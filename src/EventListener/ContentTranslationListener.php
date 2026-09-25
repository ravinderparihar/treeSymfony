<?php

namespace App\EventListener;

use App\Entity\Category;
use App\Entity\Tree;
use App\Entity\TreeTranslation;
use App\Entity\Uses;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\Event\PostLoadEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\Service\ResetInterface;

/**
 * Swaps English tree, use and category text for a translation when a GET request asks for another
 * language, via ?lang=hi or an Accept-Language header. Missing translations fall back to English.
 * Translated entities are marked read-only so the swapped text can never be flushed back to the English columns.
 */
#[AsDoctrineListener(event: Events::postLoad)]
final class ContentTranslationListener implements ResetInterface
{
    public const DEFAULT_LOCALE = 'en';
    public const LOCALES = ['en', 'hi'];

    /** @var array<string, array{tree: array, uses: array, category: array}> */
    private array $cache = [];

    public function __construct(
        private readonly RequestStack $requestStack,
        private readonly Connection $connection,
    ) {
    }

    public function postLoad(PostLoadEventArgs $args): void
    {
        $entity = $args->getObject();
        if (!$entity instanceof Tree && !$entity instanceof Uses && !$entity instanceof Category) {
            return;
        }

        $locale = $this->requestedLocale();
        if (null === $locale) {
            return;
        }

        $translations = $this->translations($locale);
        $id = $entity->getId();
        $translated = match (true) {
            $entity instanceof Tree => $this->translateTree($entity, $translations['tree'][$id] ?? null),
            $entity instanceof Uses => $this->translateUses($entity, $translations['uses'][$id] ?? null),
            $entity instanceof Category => $this->translateCategory($entity, $translations['category'][$id] ?? null),
        };

        if ($translated) {
            $args->getObjectManager()->getUnitOfWork()->markReadOnly($entity);
        }
    }

    // Null means "serve the stored English text".
    private function requestedLocale(): ?string
    {
        $request = $this->requestStack->getMainRequest();
        if (!$request instanceof Request || !$request->isMethodSafe()) {
            return null;
        }

        $lang = strtolower(trim((string) $request->query->get('lang', '')));
        $locale = in_array($lang, self::LOCALES, true)
            ? $lang
            : $request->getPreferredLanguage(self::LOCALES);

        return null === $locale || self::DEFAULT_LOCALE === $locale ? null : $locale;
    }

    private function translateTree(Tree $tree, ?array $row): bool
    {
        if (null === $row) {
            return false;
        }
        foreach (TreeTranslation::FIELDS as $field) {
            if (null !== $row[$field]) {
                $tree->$field = $row[$field];
            }
        }

        return true;
    }

    private function translateUses(Uses $uses, ?array $row): bool
    {
        if (null === $row) {
            return false;
        }
        if (null !== $row['title']) {
            $uses->setTitle($row['title']);
        }
        if (null !== $row['description']) {
            $uses->setDescription($row['description']);
        }

        return true;
    }

    private function translateCategory(Category $category, ?array $row): bool
    {
        if (null === $row || null === $row['name']) {
            return false;
        }
        $category->setName($row['name']);

        return true;
    }

    public function reset(): void
    {
        $this->cache = [];
    }

    // One query per table per request instead of one per loaded entity.
    private function translations(string $locale): array
    {
        if (isset($this->cache[$locale])) {
            return $this->cache[$locale];
        }

        $treeColumns = implode(', ', array_map(
            static fn (string $field) => strtolower(preg_replace('/[A-Z]/', '_$0', $field)) . ' AS ' . $field,
            TreeTranslation::FIELDS,
        ));

        return $this->cache[$locale] = [
            'tree' => $this->connection->fetchAllAssociativeIndexed("SELECT tree_id, $treeColumns FROM tree_translation WHERE locale = ?", [$locale]),
            'uses' => $this->connection->fetchAllAssociativeIndexed('SELECT uses_id, title, description FROM uses_translation WHERE locale = ?', [$locale]),
            'category' => $this->connection->fetchAllAssociativeIndexed('SELECT category_id, name FROM category_translation WHERE locale = ?', [$locale]),
        ];
    }
}

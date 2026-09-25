<?php

namespace App\Command;

use Doctrine\DBAL\Connection;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

#[AsCommand(name: 'app:seed-trees', description: 'Seed trees from data/seed/*.php with local names, uses and categories')]
class SeedTreesCommand extends Command
{
    private const VARCHAR_LIMIT = 255;
    private const NAME_LIMIT = 100;
    private const VARCHAR_COLUMNS = [
        'lifespan_min', 'lifespan_max', 'height_min', 'height_max', 'growth_rate', 'family_name', 'genus', 'species',
        'temperature_range', 'rainfall_requirement', 'water_requirement', 'humidity', 'altitude_range', 'soil_ph',
        'leaf_type', 'flowering_season', 'harvest_time', 'production_per_tree', 'seed_treatment', 'nursery_method',
        'planting_distance', 'fertilizer_schedule', 'irrigation_schedule', 'pruning_guide',
    ];
    // Seed-file key => tree_translation / tree column
    private const TRANSLATED_TREE_COLUMNS = [
        'desc' => 'description', 'temp' => 'temperature_range', 'rain' => 'rainfall_requirement', 'alt' => 'altitude_range',
        'leaf' => 'leaf_type', 'flower' => 'flowering_season', 'harvest' => 'harvest_time', 'prod' => 'production_per_tree',
        'seed' => 'seed_treatment', 'nursery' => 'nursery_method', 'spacing' => 'planting_distance', 'fert' => 'fertilizer_schedule',
        'irrig' => 'irrigation_schedule', 'prune' => 'pruning_guide', 'diseases' => 'common_diseases', 'insects' => 'common_insects',
        'symptoms' => 'symptoms', 'treatment' => 'treatment',
    ];

    public function __construct(
        private readonly Connection $connection,
        #[Autowire('%kernel.project_dir%/data/seed')]
        private readonly string $dataDir,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addOption('dry-run', null, InputOption::VALUE_NONE, 'Show what would be added without writing');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $dryRun = (bool) $input->getOption('dry-run');
        $files = glob($this->dataDir . '/*.php');
        sort($files);

        $this->connection->beginTransaction();
        try {
            $added = 0;
            $aliases = 0;
            foreach ($files as $file) {
                $io->section(basename($file));
                $data = self::load($file);
                $added += $this->seedTrees($io, $data['trees'], $data['generic']);
                $aliases += $this->seedAliases($data['aliases']);
            }

            // Each sub-directory holds translations for one locale, e.g. data/seed/hi/
            $translated = [];
            foreach (glob($this->dataDir . '/*', GLOB_ONLYDIR) as $localeDir) {
                $locale = basename($localeDir);
                $io->section("Translations: $locale");
                $translated[] = $this->seedTranslations($io, $locale, $localeDir);
            }

            $summary = "$added trees and $aliases local name aliases";
            foreach ($translated as [$locale, $trees, $uses, $categories]) {
                $summary .= "; $locale: $trees trees, $uses uses, $categories categories";
            }
            if ($dryRun) {
                $this->connection->rollBack();
                $io->note("Dry run: would write $summary. Nothing was written.");
            } else {
                $this->connection->commit();
                $io->success("Wrote $summary.");
            }
        } catch (\Throwable $e) {
            if ($this->connection->isTransactionActive()) {
                $this->connection->rollBack();
            }
            throw $e;
        }

        return Command::SUCCESS;
    }

    private function seedTrees(SymfonyStyle $io, array $trees, array $generic): int
    {
        $added = 0;
        foreach ($trees as $t) {
            if ($this->treeExists($t['sci'], $t['en'])) {
                $io->writeln("skip  {$t['en']} ({$t['sci']}) - already exists");
                continue;
            }

            $row = [
                'scientific_name' => $t['sci'],
                'description' => $t['desc'] . ' Commonly found in ' . $t['region'] . '.',
                'lifespan_min' => $t['life'][0],
                'lifespan_max' => $t['life'][1],
                'height_min' => $t['height'][0],
                'height_max' => $t['height'][1],
                'growth_rate' => $t['growth'],
                'status' => 1,
                'family_name' => $t['family'],
                'genus' => $t['genus'],
                'species' => $t['species'],
                'temperature_range' => $t['temp'],
                'rainfall_requirement' => $t['rain'],
                'water_requirement' => $t['water'],
                'humidity' => $t['humidity'],
                'altitude_range' => $t['alt'],
                'sandy_soil' => $t['soil'][0],
                'clay_soil' => $t['soil'][1],
                'loamy_soil' => $t['soil'][2],
                'soil_ph' => $t['ph'],
                'leaf_type' => $t['leaf'],
                'flowering_season' => $t['flower'],
                'harvest_time' => $t['harvest'],
                'production_per_tree' => $t['prod'],
                'seed_treatment' => $t['seed'],
                'nursery_method' => $t['nursery'] ?? $generic['nursery'],
                'planting_distance' => $t['spacing'],
                'fertilizer_schedule' => $t['fert'] ?? $generic['fert'],
                'irrigation_schedule' => $t['irrig'] ?? $generic['irrig'],
                'pruning_guide' => $t['prune'] ?? $generic['prune'],
                'common_diseases' => $t['diseases'],
                'common_insects' => $t['insects'],
                'symptoms' => $t['symptoms'],
                'treatment' => $t['treatment'],
            ];
            foreach (self::VARCHAR_COLUMNS as $column) {
                $this->assertLength($row[$column], self::VARCHAR_LIMIT, "{$t['en']}: $column");
            }
            foreach ($t['uses'] as [$title]) {
                $this->assertLength($title, self::NAME_LIMIT, "{$t['en']}: use title");
            }

            $this->connection->insert('tree', $row);
            $treeId = (int) $this->connection->lastInsertId();
            $this->addLocalName($treeId, 'English', $t['en']);
            $this->addLocalName($treeId, 'Hindi', $t['hi']);
            foreach ($t['uses'] as [$title, $description]) {
                $this->connection->insert('uses', ['title' => $title, 'description' => $description, 'status' => 1, 'treeId' => $treeId]);
            }
            foreach ($t['categories'] as $category) {
                $this->connection->insert('tree_categories', ['tree_id' => $treeId, 'category_id' => $this->categoryId($category)]);
            }

            $io->writeln("add   {$t['en']} ({$t['sci']}) as #$treeId");
            ++$added;
        }

        return $added;
    }

    private function seedAliases(array $aliasesByScientificName): int
    {
        $added = 0;
        foreach ($aliasesByScientificName as $scientificName => $names) {
            $treeIds = $this->connection->fetchFirstColumn('SELECT id FROM tree WHERE scientific_name = ?', [$scientificName]);
            foreach ($treeIds as $treeId) {
                foreach ($names as [$language, $name]) {
                    $exists = $this->connection->fetchOne('SELECT COUNT(*) FROM local_names WHERE treeId = ? AND localName = ?', [$treeId, $name]);
                    if (!$exists) {
                        $this->addLocalName((int) $treeId, $language, $name);
                        ++$added;
                    }
                }
            }
        }

        return $added;
    }

    /**
     * Replaces the stored translations for every tree, use and category listed in $localeDir/*.php.
     * A field left out of a translated tree falls back to the translated 'generic' text only when the English
     * tree also uses the English generic text; otherwise it is reported so no English-only detail goes unnoticed.
     *
     * @return array{string, int, int, int}
     */
    private function seedTranslations(SymfonyStyle $io, string $locale, string $localeDir): array
    {
        $trees = $uses = $categories = 0;
        $warnings = [];
        $files = glob($localeDir . '/*.php');
        sort($files);

        foreach ($files as $file) {
            $data = self::load($file);
            $englishFile = $this->dataDir . '/' . basename($file);
            $englishGeneric = is_file($englishFile) ? self::load($englishFile)['generic'] : [];
            $generic = $data['generic'] ?? [];

            foreach ($data['categories'] ?? [] as $englishName => $name) {
                $categoryId = $this->connection->fetchOne('SELECT id FROM category WHERE name = ?', [$englishName]);
                if (false === $categoryId) {
                    $warnings[] = "Category $englishName not found";
                    continue;
                }
                $this->assertLength($name, self::NAME_LIMIT, "Category $englishName ($locale)");
                $this->connection->delete('category_translation', ['category_id' => $categoryId, 'locale' => $locale]);
                $this->connection->insert('category_translation', ['category_id' => $categoryId, 'locale' => $locale, 'name' => $name]);
                ++$categories;
            }

            foreach ($data['trees'] as $t) {
                $treeId = $this->treeId($t['sci'], $t['en']);
                if (null === $treeId) {
                    $warnings[] = "{$t['en']} ({$t['sci']}) not found";
                    continue;
                }

                $english = $this->connection->fetchAssociative('SELECT * FROM tree WHERE id = ?', [$treeId]);
                $row = ['tree_id' => $treeId, 'locale' => $locale];
                foreach (self::TRANSLATED_TREE_COLUMNS as $key => $column) {
                    $value = $t[$key] ?? null;
                    if (null === $value && null !== $english[$column] && '' !== $english[$column]) {
                        if (isset($generic[$key], $englishGeneric[$key]) && $english[$column] === $englishGeneric[$key]) {
                            $value = $generic[$key];
                        } else {
                            $warnings[] = "{$t['en']}: $column has no $locale text";
                        }
                    }
                    $row[$column] = $value;
                }
                $this->connection->delete('tree_translation', ['tree_id' => $treeId, 'locale' => $locale]);
                $this->connection->insert('tree_translation', $row);
                ++$trees;

                $translatedUses = $t['uses'] ?? [];
                foreach ($this->connection->fetchAllKeyValue('SELECT id, title FROM uses WHERE treeId = ?', [$treeId]) as $usesId => $englishTitle) {
                    if (!isset($translatedUses[$englishTitle])) {
                        $warnings[] = "{$t['en']}: use \"$englishTitle\" has no $locale text";
                        continue;
                    }
                    [$title, $description] = $translatedUses[$englishTitle];
                    $this->assertLength($title, self::NAME_LIMIT, "{$t['en']}: use title ($locale)");
                    $this->connection->delete('uses_translation', ['uses_id' => $usesId, 'locale' => $locale]);
                    $this->connection->insert('uses_translation', ['uses_id' => $usesId, 'locale' => $locale, 'title' => $title, 'description' => $description]);
                    ++$uses;
                }
            }
        }

        $untranslated = $this->connection->fetchFirstColumn(
            "SELECT CONCAT(t.id, ' ', t.scientific_name) FROM tree t LEFT JOIN tree_translation tt ON tt.tree_id = t.id AND tt.locale = ? WHERE tt.id IS NULL",
            [$locale],
        );
        foreach ($untranslated as $tree) {
            $warnings[] = "Tree #$tree has no $locale translation";
        }

        $io->writeln("$locale: $trees trees, $uses uses, $categories categories");
        if ($warnings) {
            $io->warning($warnings);
        }

        return [$locale, $trees, $uses, $categories];
    }

    // Seed files define helper variables such as $trees and $generic; keep them out of the caller's scope.
    private static function load(string $file): array
    {
        return require $file;
    }

    private function assertLength(string $value, int $limit, string $label): void
    {
        if (mb_strlen($value) > $limit) {
            throw new \RuntimeException("$label is longer than $limit characters");
        }
    }

    // Moru and Tilonj share a scientific name, so the English name is part of the identity.
    private function treeExists(string $scientificName, string $englishName): bool
    {
        return null !== $this->treeId($scientificName, $englishName);
    }

    private function treeId(string $scientificName, string $englishName): ?int
    {
        $id = $this->connection->fetchOne(
            'SELECT t.id FROM tree t JOIN local_names n ON n.treeId = t.id WHERE t.scientific_name = ? AND n.language = ? AND n.localName = ? ORDER BY t.id',
            [$scientificName, 'English', $englishName],
        );

        return false === $id ? null : (int) $id;
    }

    private function addLocalName(int $treeId, string $language, string $name): void
    {
        $this->assertLength($name, self::NAME_LIMIT, "Local name $name");
        $this->connection->insert('local_names', ['language' => $language, 'localName' => $name, 'treeId' => $treeId]);
    }

    private function categoryId(string $name): int
    {
        $id = $this->connection->fetchOne('SELECT id FROM category WHERE name = ?', [$name]);
        if (false === $id) {
            $this->connection->insert('category', ['name' => $name]);
            $id = $this->connection->lastInsertId();
        }

        return (int) $id;
    }
}

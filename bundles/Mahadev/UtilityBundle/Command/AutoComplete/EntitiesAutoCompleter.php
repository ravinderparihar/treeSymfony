<?php

namespace Mahadev\UtilityBundle\Command\AutoComplete;

use Doctrine\ORM\EntityManagerInterface;

/**
 * Provides auto-completion suggestions for entities.
 *
 * @author Charles Sarrazin <charles@sarraz.in>
 */
class EntitiesAutoCompleter
{
    private $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function getSuggestions()
    {
        $configuration = $this->manager
            ->getConfiguration();

        $namespaceReplacements = array();

        foreach ($configuration->getEntityNamespaces() as $alias => $namespace) {
            $namespaceReplacements[$namespace.'\\'] = $alias.':';
        }

        $entities = $configuration
            ->getMetadataDriverImpl()
            ->getAllClassNames()
        ;

        return array_map(function ($entity) use ($namespaceReplacements) {
            return strtr($entity, $namespaceReplacements);
        }, $entities);
    }
}

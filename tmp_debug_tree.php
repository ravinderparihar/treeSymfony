<?php

require __DIR__.'/vendor/autoload.php';
require __DIR__.'/config/bootstrap.php';

use App\Kernel;
use App\Entity\Tree;
use ApiPlatform\Metadata\IdentifiersExtractorInterface;

$kernel = new Kernel('dev', true);
$kernel->boot();
$container = $kernel->getContainer();

try {
    $extractor = $container->get('api_platform.api.identifiers_extractor');
    echo 'extractor: '.get_class($extractor)."\n";
    $tree = new Tree();
    try {
        $ids = $extractor->getIdentifiersFromItem($tree);
        var_export($ids);
        echo "\n";
    } catch (Throwable $e) {
        echo get_class($e).': '.$e->getMessage()."\n";
    }

    $resourceClass = Tree::class;
    $resourceMetadataFactory = $container->get('api_platform.metadata.resource.metadata_factory');
    $resources = $resourceMetadataFactory->create($resourceClass);
    echo 'resource class: '.get_class($resources)."\n";
    echo 'resource name: '.($resources->getShortName() ?? 'none')."\n";
    echo 'item operations:'.PHP_EOL;
    foreach ($resources->getItemOperations() as $name => $operation) {
        echo ' - '.$name.' ('.get_class($operation).')'.PHP_EOL;
    }
    echo 'collection operations:'.PHP_EOL;
    foreach ($resources->getCollectionOperations() as $name => $operation) {
        echo ' - '.$name.' ('.get_class($operation).')'.PHP_EOL;
    }
    echo 'identifiers: '.implode(',', $resources->getIdentifiers())."\n";
    echo 'iri: '.($resources->getIri() ?? 'none')."\n";
} catch (Throwable $e) {
    echo 'EXCEPTION: '.get_class($e).': '.$e->getMessage()."\n";
}

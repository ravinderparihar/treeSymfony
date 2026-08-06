<?php
require __DIR__ . '/vendor/autoload.php';

use App\Kernel;
use App\Entity\Tree;
use Symfony\Component\Dotenv\Dotenv;

if (file_exists(__DIR__ . '/.env')) {
    (new Dotenv())->bootEnv(__DIR__.'/.env');
}

$kernel = new Kernel('dev', true);
$kernel->boot();
$container = $kernel->getContainer();

try {
    $extractor = $container->get('api_platform.api.identifiers_extractor');
    echo 'extractor: ' . get_class($extractor) . "\n";
    $tree = new Tree();
    try {
        $ids = $extractor->getIdentifiersFromItem($tree);
        var_export($ids);
        echo "\n";
    } catch (Throwable $e) {
        echo get_class($e) . ': ' . $e->getMessage() . "\n";
    }

    echo "\nResource metadata:\n";
    $resourceMetadataFactory = $container->get('api_platform.metadata.resource_factory');
    $resources = $resourceMetadataFactory->create(Tree::class);
    echo 'short name: ' . $resources->getShortName() . "\n";
    echo 'iri: ' . ($resources->getIri() ?? 'none') . "\n";
    echo 'identifiers: ' . implode(',', $resources->getIdentifiers()) . "\n";
    echo 'item ops: ' . implode(',', array_keys($resources->getItemOperations())) . "\n";
    echo 'collection ops: ' . implode(',', array_keys($resources->getCollectionOperations())) . "\n";
} catch (Throwable $e) {
    echo 'EXCEPTION: ' . get_class($e) . ': ' . $e->getMessage() . "\n";
}

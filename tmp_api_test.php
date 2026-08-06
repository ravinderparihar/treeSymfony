<?php
require __DIR__.'/vendor/autoload.php';
require __DIR__.'/config/bootstrap.php';
use App\Kernel;
use App\Entity\Tree;

$kernel = new Kernel('dev', true);
$kernel->boot();
$container = $kernel->getContainer();
$extractor = $container->get('api_platform.api.identifiers_extractor');
echo get_class($extractor)."\n";
$tree = new Tree();
try {
    var_export($extractor->getIdentifiersFromItem($tree));
    echo "\n";
} catch (Throwable $e) {
    echo get_class($e).": ".$e->getMessage()."\n";
}

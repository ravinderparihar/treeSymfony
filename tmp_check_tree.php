<?php
require __DIR__ . '/vendor/autoload.php';

var_dump(class_exists('App\\Entity\\Tree'));

if (class_exists('App\\Entity\\Tree')) {
    $r = new ReflectionClass('App\\Entity\\Tree');
    echo $r->getFileName() . PHP_EOL;
}

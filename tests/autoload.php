<?php

require __DIR__ . '/../vendor/autoload.php';

$loader = new Composer\Autoload\ClassLoader();

$loader->addPsr4('CodePress\\CodeTag\\', __DIR__. '/../../codetag/src/CodeTag');
$loader->addPsr4('CodePress\\CodeCategory\\', __DIR__. '/../../codecategory/src/CodeCategory');

$loader->register();
<?php declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/constants.php';
require_once __DIR__ . '/tools.php';

Tester\Dumper::$dumpDir = OutputDir;
Tester\Environment::setup();

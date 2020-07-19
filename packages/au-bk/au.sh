#!/usr/bin/env php

<?php

$app = require __DIR__ . '/bootstrap/console.php';

/**
 * Resolve Symfony Console
 */
$console = $app->resolve(\Symfony\Component\Console\Application::class);

/**
 * Run Command
 */
$console->run();

/**
 * Exit Console Application
 */
exit();

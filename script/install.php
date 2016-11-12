<?php

// Change to the project root, to simplify resolving paths
chdir(dirname(__DIR__));
require 'vendor/autoload.php';

$container = include 'config/container.php';

use zaboy\utils\DataStore\Installer as DataStoreInstaller;

$dataStoreInstaller = new DataStoreInstaller;
$dataStoreInstaller->install();

exit;


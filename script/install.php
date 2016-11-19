<?php

// Change to the project root, to simplify resolving paths
chdir(dirname(__DIR__));
require 'vendor/autoload.php';

$container = include 'config/container.php';

use zaboy\utils\DataStore\Installer as DataStoreInstaller;
use zaboy\utils\DataStore\RockyMountain\Installer as RockyMountainInstaller;
use zaboy\utils\RockyMountain\GiftCards;
use zaboy\utils\DataStore\RockyMountain\Orders;

//$dataStoreInstaller = new DataStoreInstaller;
//$dataStoreInstaller->install();

$rockyMountainInstaller = new RockyMountainInstaller;
$rockyMountainInstaller->install([Orders::TABLE_NAME]);

exit;


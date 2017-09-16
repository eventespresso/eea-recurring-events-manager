<?php
/**
 * Bootstrap for EE4 Addon Skeleton Unit Tests
 */
use EETests\bootstrap\AddonLoader;

$core_tests_dir = dirname(dirname(__DIR__)) . '/event-espresso-core/tests/';
//if still don't have $core_tests_dir, then let's check tmp folder.
if (! is_dir($core_tests_dir)) {
    $core_tests_dir = '/tmp/event-espresso-core/tests/';
}
require $core_tests_dir . 'includes/CoreLoader.php';
require $core_tests_dir . 'includes/AddonLoader.php';

define('EEADDON_PLUGIN_DIR', dirname(__DIR__) . '/');
define('EEADDON_TESTS_DIR', EEADDON_PLUGIN_DIR . 'tests/');

$addon_loader = new AddonLoader(
    EEADDON_TESTS_DIR,
    EEADDON_PLUGIN_DIR,
    'eea-recurring-events-manager.php'
);
$addon_loader->init();

<?php
declare(strict_types=1);

define('DS', DIRECTORY_SEPARATOR);
define('PUBLIC_DIR', __DIR__ . DS);
define('ROOT_DIR', dirname(PUBLIC_DIR) . DS);
define('CONFIG_DIR', ROOT_DIR . 'config' . DS);
define('VENDOR_DIR', ROOT_DIR . 'vendor' . DS);
define('CACHE_DIR', ROOT_DIR . 'cache' . DS);
define('LOGS_DIR', ROOT_DIR . 'logs' . DS);
require VENDOR_DIR . 'autoload.php';

use App\BootLoader;

try {
    BootLoader::init();
} catch (\Exception $e) {
    echo "Error while booting: " . $e->getMessage() . PHP_EOL;
    echo "<br /><xmp>{$e->getTraceAsString()}</xmp>";
}
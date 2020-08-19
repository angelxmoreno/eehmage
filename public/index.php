<?php
declare(strict_types=1);

define('DS', DIRECTORY_SEPARATOR);
define('PUBLIC_DIR', __DIR__ . DS);
define('ROOT_DIR', dirname(PUBLIC_DIR) . DS);
define('CONFIG_DIR', ROOT_DIR . 'config' . DS);
define('VENDOR_DIR', ROOT_DIR . 'vendor' . DS);
define('CACHE_DIR', ROOT_DIR . 'cache' . DS);
define('LOGS_DIR', ROOT_DIR . 'logs' . DS);
define('DEVELOPMENT', 'development');
require VENDOR_DIR . 'autoload.php';

use App\BootLoader;

try {
    BootLoader::init();
} catch (\Exception $e) {
    $description = 'Error while booting: ' . $e->getMessage();
    fwrite(fopen('php://stderr', 'w'), $description);
    header('X-PHP-Response-Code: 500', true, 500);
    echo json_encode([
        'statusCode' => 500,
        'error' => [
            'type' => get_class($e),
            'description' => $description,
        ],
    ]);
}
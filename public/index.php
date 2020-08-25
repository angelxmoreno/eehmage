<?php
declare(strict_types=1);

define('DS', DIRECTORY_SEPARATOR);
define('PUBLIC_DIR', __DIR__ . DS);
define('ROOT_DIR', dirname(PUBLIC_DIR) . DS);
define('CONFIG_DIR', ROOT_DIR . 'config' . DS);
define('VENDOR_DIR', ROOT_DIR . 'vendor' . DS);
define('CACHE_DIR', ROOT_DIR . 'cache' . DS);
define('LOGS_DIR', ROOT_DIR . 'logs' . DS);
define('UPLOADS_DIR', PUBLIC_DIR . 'uploads' . DS);
define('UPLOADS_TMP', CACHE_DIR . 'uploads' . DS);

define('DEVELOPMENT', 'development');
require VENDOR_DIR . 'autoload.php';

use App\BootLoader;
use App\Errors\ErrorObj;

try {
    BootLoader::init();
} catch (\Exception $exception) {
    $description = 'Error while booting: ' . $exception->getMessage();
    header('X-PHP-Response-Code: 500', true, 500);
    header('Content-Type: application/json');
    echo json_encode(new ErrorObj($exception, false));
}
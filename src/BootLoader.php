<?php
declare(strict_types=1);

namespace App;

use App\Handlers\HttpErrorHandler;
use App\Handlers\ShutdownHandler;
use DI\Container;
use DI\ContainerBuilder;
use Dotenv\Dotenv;
use Exception;
use Slim\App;
use Slim\Factory\AppFactory;
use Slim\Factory\ServerRequestCreatorFactory;

/**
 * Class BootLoader
 * @package App
 *
 */
class BootLoader
{

    /**
     * @throws Exception
     */
    public static function init()
    {
        self::loadEnv();

        // Instantiate PHP-DI ContainerBuilder
        $container = self::buildContainer();

        // Instantiate the app
        AppFactory::setContainer($container);
        $app = AppFactory::create();

        // Register middleware
        self::interpolateApp($app, CONFIG_DIR . 'middleware.php');

        // Register routes
        self::interpolateApp($app, CONFIG_DIR . 'routes.php');

        // Add ErrorHandlers
        self::addErrorHandlers($app);

        $app->run();
    }

    protected static function loadEnv()
    {
        (Dotenv::createImmutable(ROOT_DIR))->load();
    }

    /**
     * @return Container
     * @throws Exception
     */
    protected static function buildContainer(): Container
    {
        $builder = new ContainerBuilder();
        $definitions = require CONFIG_DIR . 'definitions.php';
        $builder->addDefinitions($definitions);
        if (!self::isDev()) {
            $builder->enableCompilation(CACHE_DIR . 'containerCompilation');
            $builder->writeProxiesToFile(true, CACHE_DIR . 'containerProxies');
        }

        return $builder->build();
    }

    /**
     * @return bool
     */
    public static function isDev(): bool
    {
        return $_ENV['ENV'] === DEVELOPMENT;
    }

    /**
     * @param App $app
     * @param string $function_path
     */
    protected static function interpolateApp(App $app, string $function_path)
    {
        $func = require $function_path;
        $func($app);
    }

    /**
     * @param App $app
     */
    protected static function addErrorHandlers(App $app)
    {
        $callable_resolver = $app->getCallableResolver();
        $response_factory = $app->getResponseFactory();

        $server_request_creator = ServerRequestCreatorFactory::create();
        $request = $server_request_creator->createServerRequestFromGlobals();

        $error_handler = new HttpErrorHandler($callable_resolver, $response_factory);
        $shutdown_handler = new ShutdownHandler($request, $error_handler, self::isDev());
        register_shutdown_function($shutdown_handler);


        // Add Error Handling Middleware
        $error_middleware = $app->addErrorMiddleware(self::isDev(), false, false);
        $error_middleware->setDefaultErrorHandler($error_handler);
    }
}
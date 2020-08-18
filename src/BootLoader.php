<?php
declare(strict_types=1);

namespace App;

use DI\Container;
use DI\ContainerBuilder;
use Dotenv\Dotenv;
use Exception;
use Psr\Http\Message\ServerRequestInterface;
use Slim\App;
use Slim\Exception\HttpMethodNotAllowedException;
use Slim\Exception\HttpNotFoundException;
use Slim\Factory\AppFactory;
use Slim\Psr7\Response;
use Throwable;

/**
 * Class BootLoader
 * @package App
 *
 * @TODO add error handling http://www.slimframework.com/docs/v4/middleware/error-handling.html
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

        if (false) {
            //@TODO use proper path and .env to determine isProd
            $builder->enableCompilation(CACHE_DIR . 'container');
        }

        return $builder->build();
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

        /**
         * Add Error Handling Middleware
         *
         * @param bool $displayErrorDetails -> Should be set to false in production
         * @param bool $logErrors -> Parameter is passed to the default ErrorHandler
         * @param bool $logErrorDetails -> Display error details in error log
         * which can be replaced by a callable of your choice.
         * Note: This middleware should be added last. It will not handle any exceptions/errors
         * for middleware added after it.
         */
        $errorMiddleware = $app->addErrorMiddleware(true, true, true);

        // Set the Not Found Handler
        $errorMiddleware->setErrorHandler(
            HttpNotFoundException::class,
            function (ServerRequestInterface $request, Throwable $exception, bool $displayErrorDetails) {
                $response = new Response();
                $response->getBody()->write('404 NOT FOUND');

                return $response->withStatus(404);
            });

        // Set the Not Allowed Handler
        $errorMiddleware->setErrorHandler(
            HttpMethodNotAllowedException::class,
            function (ServerRequestInterface $request, Throwable $exception, bool $displayErrorDetails) {
                $response = new Response();
                $response->getBody()->write('405 NOT ALLOWED');

                return $response->withStatus(405);
            });
    }
}
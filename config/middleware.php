<?phpdeclare(strict_types=1);namespace App;use Middlewares\TrailingSlash;use Slim\App;use Slim\Middleware\ContentLengthMiddleware;return function (App $app) {    $app->add(new ContentLengthMiddleware);    $app->addRoutingMiddleware();    $app->add(new TrailingSlash(true)); // true adds the trailing slash (false removes it)};
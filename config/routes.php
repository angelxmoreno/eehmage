<?php
declare(strict_types=1);

use App\Actions;
use App\Middlewares\ValidApiKeyMiddleware;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

return function (App $app) {
    $app->get('/', Actions\HomeAction::class);

    $app->group('/groups[/{id}]', function (RouteCollectorProxy $group) {
        $group->any('', Actions\GroupsAction::class);
    })->add(ValidApiKeyMiddleware::class);

    $app->group('/images[/{id}]', function (RouteCollectorProxy $group) {
        $group->any('', Actions\ImagesAction::class);
    })->add(ValidApiKeyMiddleware::class);
};
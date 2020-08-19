<?php
declare(strict_types=1);

use App\Actions;
use Slim\App;

return function (App $app) {
    $app->get('/hello[/{name}]', Actions\HomeAction::class);
    $app->get('/', Actions\HomeAction::class);
};
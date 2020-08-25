<?php
declare(strict_types=1);

use Illuminate\Container\Container as IlluminateContainer;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Events\Dispatcher as IlluminateDispatcher;
use Psr\Container\ContainerInterface;

return [
    'app.apiKey' => $_ENV['APP_API_KEY'],
    'app.baseUrl' => $_ENV['BASE_URL'],
    'app.env' => $_ENV['ENV'],
    'db.url' => $_ENV['DATABASE_URL'],
    Capsule::class => function (ContainerInterface $c) {
        $illuminate_container = new IlluminateContainer;
        $illuminate_dispatcher = new IlluminateDispatcher($illuminate_container);
        $capsule = new Capsule;
        $capsule->setEventDispatcher($illuminate_dispatcher);

        $capsule->addConnection([
            'driver' => 'mysql',
            'url' => $c->get('db.url'),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'options' => [
                // Turn off persistent connections
                PDO::ATTR_PERSISTENT => false,
                // Enable exceptions
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                // Emulate prepared statements
                PDO::ATTR_EMULATE_PREPARES => true,
                // Set default fetch mode to array
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                // Set character set
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci'
            ],
        ]);
        // Make this Capsule instance available globally via static methods
        $capsule->setAsGlobal();
        // Setup the Eloquent ORM...
        $capsule->bootEloquent();
        return $capsule;
    }
];

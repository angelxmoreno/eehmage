<?php
/**
 * This class has been auto-generated by PHP-DI.
 */
class CompiledContainer extends DI\CompiledContainer{
    const METHOD_MAPPING = array (
  'app.apiKey' => 'get1',
  'app.baseUrl' => 'get2',
  'app.env' => 'get3',
  'db.url' => 'get4',
  'Illuminate\\Database\\Capsule\\Manager' => 'get5',
);

    protected function get1()
    {
        return 'When7Eight9';
    }

    protected function get2()
    {
        return 'http://localhost:8311';
    }

    protected function get3()
    {
        return 'development';
    }

    protected function get4()
    {
        return 'mysql://eehmage:eehmage@mysql/eehmage';
    }

    protected function get5()
    {
        return $this->resolveFactory(static function (\Psr\Container\ContainerInterface $c) {
        $illuminate_container = new \Illuminate\Container\Container;
        $illuminate_dispatcher = new \Illuminate\Events\Dispatcher($illuminate_container);
        $capsule = new \Illuminate\Database\Capsule\Manager;
        $capsule->setEventDispatcher($illuminate_dispatcher);

        $capsule->addConnection([
            'driver' => 'mysql',
            'url' => $c->get('db.url'),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'options' => [
                // Turn off persistent connections
                \PDO::ATTR_PERSISTENT => false,
                // Enable exceptions
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                // Emulate prepared statements
                \PDO::ATTR_EMULATE_PREPARES => true,
                // Set default fetch mode to array
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                // Set character set
                \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci'
            ],
        ]);
        // Make this Capsule instance available globally via static methods
        $capsule->setAsGlobal();
        // Setup the Eloquent ORM...
        $capsule->bootEloquent();
        return $capsule;
    }, 'Illuminate\\Database\\Capsule\\Manager');
    }

}

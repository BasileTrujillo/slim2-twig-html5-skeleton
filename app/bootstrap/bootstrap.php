<?php

/**
 * Application components bootstrap file
 *
 * @author Basile Trujillo
 * @link https://bitbucket.org/L0gIn/slim-twig-laravel-html5-boilerplate
 */

define('__BASE_DIR', dirname(dirname(__DIR__)) . '/');

require __BASE_DIR . 'vendor/autoload.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);

// Start native session to get a session id for the Laravel session store and
// filesystem session handler
session_name('app_session');
session_start();

// Environment based configuration
// Allows developers using the getenv('<name>') method to fetch configuration values
if (file_exists(__BASE_DIR . '.env')) {
    Dotenv::load(__BASE_DIR);
}

// IoC container setup
$container = new \Illuminate\Container\Container();

// Application configuration
$config = include(__BASE_DIR . 'app/config/config.php');
$config = new \App\Components\Config\Config($config);

$container->instance('\App\Components\Config\Config', $config);
$container->alias('\App\Components\Config\Config', 'config');

// Slim application setup
$container->singleton('Slim\\Slim', function ($container) use ($config)
{
    $app = new \Slim\Slim(array(
        'debug' => $config->get('app.debug'),
        'mode' => $config->get('app.mode'),
        'log.enabled' => $config->get('app.logging.enabled'),
        'log.level' => $config->get('app.logging.level'),
        'log.writer' => new \App\Components\Logging\FileSystemLogWriter(__BASE_DIR . 'app/storage/logs/' . date('ymd') . '.log'),
        'templates.path' => __BASE_DIR . 'app/ressources/views',
        'view' => new \Slim\Views\Twig(),
        'assets.path' => $config->get('assets.'.$config->get('app.mode')),
        'misc.conf' => $config->get('misc'),
    ));

    // Twig template engine setup
    $app->view()->parserOptions = array(
        'debug' => $config->get('app.debug'),
        'cache' => __BASE_DIR . 'app/storage/cache/views'
    );

    $twigExtensions = array(
        new \Slim\Views\TwigExtension()
    );
    if ($config->get('app.debug')) {
        $twigExtensions[] = new Twig_Extension_Debug();
    }
    $app->view()->parserExtensions = $twigExtensions;

    // Laravel session component start and shutdown configuration
    $app->hook('slim.before', function ($container) use ($container)
    {
        $container->make('session')->start();
    });

    $app->hook('slim.after.router', function ($container) use ($container)
    {
        $container->make('session')->save();
    });

    return $app;
});

$container->alias('Slim\\Slim', 'app');

// Setup router for simple controller routing
$container->singleton('App\\Components\\Routing\\Router', function ($container)
{
    $router = new \App\Components\Routing\Router($container->make('app'), $container);
    $router->setControllerNamespace('\\App\\Http\\Controllers');

    return $router;
});

$container->alias('App\\Components\\Routing\\Router', 'router');

// Filesystem access
$container->singleton('Illuminate\\Filesystem\\Filesystem', function ()
{
    return new Illuminate\Filesystem\Filesystem;
});

$container->alias('Illuminate\\Filesystem\\Filesystem', 'file');

// Session component
$container->singleton('Illuminate\\Session\\Store', function ($container)
{
    $handler = new \Illuminate\Session\FileSessionHandler(
        $container->make('file'),
        __BASE_DIR . 'app/storage/sessions'
    );

    return new \Illuminate\Session\Store('app_session', $handler, session_id());
});

$container->alias('Illuminate\\Session\\Store', 'session');


// Events component
$container->singleton('Illuminate\\Events\\Dispatcher', function ($container) {
    return new \Illuminate\Events\Dispatcher($container);
});

$container->alias('Illuminate\\Events\\Dispatcher', 'events');

// Database component
$capsule = new Illuminate\Database\Capsule\Manager;

$capsule->addConnection([
    'driver'    => $config->get('database.driver'),
    'host'      => $config->get('database.host'),
    'database'  => $config->get('database.database'),
    'username'  => $config->get('database.username'),
    'password'  => $config->get('database.password'),
    'charset'   => $config->get('database.charset'),
    'collation' => $config->get('database.collation'),
    'prefix'    => $config->get('database.prefix'),
]);

// Set the event dispatcher used by Eloquent models... (optional)
$capsule->setEventDispatcher($container->make('Illuminate\\Events\\Dispatcher'));

// Make this Capsule instance available globally via static methods... (optional)
$capsule->setAsGlobal();

// Setup the Eloquent ORM... (optional; unless you've used setEventDispatcher())
$capsule->bootEloquent();

$container->instance('Illuminate\\Database\\Capsule\\Manager', $capsule);

$container->alias('Illuminate\\Database\\Capsule\\Manager', 'db');

// additional globally available components

/**
 * @var Slim\Slim
 */
$app = $container->make('app');

/**
 * @var App\Components\Routing\Router
 */
$router = $container->make('router');
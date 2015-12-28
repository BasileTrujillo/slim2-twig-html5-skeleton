#Website Boilerplate

This website boilerplate is based on the [Slim Framework](http://www.slimframework.com/), Twig and a couple of useful Laravel Framework components.

##Why?

As many frameworks provide quite a lot of components and features that seem to be overhead in small projects, the Slim Framework provide a lightweight and powerfull system.

I recommand to use it with PHP 5.6 or 7 (not fully tested yet)

##Included componants

This project starter with a mix of good practices within differents boilerplates.
The following tools and concepts are used :

 * [Composer](https://getcomposer.org/) to load external libraries
 * [Gulp](http://gulpjs.com/) to manage asset deployement (and other nice stuffs).
 * [Slim Framework](http://www.slimframework.com/) to manage routing.
 * [PHP dotenv](https://github.com/vlucas/phpdotenv) to load environement config
 * [Laravel framework components](http://www.laravel.com/docs) from version 4.x, as these require PHP 5.3 only
     * [IoC Container](https://github.com/illuminate/container) which allows automatic dependency injection of Controller constructor parameters
     * [Session](https://github.com/illuminate/session) - Session manager
     * [Database](https://github.com/illuminate/database) - Database manager
 * [Twig template engine](http://twig.sensiolabs.org/) - Templating system
 * [Zepto.JS](http://zeptojs.com/) to have a lightweight cross-browser JS library.
 * [apiDoc](https://github.com/apidoc/apidoc) to generate web service documentation

##Installation

For installation execute the following commands :

    $ git clone https://bitbucket.org/L0gIn/slim-twig-laravel-html5-boilerplate.git .
    $ composer install

##Docs

###Configuration

The application can be easily configured using the *app/config/config.php* file.

There are defined a couple of configuration values by default. They can be changed as described and new configuration values may be added as required.

The configuration object is available as as *$config* inside routes.php and can be injected into Controllers by using the *\App\Components\Config\Config $config* parameter.

####Environment based configuration

Within the application base directory exists a *.env.example* file, which can be used for environment based configuration.

Simply rename the file to *.env* and adjust the settings. Settings are read from within *app/config/config.php* using PHP's *getenv()* function.

For further information see: [https://github.com/vlucas/phpdotenv](https://github.com/vlucas/phpdotenv)

####Database

There are default MSQL database credentials provided within the *app/config/config.php* file as a fallback for development systems.
You have to set db config by adding following lines into *.env* file.

    database.database=dbname
    database.username=foo
    database.password=bar

###Dependency Injection

The website boilerplate makes use of Laravel's *IoC (Inversion of Control)* component, which allows automatic dependency injection of Controller constructor parameters.

Let's take a look at the *HomeController*, that comes with the application by default:

    class HomeController extends BaseController
    {
        /**
         * @var \Slim\Slim
         */
        private $app;
    
        public function __construct(\Slim\Slim $app) // automatically provided by Laravel's IoC container
        {
            $this->app = $app;
        }
    
        /**
         * Show the home page
         */
        public function indexAction()
        {
            $this->app->render('home.twig');
        }
    }

Note, that it is possible to automatically load any resource from the IoC container, that is registered. You can see how this works in *app/bootstrap/bootstrap.php*.

Further information can be found at http://laravel.com/docs/4.1/ioc

###Routing

A simple router class as a wrapper for the *Slim\Slim* application class is available within the *routes.php* file as *$router*.

It provides a simple way to add controller based routing:


    // The base namespace \App\Http\Controllers\ is set within the app/boilerplate/boilerplate.php file
    // \App\Http\Controllers\HomeController
    $router->get('/home', 'HomeController::index');
    
    // A simple Slim route using the Router component
    $router->get('/test/:something', function ($something) {
        echo htmlspecialchars($something);
    });

###Database

The Laravel database component can be easily injected into Controllers:

    class ExampleController extends BaseController
    {
        /**
         * @var \Slim\Slim
         */
        private $app;
    
        /**
         * @var \Illuminate\Database\Capsule\Manager
         */
        private $db;
    
        public function __construct(
            \Slim\Slim $app,
            \Illuminate\Database\Capsule\Manager $db
        ) {
            $this->app = $app;
            $this->db = $db;
        }
    
        /**
         * Show the home page
         */
        public function indexAction()
        {
            $this->app->render('home.twig');
        }
    }

For further information on how to use the database object see https://github.com/laravel/docs/blob/4.1/database.md

###HTML5 Boilerplate
@TODO...
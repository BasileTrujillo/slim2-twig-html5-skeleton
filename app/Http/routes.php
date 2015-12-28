<?php

/**
 * Application route setup
 *
 * $app, $container and $router global variables available in here by default
 *
 * @author Basile Trujillo
 * @link https://bitbucket.org/L0gIn/slim-twig-laravel-html5-boilerplate
 */

// Defining the home page route
$router->get('/', 'HomeController::index')->name('home');

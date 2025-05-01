<?php

namespace Config;

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();

// Rutas de documentación Swagger
$routes->get('/', 'Swagger::index');
$routes->get('docs', 'Swagger::index');
$routes->get('swagger.json', 'Swagger::json');

// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
// $routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

$routes->group('api', ['namespace' => 'App\Controllers'], function ($routes) {
    // Rutas públicas
    $routes->post('auth/login', 'Auth::login');
    $routes->post('auth/register', 'Auth::register');

    // Rutas protegidas (requieren JWT)
    $routes->group('', ['filter' => 'jwt'], function ($routes) {
        // Aquí irán las rutas protegidas que requieren autenticación
    });
});

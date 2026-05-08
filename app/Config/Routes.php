<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/formulaire', 'Home::formulaire');

// Authentication routes
$routes->get('/auth/login', 'Auth::login');
$routes->post('/auth/login', 'Auth::handleLogin');
$routes->get('/auth/register', 'Auth::register');
$routes->post('/auth/register', 'Auth::handleRegister');
$routes->get('/auth/logout', 'Auth::logout');

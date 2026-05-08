<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/formulaire', 'Auth::registerStep1');
$routes->get('/formulaire-step2', 'Auth::registerStep2');

// Authentication routes
$routes->get('/auth/login', 'Auth::login');
$routes->post('/auth/login', 'Auth::handleLogin');
$routes->get('/auth/register', 'Auth::registerStep1');
$routes->post('/auth/register-step1', 'Auth::handleRegisterStep1');
$routes->post('/auth/register-step2', 'Auth::handleRegisterStep2');
$routes->get('/auth/logout', 'Auth::logout');

// Profile routes
$routes->get('/profil', 'Auth::profile');
$routes->post('/profil', 'Auth::updateProfile');

// Objectifs & suggestions
$routes->get('/objectifs', 'Auth::objectifs');
$routes->get('/objectifs/data', 'Auth::objectifsData');

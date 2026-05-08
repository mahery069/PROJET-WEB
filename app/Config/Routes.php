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
// Wallet routes
$routes->get('wallet/balance', 'Wallet::balance');
$routes->post('wallet/redeem', 'Wallet::redeem');

// Auth routes
$routes->get('auth/login', 'Auth::login');
$routes->post('auth/handle-login', 'Auth::handleLogin');
$routes->get('auth/logout', 'Auth::logout');

// Admin routes
$routes->get('/admin/login', 'AdminAuth::login');
$routes->post('/admin/login', 'AdminAuth::handleLogin');
$routes->get('/admin/logout', 'AdminAuth::logout');

// Admin protected routes
$routes->group('admin', ['filter' => 'AdminAuth'], function($routes) {
    $routes->get('dashboard', 'Admin::dashboard');
    
    // Regimes CRUD
    $routes->get('regimes', 'Admin::regimesIndex');
    $routes->get('regimes/create', 'Admin::regimesCreate');
    $routes->post('regimes', 'Admin::regimesStore');
    $routes->get('regimes/edit/(:num)', 'Admin::regimesEdit/$1');
    $routes->post('regimes/update/(:num)', 'Admin::regimesUpdate/$1');
    $routes->get('regimes/delete/(:num)', 'Admin::regimesDelete/$1');

    // Activites CRUD
    $routes->get('activites', 'Admin::activitesIndex');
    $routes->get('activites/create', 'Admin::activitesCreate');
    $routes->post('activites', 'Admin::activitesStore');
    $routes->get('activites/edit/(:num)', 'Admin::activitesEdit/$1');
    $routes->post('activites/update/(:num)', 'Admin::activitesUpdate/$1');
    $routes->get('activites/delete/(:num)', 'Admin::activitesDelete/$1');

    // Codes Wallet
    $routes->get('codes', 'Admin::codesIndex');
    $routes->get('codes/create', 'Admin::codesCreate');
    $routes->post('codes', 'Admin::codesStore');
    $routes->get('codes/validate/(:num)', 'Admin::codesValidate/$1');
});

// CRUD API for regimes, activites and codes_wallet
$routes->group('api', function($routes) {
    $routes->get('regimes', 'Regimes::index');
    $routes->get('regimes/(:num)', 'Regimes::show/$1');
    $routes->post('regimes', 'Regimes::create');
    $routes->put('regimes/(:num)', 'Regimes::update/$1');
    $routes->patch('regimes/(:num)', 'Regimes::update/$1');
    $routes->delete('regimes/(:num)', 'Regimes::delete/$1');

    $routes->get('activites', 'Activites::index');
    $routes->get('activites/(:num)', 'Activites::show/$1');
    $routes->post('activites', 'Activites::create');
    $routes->put('activites/(:num)', 'Activites::update/$1');
    $routes->patch('activites/(:num)', 'Activites::update/$1');
    $routes->delete('activites/(:num)', 'Activites::delete/$1');

    $routes->get('codes', 'CodesWallet::index');
    $routes->get('codes/(:num)', 'CodesWallet::show/$1');
    $routes->post('codes', 'CodesWallet::create');
    $routes->put('codes/(:num)', 'CodesWallet::update/$1');
    $routes->patch('codes/(:num)', 'CodesWallet::update/$1');
    $routes->delete('codes/(:num)', 'CodesWallet::delete/$1');
});

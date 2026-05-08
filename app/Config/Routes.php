<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

// Wallet UI pages
$routes->get('wallet/gold', 'Wallet::goldPage');

// Wallet routes
$routes->get('wallet/balance', 'Wallet::balance');
$routes->post('wallet/redeem', 'Wallet::redeem');
$routes->post('wallet/purchase', 'Wallet::purchase');
$routes->get('wallet/subscriptions', 'Wallet::subscriptions');
$routes->post('wallet/gold/purchase', 'Wallet::purchaseGold');
$routes->get('wallet/gold/status', 'Wallet::goldStatus');

// Auth routes
$routes->get('auth/login', 'Auth::login');
$routes->post('auth/handle-login', 'Auth::handleLogin');
$routes->get('auth/logout', 'Auth::logout');

// Admin routes (protected by AdminAuth filter)
$routes->group('admin', ['filter' => 'AdminAuth'], function($routes) {
    $routes->get('dashboard', 'Admin::dashboard');
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

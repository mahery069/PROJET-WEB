<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
// Wallet routes
$routes->get('wallet/balance', 'Wallet::balance');
$routes->post('wallet/redeem', 'Wallet::redeem');

// Auth routes
$routes->get('auth/login', 'Auth::login');
$routes->post('auth/handle-login', 'Auth::handleLogin');
$routes->get('auth/logout', 'Auth::logout');

// Admin routes (protected by AdminAuth filter)
$routes->group('admin', ['filter' => 'AdminAuth'], function($routes) {
    $routes->get('dashboard', 'Admin::dashboard');
});

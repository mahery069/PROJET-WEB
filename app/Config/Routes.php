<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
// Wallet routes
$routes->get('wallet/balance', 'Wallet::balance');
$routes->post('wallet/redeem', 'Wallet::redeem');

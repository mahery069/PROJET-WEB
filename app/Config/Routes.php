<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');


$routes->get('/formulaire', 'Auth::registerStep1');
$routes->get('/formulaire-step2', 'Auth::registerStep2');
$routes->get('/auth/login', 'Auth::login');
$routes->post('/auth/login', 'Auth::handleLogin');
$routes->get('/auth/register', 'Auth::registerStep1');
$routes->post('/auth/register-step1', 'Auth::handleRegisterStep1');
$routes->post('/auth/register-step2', 'Auth::handleRegisterStep2');
$routes->get('/auth/forgot-password', 'Auth::forgotPassword');
$routes->get('/auth/logout', 'Auth::logout');

// Protected user routes (require authentication)
$routes->group('', ['filter' => 'UserAuth'], function($routes) {
    $routes->get('/profil', 'Auth::profile');
    $routes->get('/mon-profil', 'Auth::profile');
    $routes->post('/profil', 'Auth::updateProfile');
    $routes->get('/export-pdf', 'Auth::exportPDF');
    $routes->get('/objectifs', 'Auth::objectifs');
    $routes->get('/objectifs/data', 'Auth::objectifsData');

    // User Dashboard routes
    $routes->get('/dashboard', 'Dashboard::index');
    $routes->get('/regimes', 'Dashboard::regimes');
    $routes->get('/porte-monnaie', 'Dashboard::wallet');

    $routes->get('wallet/gold', 'Wallet::goldPage');
    $routes->get('wallet/balance', 'Wallet::balance');
    $routes->post('wallet/redeem', 'Wallet::redeem');
    $routes->post('wallet/purchase', 'Wallet::purchase');
    $routes->get('wallet/subscriptions', 'Wallet::subscriptions');
    $routes->post('wallet/gold/purchase', 'Wallet::purchaseGold');
    $routes->get('wallet/gold/status', 'Wallet::goldStatus');
});

$routes->get('/admin/login', 'AdminAuth::login');
$routes->post('/admin/login', 'AdminAuth::handleLogin');
$routes->get('/admin/logout', 'AdminAuth::logout');

$routes->group('admin', ['filter' => 'AdminAuth'], function($routes) {
    $routes->get('dashboard', 'Admin::dashboard');
    $routes->get('regimes', 'Admin::regimesIndex');
    $routes->get('regimes/create', 'Admin::regimesCreate');
    $routes->post('regimes', 'Admin::regimesStore');
    $routes->get('regimes/edit/(:num)', 'Admin::regimesEdit/$1');
    $routes->post('regimes/update/(:num)', 'Admin::regimesUpdate/$1');
    $routes->get('regimes/delete/(:num)', 'Admin::regimesDelete/$1');

    $routes->get('activites', 'Admin::activitesIndex');
    $routes->get('activites/create', 'Admin::activitesCreate');
    $routes->post('activites', 'Admin::activitesStore');
    $routes->get('activites/edit/(:num)', 'Admin::activitesEdit/$1');
    $routes->post('activites/update/(:num)', 'Admin::activitesUpdate/$1');
    $routes->get('activites/delete/(:num)', 'Admin::activitesDelete/$1');

    $routes->get('codes', 'Admin::codesIndex');
    $routes->get('codes/create', 'Admin::codesCreate');
    $routes->post('codes', 'Admin::codesStore');
    $routes->get('codes/delete/(:num)', 'Admin::codesDelete/$1');
    $routes->get('codes/validate/(:num)', 'Admin::codesValidate/$1');

    $routes->get('parametres', 'Admin::parametresIndex');
    $routes->get('parametres/create', 'Admin::parametresCreate');
    $routes->post('parametres', 'Admin::parametresStore');
    $routes->get('parametres/edit/(:num)', 'Admin::parametresEdit/$1');
    $routes->post('parametres/update/(:num)', 'Admin::parametresUpdate/$1');
    $routes->get('parametres/delete/(:num)', 'Admin::parametresDelete/$1');
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

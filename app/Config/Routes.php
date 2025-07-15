<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

//Auth
$routes->get('/registration', 'Auth::registrationform');
$routes->post('/registration', 'Auth::registration');

$routes->get('/login', 'Auth::loginForm');
$routes->post('/login', 'Auth::login');

// Akun
$routes->get('/profile', 'User::profile');
$routes->get('/profile/edit', 'User::edit');
$routes->put('/profile/update', 'User::update');
$routes->post('/profile/image', 'User::uploadImage');

$routes->post('/logout', 'Auth::logout');

//Home
$routes->get('/', 'Home::index');

//Topup
$routes->get('/topup', 'Topup::index');
$routes->post('/topup/submit', 'Topup::submit');

//Payment
$routes->get('payment/(:segment)', 'Payment::index/$1');
$routes->post('payment/transaction', 'Payment::transaction');

//Transaction
$routes->get('transaction', 'Transaction::index');
$routes->get('transaction/loadMore', 'Transaction::loadMore');

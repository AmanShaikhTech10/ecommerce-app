<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Catch-all for preflight requests
$routes->options('(:any)', '', ['filter' => 'cors']);

$routes->get('/', 'Home::index');

$routes->group('api', function ($routes) {

    //Protected Auth Routes
    $routes->group('payment', ['filter' => 'auth'], function ($routes) {
        $routes->post('checkout', 'PaymentController::checkout');
        $routes->post('mock-result', 'PaymentController::mockResult');
    });


    // Public Auth Routes
    $routes->post('auth/signup', 'Auth::signup');
    $routes->post('auth/login', 'Auth::login');
    $routes->post('auth/logout', 'Auth::logout');

    // Protected Auth Routes
    $routes->get('auth/me', 'Auth::me', ['filter' => 'auth']);
    // $routes->post('api/orders/create', 'OrderController::create');
    // $routes->post('api/orders/verify-payment', 'OrderController::verifyPayment');


    // Public Product Routes
    $routes->get('products', 'ProductController::index');
    $routes->get('products/(:num)', 'ProductController::show/$1');

    // Protected Cart Routes
    $routes->group('cart', ['filter' => 'auth'], function ($routes) {
        $routes->get('/', 'CartController::index');
        $routes->post('/', 'CartController::create');
        $routes->delete('clear', 'CartController::clear');
        $routes->put('(:num)', 'CartController::update/$1');
        $routes->delete('(:num)', 'CartController::delete/$1');
    });
    $routes->post(
        'payment/checkout',
        'PaymentController::checkout'
    );

    // Protected Order Routes
    $routes->group('orders', ['filter' => 'auth'], function ($routes) {
        $routes->get('/', 'OrderController::index');
        $routes->post('create', 'OrderController::create');
        $routes->post('verify-payment', 'OrderController::verifyPayment');
        $routes->get('invoice/(:num)', 'OrderController::downloadInvoice/$1');
    });

    // Protected Admin Routes
    $routes->group('admin', ['filter' => 'admin'], function ($routes) {
        $routes->get('stats', 'AdminController::stats');
        $routes->get('users', 'AdminController::users');
        $routes->get('orders', 'AdminController::orders');
    });
});
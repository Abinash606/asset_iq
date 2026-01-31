<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Api\AdminAuth::index');
$routes->group('api', function ($routes) {
    $routes->post('admin/login', 'Api\AdminAuth::login');
    $routes->post('admin/refresh', 'Api\AdminAuth::refresh');
    $routes->post('admin/logout', 'Api\AdminAuth::logout');

    $routes->group('admin', ['filter' => 'jwt'], function ($routes) {
        $routes->get('dashboard', 'Api\AdminDashboard::index');
        // Customer Routes
        $routes->get('customers', 'Api\CustomerController::index');          // View
        $routes->get('customers/list', 'Api\CustomerController::list');      // DataTable
        $routes->get('customers/(:num)', 'Api\CustomerController::get/$1');  // Get single
        $routes->post('customers', 'Api\CustomerController::store');         // Create
        $routes->put('customers/(:num)', 'Api\CustomerController::update/$1'); // Update
        $routes->delete('customers/(:num)', 'Api\CustomerController::delete/$1'); // Delete
    });
});
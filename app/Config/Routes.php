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
    });
});
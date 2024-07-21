<?php

use App\Controllers\AdminController;
use App\Controllers\CategoryController;
use App\Controllers\HomeController;
use App\Controllers\ProductController;
use App\Controllers\UserController;
use App\Middleware\AdminMiddleware;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

return function (App $app) {
    // Public routes
    $app->get('/', [HomeController::class, 'index']);

    // Admin routes
    $app->group('/admin-dashboard', function(RouteCollectorProxy $adminDashboard) {
        // Login routes
        $adminDashboard->get('/login', [AdminController::class, 'renderLogin']);
        $adminDashboard->post('/login', [AdminController::class, 'login']);

        // Protected admin routes
        $adminDashboard->group('', function(RouteCollectorProxy $adminProtected) {
            $adminProtected->get('', [AdminController::class, 'index']);
            $adminProtected->post('/logout', [AdminController::class, 'logout']);

            $adminProtected->get('/products', [ProductController::class, 'index']);
            $adminProtected->post('/products', [ProductController::class, 'addProduct']);
            $adminProtected->get('/products/load', [ProductController::class, 'load']);

            $adminProtected->get('/categories', [CategoryController::class, 'index']);
            $adminProtected->post('/categories', [CategoryController::class, 'addCategory']);
            $adminProtected->get('/categories/load', [CategoryController::class, 'load']);

            $adminProtected->get('/users', [UserController::class, 'index']);
            $adminProtected->post('/users', [UserController::class, 'addUser']);
        })->add(AdminMiddleware::class);
    });
};

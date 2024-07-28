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
    $app->get('/', [HomeController::class, 'index']);

    $app->group('/admin-dashboard', function(RouteCollectorProxy $adminDashboard) {
        $adminDashboard->get('/login', [AdminController::class, 'renderLogin']);
        $adminDashboard->post('/login', [AdminController::class, 'login']);

        $adminDashboard->group('', function(RouteCollectorProxy $adminProtected) {
            $adminProtected->get('', [AdminController::class, 'index']);
            $adminProtected->post('/logout', [AdminController::class, 'logout']);
            $adminProtected->get('/admin/load', [AdminController::class, 'load']);
            $adminProtected->delete('/admin/{admin}', [AdminController::class, 'delete']);

            $adminProtected->group('/products', function(RouteCollectorProxy $products) {
                $products->get('', [ProductController::class, 'index']);
                $products->post('', [ProductController::class, 'addProduct']);
                $products->get('/load', [ProductController::class, 'load']);
                $products->delete('/{product}', [ProductController::class, 'delete']);
                $products->post('/update/{product}', [ProductController::class, 'update']);
            });

            $adminProtected->group('/categories', function(RouteCollectorProxy $categories) {
                $categories->get('', [CategoryController::class, 'index']);
                $categories->post('', [CategoryController::class, 'addCategory']);
                $categories->get('/load', [CategoryController::class, 'load']);
                $categories->delete('/{category}', [CategoryController::class, 'delete']);
            });

            $adminProtected->group('/users', function(RouteCollectorProxy $users) {
                $users->get('', [UserController::class, 'index']);
                $users->post('', [UserController::class, 'addUser']);
                $users->get('/load', [UserController::class, 'load']);
                $users->delete('/{user}', [UserController::class, 'delete']);
            });

        })->add(AdminMiddleware::class);
    });
};

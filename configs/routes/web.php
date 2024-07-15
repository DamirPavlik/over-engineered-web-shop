<?php

use App\Controllers\AdminController;
use App\Controllers\HomeController;
use App\Middleware\AdminMiddleware;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

return function (App $app) {
    $app->group('', function(RouteCollectorProxy $group) {
        $group->get('/', [HomeController::class, 'index']);

        $group->group('/admin-dashboard', function(RouteCollectorProxy $adminDashboard) {
            $adminDashboard->get('/login', [AdminController::class, 'renderLogin']);
            $adminDashboard->post('/login/submit', [AdminController::class, 'login']);
            $adminDashboard->get('', [AdminController::class, 'index'])->add(AdminMiddleware::class);
            $adminDashboard->get('/users', [AdminController::class, 'renderUsers'])->add(AdminMiddleware::class);
            $adminDashboard->get('/products', [AdminController::class, 'renderProducts'])->add(AdminMiddleware::class);
        });
    });
};
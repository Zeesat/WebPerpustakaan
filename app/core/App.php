<?php

declare(strict_types=1);

namespace App\Core;

use App\Controllers\Admin\BookManagementController;
use App\Controllers\Admin\CategoryManagementController;
use App\Controllers\Admin\LoanVerificationController;
use App\Controllers\Admin\UserManagementController;
use App\Controllers\Admin\AdminDashboardController;
use App\Controllers\AuthController;
use App\Controllers\BookController;
use App\Controllers\DashboardController;
use App\Controllers\HomeController;
use App\Controllers\LoanController;
use App\Middleware\AdminMiddleware;
use App\Middleware\AuthMiddleware;
use App\Middleware\GuestMiddleware;

class App
{
    public function run(): void
    {
        $router = new Router();
        $this->registerRoutes($router);

        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';

        $router->dispatch($method, $uri);
    }

    private function registerRoutes(Router $router): void
    {
        $router->get('/', [HomeController::class, 'index']);
        $router->get('/books', [BookController::class, 'index']);
        $router->get('/books/show', [BookController::class, 'show']);

        $router->get('/login', [AuthController::class, 'login'], [GuestMiddleware::class]);
        $router->post('/login', [AuthController::class, 'authenticate'], [GuestMiddleware::class]);
        $router->get('/register', [AuthController::class, 'register'], [GuestMiddleware::class]);
        $router->post('/register', [AuthController::class, 'store'], [GuestMiddleware::class]);
        $router->get('/forgot-password', [AuthController::class, 'forgotPassword'], [GuestMiddleware::class]);
        $router->get('/reset-password', [AuthController::class, 'resetPassword'], [GuestMiddleware::class]);
        $router->post('/logout', [AuthController::class, 'logout'], [AuthMiddleware::class]);

        $router->get('/dashboard', [DashboardController::class, 'index'], [AuthMiddleware::class]);
        $router->get('/loans/my', [LoanController::class, 'myLoans'], [AuthMiddleware::class]);
        $router->get('/loans/request', [LoanController::class, 'requestForm'], [AuthMiddleware::class]);

        $router->get('/admin', [AdminDashboardController::class, 'index'], [AdminMiddleware::class]);
        $router->get('/admin/books', [BookManagementController::class, 'index'], [AdminMiddleware::class]);
        $router->get('/admin/categories', [CategoryManagementController::class, 'index'], [AdminMiddleware::class]);
        $router->get('/admin/users', [UserManagementController::class, 'index'], [AdminMiddleware::class]);
        $router->get('/admin/loans', [LoanVerificationController::class, 'index'], [AdminMiddleware::class]);
    }
}


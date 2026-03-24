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

        $router->get('/login', [AuthController::class, 'login']);
        $router->get('/register', [AuthController::class, 'register']);

        $router->get('/dashboard', [DashboardController::class, 'index']);
        $router->get('/loans/my', [LoanController::class, 'myLoans']);
        $router->get('/loans/request', [LoanController::class, 'requestForm']);

        $router->get('/admin', [AdminDashboardController::class, 'index']);
        $router->get('/admin/books', [BookManagementController::class, 'index']);
        $router->get('/admin/categories', [CategoryManagementController::class, 'index']);
        $router->get('/admin/users', [UserManagementController::class, 'index']);
        $router->get('/admin/loans', [LoanVerificationController::class, 'index']);
    }
}


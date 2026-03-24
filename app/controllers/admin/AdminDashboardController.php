<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;

class AdminDashboardController extends Controller
{
    public function index(): void
    {
        $this->view('admin/dashboard', ['title' => 'Admin Dashboard']);
    }
}


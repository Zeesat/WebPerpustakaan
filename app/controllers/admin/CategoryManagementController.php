<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;

class CategoryManagementController extends Controller
{
    public function index(): void
    {
        $this->view('admin/categories', ['title' => 'Manage Categories']);
    }
}


<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;

class UserManagementController extends Controller
{
    public function index(): void
    {
        $this->view('admin/users', ['title' => 'Manage Users']);
    }
}


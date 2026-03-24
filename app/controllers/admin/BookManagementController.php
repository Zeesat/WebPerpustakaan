<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;

class BookManagementController extends Controller
{
    public function index(): void
    {
        $this->view('admin/books', ['title' => 'Manage Books']);
    }
}


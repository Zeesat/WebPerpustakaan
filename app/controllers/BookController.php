<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;

class BookController extends Controller
{
    public function index(): void
    {
        $this->view('books/index', ['title' => 'Book List']);
    }

    public function show(): void
    {
        $this->view('books/show', ['title' => 'Book Detail']);
    }
}


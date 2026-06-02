<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Services\BookService;

class BookController extends Controller
{
    public function __construct(private ?BookService $bookService = null)
    {
        $this->bookService ??= new BookService();
    }

    public function index(): void
    {
        $this->view('books/index', $this->bookService->getCatalogPageData(auth_user(), $_GET));
    }

    public function show(): void
    {
        $bookId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 1],
        ]);

        if ($bookId === false || $bookId === null) {
            flash('error', 'The requested book could not be found.');
            $this->redirect('/books');
            return;
        }

        $viewData = $this->bookService->getBookDetailPageData($bookId, auth_user());

        if ($viewData === null) {
            flash('error', 'The requested book could not be found.');
            $this->redirect('/books');
            return;
        }

        $this->view('books/show', $viewData);
    }
}


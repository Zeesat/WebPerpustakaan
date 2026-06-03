<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\Book;
use App\Models\Category;

class BookManagementController extends Controller
{
    private Book $bookModel;
    private Category $categoryModel;

    public function __construct()
    {
        $this->bookModel = new Book();
        $this->categoryModel = new Category();
    }

    public function index(): void
    {
        $books = $this->bookModel->getAll();
        $categories = $this->categoryModel->getAllWithBookCounts();

        $this->view('admin/books', [
            'title' => 'Manage Books',
            'books' => $books,
            'categories' => $categories,
        ]);
    }

    public function createForm(): void
    {
        $categories = $this->categoryModel->getAll();

        $this->view('admin/book-form', [
            'title' => 'Add New Book',
            'mode' => 'create',
            'book' => null,
            'categories' => $categories,
        ]);
    }

    public function store(): void
    {
        $title = trim((string) ($_POST['title'] ?? ''));
        $author = trim((string) ($_POST['author'] ?? ''));
        $categoryId = filter_var($_POST['category_id'] ?? 0, FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 0],
        ]);
        $description = trim((string) ($_POST['description'] ?? ''));
        $stock = filter_var($_POST['stock'] ?? 0, FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 0],
        ]);

        // Validation
        $errors = [];

        if ($title === '') {
            $errors[] = 'Book title is required.';
        }

        if ($author === '') {
            $errors[] = 'Author name is required.';
        }

        if ($categoryId === false || $categoryId === null) {
            $errors[] = 'Please select a valid category.';
        }

        if ($stock === false || $stock === null) {
            $errors[] = 'Stock must be a valid non-negative number.';
        }

        if (! verify_csrf_token($_POST['_token'] ?? null)) {
            $errors[] = 'Invalid security token. Please try again.';
        }

        if ($errors !== []) {
            with_old_input($_POST);
            flash('error', implode(' ', $errors));
            $this->redirect('/admin/books/create');
            return;
        }

        $bookId = $this->bookModel->create(
            $title,
            $author,
            $categoryId,
            $description,
            $stock
        );

        if ($bookId === false) {
            with_old_input($_POST);
            flash('error', 'Failed to save the book. Please try again.');
            $this->redirect('/admin/books/create');
            return;
        }

        flash('status', 'Book "' . htmlspecialchars($title) . '" has been added successfully.');
        $this->redirect('/admin/books');
    }

    public function editForm(): void
    {
        $bookId = filter_var($_GET['id'] ?? 0, FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 1],
        ]);

        if ($bookId === false || $bookId === null) {
            flash('error', 'Invalid book ID.');
            $this->redirect('/admin/books');
            return;
        }

        $book = $this->bookModel->findById($bookId);

        if ($book === null) {
            flash('error', 'Book not found.');
            $this->redirect('/admin/books');
            return;
        }

        $categories = $this->categoryModel->getAll();

        $this->view('admin/book-form', [
            'title' => 'Edit Book: ' . $book['title'],
            'mode' => 'edit',
            'book' => $book,
            'categories' => $categories,
        ]);
    }

    public function update(): void
    {
        $bookId = filter_var($_POST['id'] ?? 0, FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 1],
        ]);

        if ($bookId === false || $bookId === null) {
            flash('error', 'Invalid book ID.');
            $this->redirect('/admin/books');
            return;
        }

        $title = trim((string) ($_POST['title'] ?? ''));
        $author = trim((string) ($_POST['author'] ?? ''));
        $categoryId = filter_var($_POST['category_id'] ?? 0, FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 0],
        ]);
        $description = trim((string) ($_POST['description'] ?? ''));
        $stock = filter_var($_POST['stock'] ?? 0, FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 0],
        ]);

        // Validation
        $errors = [];

        if ($title === '') {
            $errors[] = 'Book title is required.';
        }

        if ($author === '') {
            $errors[] = 'Author name is required.';
        }

        if ($categoryId === false || $categoryId === null) {
            $errors[] = 'Please select a valid category.';
        }

        if ($stock === false || $stock === null) {
            $errors[] = 'Stock must be a valid non-negative number.';
        }

        if (! verify_csrf_token($_POST['_token'] ?? null)) {
            $errors[] = 'Invalid security token. Please try again.';
        }

        if ($errors !== []) {
            with_old_input($_POST);
            flash('error', implode(' ', $errors));
            $this->redirect('/admin/books/edit?id=' . $bookId);
            return;
        }

        $updated = $this->bookModel->update(
            $bookId,
            $title,
            $author,
            $categoryId,
            $description,
            $stock
        );

        if (! $updated) {
            with_old_input($_POST);
            flash('error', 'Failed to update the book. Please try again.');
            $this->redirect('/admin/books/edit?id=' . $bookId);
            return;
        }

        flash('status', 'Book "' . htmlspecialchars($title) . '" has been updated successfully.');
        $this->redirect('/admin/books');
    }

    public function destroy(): void
    {
        $bookId = filter_var($_POST['id'] ?? 0, FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 1],
        ]);

        if ($bookId === false || $bookId === null) {
            flash('error', 'Invalid book ID.');
            $this->redirect('/admin/books');
            return;
        }

        if (! verify_csrf_token($_POST['_token'] ?? null)) {
            flash('error', 'Invalid security token. Please try again.');
            $this->redirect('/admin/books');
            return;
        }

        $book = $this->bookModel->findById($bookId);

        if ($book === null) {
            flash('error', 'Book not found.');
            $this->redirect('/admin/books');
            return;
        }

        $deleted = $this->bookModel->delete($bookId);

        if (! $deleted) {
            flash('error', 'Failed to delete the book. It may be referenced by existing loans.');
            $this->redirect('/admin/books');
            return;
        }

        flash('status', 'Book "' . htmlspecialchars($book['title']) . '" has been deleted.');
        $this->redirect('/admin/books');
    }
}


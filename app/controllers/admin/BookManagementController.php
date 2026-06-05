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
            with_old_input(array_merge($_POST, ['_form_context' => 'book:create']));
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
            with_old_input(array_merge($_POST, ['_form_context' => 'book:create']));
            flash('error', 'Failed to save the book. Please try again.');
            $this->redirect('/admin/books/create');
            return;
        }

        clear_old_input();
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
            with_old_input(array_merge($_POST, ['_form_context' => 'book:edit:' . $bookId]));
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
            with_old_input(array_merge($_POST, ['_form_context' => 'book:edit:' . $bookId]));
            flash('error', 'Failed to update the book. Please try again.');
            $this->redirect('/admin/books/edit?id=' . $bookId);
            return;
        }

        clear_old_input();
        flash('status', 'Book "' . htmlspecialchars($title) . '" has been updated successfully.');
        $this->redirect('/admin/books');
    }

        public function updateCover(): void
    {
        $bookId = filter_var($_POST['id'] ?? 0, FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 1],
        ]);

        if ($bookId === false || $bookId === null) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid book ID.']);
            return;
        }

        if (! verify_csrf_token($_POST['_token'] ?? null)) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Invalid security token.']);
            return;
        }

        $book = $this->bookModel->findById($bookId);

        if ($book === null) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Book not found.']);
            return;
        }

        $file = $_FILES['cover'] ?? null;

        if (! is_array($file)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'No cover file uploaded.']);
            return;
        }

        $cover = save_cover_file($file, $bookId);

        if ($cover === null) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Failed to process cover image. Ensure it is a valid image (JPG, PNG, WebP) under 5MB.']);
            return;
        }

        // Delete old cover before updating
        $oldCover = $this->bookModel->getCover($bookId);
        delete_cover_file($oldCover);

        $updated = $this->bookModel->updateCover($bookId, $cover);

        if (! $updated) {
            // Rollback: remove the uploaded file
            delete_cover_file($cover);
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Failed to save cover to database.']);
            return;
        }

        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'message' => 'Cover updated successfully.',
            'cover_url' => cover_url($cover),
        ]);
    }

    public function deleteCover(): void
    {
        $bookId = filter_var($_POST['id'] ?? 0, FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 1],
        ]);

        if ($bookId === false || $bookId === null) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid book ID.']);
            return;
        }

        if (! verify_csrf_token($_POST['_token'] ?? null)) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Invalid security token.']);
            return;
        }

        $book = $this->bookModel->findById($bookId);

        if ($book === null) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Book not found.']);
            return;
        }

        $oldCover = $this->bookModel->getCover($bookId);
        delete_cover_file($oldCover);

        $this->bookModel->updateCover($bookId, null);

        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'message' => 'Cover removed successfully.']);
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

        // Delete cover file before deleting the book
        delete_cover_file($book['cover'] ?? null);

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


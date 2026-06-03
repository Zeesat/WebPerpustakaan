<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\Book;
use App\Models\Category;

class CategoryManagementController extends Controller
{
    private Category $categoryModel;
    private Book $bookModel;

    public function __construct()
    {
        $this->categoryModel = new Category();
        $this->bookModel = new Book();
    }

    public function index(): void
    {
        $categories = $this->categoryModel->getAllWithBookCounts();

        $this->view('admin/categories', [
            'title' => 'Manage Categories',
            'categories' => $categories,
        ]);
    }

    public function store(): void
    {
        $name = trim((string) ($_POST['name'] ?? ''));

        $errors = [];

        if ($name === '') {
            $errors[] = 'Category name is required.';
        } elseif (mb_strlen($name) > 100) {
            $errors[] = 'Category name must not exceed 100 characters.';
        } elseif ($this->categoryModel->nameExists($name)) {
            $errors[] = 'A category with this name already exists.';
        }

        if (! verify_csrf_token($_POST['_token'] ?? null)) {
            $errors[] = 'Invalid security token. Please try again.';
        }

        if ($errors !== []) {
            with_old_input($_POST);
            flash('error', implode(' ', $errors));
            $this->redirect('/admin/categories');
            return;
        }

        $categoryId = $this->categoryModel->create($name);

        if ($categoryId === false) {
            flash('error', 'Failed to create category. Please try again.');
            $this->redirect('/admin/categories');
            return;
        }

        flash('status', 'Category "' . htmlspecialchars($name) . '" has been created.');
        $this->redirect('/admin/categories');
    }

    public function update(): void
    {
        $categoryId = filter_var($_POST['id'] ?? 0, FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 1],
        ]);

        if ($categoryId === false || $categoryId === null) {
            flash('error', 'Invalid category ID.');
            $this->redirect('/admin/categories');
            return;
        }

        $name = trim((string) ($_POST['name'] ?? ''));

        $errors = [];

        if ($name === '') {
            $errors[] = 'Category name is required.';
        } elseif (mb_strlen($name) > 100) {
            $errors[] = 'Category name must not exceed 100 characters.';
        } elseif ($this->categoryModel->nameExists($name, $categoryId)) {
            $errors[] = 'A category with this name already exists.';
        }

        if (! verify_csrf_token($_POST['_token'] ?? null)) {
            $errors[] = 'Invalid security token. Please try again.';
        }

        if ($errors !== []) {
            with_old_input($_POST);
            flash('error', implode(' ', $errors));
            $this->redirect('/admin/categories');
            return;
        }

        $updated = $this->categoryModel->update($categoryId, $name);

        if (! $updated) {
            flash('error', 'Failed to update category. Please try again.');
            $this->redirect('/admin/categories');
            return;
        }

        flash('status', 'Category has been renamed to "' . htmlspecialchars($name) . '".');
        $this->redirect('/admin/categories');
    }

    public function destroy(): void
    {
        $categoryId = filter_var($_POST['id'] ?? 0, FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 1],
        ]);

        if ($categoryId === false || $categoryId === null) {
            flash('error', 'Invalid category ID.');
            $this->redirect('/admin/categories');
            return;
        }

        if (! verify_csrf_token($_POST['_token'] ?? null)) {
            flash('error', 'Invalid security token. Please try again.');
            $this->redirect('/admin/categories');
            return;
        }

        $category = $this->categoryModel->findById($categoryId);

        if ($category === null) {
            flash('error', 'Category not found.');
            $this->redirect('/admin/categories');
            return;
        }

        // Check if books reference this category
        $bookCount = $this->bookModel->countByCategory($categoryId);

        if ($bookCount > 0) {
            flash('error', 'Cannot delete "' . htmlspecialchars($category['name']) . '": ' . $bookCount . ' book(s) are using this category. Reassign them first.');
            $this->redirect('/admin/categories');
            return;
        }

        $deleted = $this->categoryModel->delete($categoryId);

        if (! $deleted) {
            flash('error', 'Failed to delete category.');
            $this->redirect('/admin/categories');
            return;
        }

        flash('status', 'Category "' . htmlspecialchars($category['name']) . '" has been deleted.');
        $this->redirect('/admin/categories');
    }
}


<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\Book;
use App\Models\Category;
use App\Models\Loan;
use App\Models\User;

class AdminDashboardController extends Controller
{
    public function index(): void
    {
        $bookModel = new Book();
        $categoryModel = new Category();
        $loanModel = new Loan();
        $userModel = new User();

        $summary = $bookModel->getCatalogSummary();
        $categories = $categoryModel->getAllWithBookCounts();
        $pendingLoans = $loanModel->getPendingLoans();
        $totalUsers = $userModel->countAll();

        $data = [
            'title' => 'Admin Dashboard',
            'totalBooks' => $summary['total_titles'],
            'totalCopies' => $summary['total_copies'],
            'availableTitles' => $summary['available_titles'],
            'outOfStockTitles' => $summary['out_of_stock_titles'],
            'totalCategories' => count($categories),
            'totalUsers' => $totalUsers,
            'pendingLoansCount' => count($pendingLoans),
            'pendingLoans' => $pendingLoans,
            'activeLoansCount' => $loanModel->countActive(),
            'lateLoansCount' => $loanModel->countLate(),
        ];

        $this->view('admin/dashboard', $data);
    }
}

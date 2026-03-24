<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;

class LoanVerificationController extends Controller
{
    public function index(): void
    {
        $this->view('admin/loans', ['title' => 'Loan Verification']);
    }
}


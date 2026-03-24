<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;

class LoanController extends Controller
{
    public function myLoans(): void
    {
        $this->view('loans/my-loans', ['title' => 'My Loans']);
    }

    public function requestForm(): void
    {
        $this->view('loans/request', ['title' => 'Request Loan']);
    }
}


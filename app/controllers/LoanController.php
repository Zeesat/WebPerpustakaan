<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Services\LoanService;

class LoanController extends Controller
{
    public function __construct(private ?LoanService $loanService = null)
    {
        $this->loanService ??= new LoanService();
    }

    public function myLoans(): void
    {
        $this->view(
            'loans/my-loans',
            $this->loanService->getMyLoansPageData(auth_user(), $_GET)
        );
    }

    public function requestForm(): void
    {
        $this->view('loans/request', ['title' => 'Request Loan']);
    }
}


<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Services\BorrowBasketService;
use App\Services\LoanService;

class LoanController extends Controller
{
    public function __construct(
        private ?LoanService $loanService = null,
        private ?BorrowBasketService $borrowBasketService = null
    ) {
        $this->loanService ??= new LoanService();
        $this->borrowBasketService ??= new BorrowBasketService();
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
        $this->view(
            'loans/request',
            $this->borrowBasketService->getPageData(auth_user())
        );
    }

    public function submitRequest(): void
    {
        $rawBody = file_get_contents('php://input');
        $payload = json_decode(is_string($rawBody) ? $rawBody : '', true);

        if (! is_array($payload)) {
            $this->json([
                'success' => false,
                'code' => 'invalid_payload',
                'message' => 'The submitted borrowing list payload is invalid.',
                'issues' => [],
            ], 422);
            return;
        }

        if (! verify_csrf_token((string) ($payload['_token'] ?? ''))) {
            $this->json([
                'success' => false,
                'code' => 'session_expired',
                'message' => 'Your session has expired. Refresh the page and try again.',
                'issues' => [],
            ], 419);
            return;
        }

        $result = $this->borrowBasketService->submitRequest(auth_user(), $payload);
        $statusCode = (int) ($result['statusCode'] ?? ($result['success'] ? 201 : 422));
        unset($result['statusCode']);

        $this->json($result, $statusCode);
    }
}


<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Services\AuthService;

class AuthController extends Controller
{
    public function __construct(private ?AuthService $authService = null)
    {
        $this->authService ??= new AuthService();
    }

    public function login(): void
    {
        $this->view('auth/login', [
            'title' => 'Login',
            'isLandingPage' => true,
            'status' => flash_get('status'),
            'error' => flash_get('error'),
            'errors' => flash_get('errors', []),
        ]);
    }

    public function register(): void
    {
        $this->view('auth/register', [
            'title' => 'Register',
            'isLandingPage' => true,
            'error' => flash_get('error'),
            'errors' => flash_get('errors', []),
        ]);
    }

    public function authenticate(): void
    {
        $input = $this->validatedAuthRequest($_POST);
        $result = $this->authService->attempt($input);

        if (! $result['success']) {
            with_old_input(['email' => $input['email'] ?? '']);
            flash('errors', $result['errors']);
            $this->redirect('/login');
        }

        flash('status', 'Welcome back, ' . (($result['user']['name'] ?? 'reader')) . '.');
        $this->redirect(consume_intended_path());
    }

    public function store(): void
    {
        $input = $this->validatedAuthRequest($_POST);
        $result = $this->authService->register($input);

        if (! $result['success']) {
            with_old_input([
                'name' => $input['name'] ?? '',
                'email' => $input['email'] ?? '',
            ]);
            flash('errors', $result['errors']);
            $this->redirect('/register');
        }

        flash('status', 'Your account is ready. You are now signed in.');
        $this->redirect('/dashboard');
    }

    public function forgotPassword(): void
    {
        $this->view('auth/forgot-password', [
            'title' => 'Forgot Password',
            'isLandingPage' => true,
            'status' => flash_get('status'),
        ]);
    }

    public function resetPassword(): void
    {
        $this->view('auth/reset-password', [
            'title' => 'Reset Password',
            'isLandingPage' => true,
            'status' => flash_get('status'),
        ]);
    }

    public function logout(): void
    {
        $this->ensureValidCsrf($_POST['_token'] ?? null);
        $this->authService->logout();
        flash('status', 'You have been signed out.');
        $this->redirect('/');
    }

    private function validatedAuthRequest(array $input): array
    {
        $this->ensureValidCsrf($input['_token'] ?? null);

        return $input;
    }

    private function ensureValidCsrf(?string $token): void
    {
        if (verify_csrf_token($token)) {
            return;
        }

        flash('error', 'Your session has expired. Please try again.');
        $fallbackPath = parse_url($_SERVER['HTTP_REFERER'] ?? '/login', PHP_URL_PATH);
        redirect(is_string($fallbackPath) && $fallbackPath !== '' ? $fallbackPath : '/login');
    }
}


<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;

class AuthService
{
    public function __construct(private ?User $users = null)
    {
        $this->users ??= new User();
    }

    public function register(array $input): array
    {
        $name = trim((string) ($input['name'] ?? ''));
        $email = strtolower(trim((string) ($input['email'] ?? '')));
        $password = (string) ($input['password'] ?? '');
        $passwordConfirmation = (string) ($input['password_confirmation'] ?? '');

        $errors = [];

        if ($name === '') {
            $errors['name'] = 'Name is required.';
        } elseif (mb_strlen($name) > 100) {
            $errors['name'] = 'Name may not be greater than 100 characters.';
        }

        if ($email === '') {
            $errors['email'] = 'Email is required.';
        } elseif (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Please enter a valid email address.';
        } elseif ($this->users->findByEmail($email) !== null) {
            $errors['email'] = 'That email is already registered.';
        }

        if ($password === '') {
            $errors['password'] = 'Password is required.';
        } elseif (strlen($password) < 8) {
            $errors['password'] = 'Password must be at least 8 characters.';
        }

        if ($passwordConfirmation === '') {
            $errors['password_confirmation'] = 'Please confirm your password.';
        } elseif ($password !== $passwordConfirmation) {
            $errors['password_confirmation'] = 'Passwords do not match.';
        }

        if ($errors !== []) {
            return ['success' => false, 'errors' => $errors];
        }

        $userId = $this->users->create([
            'name' => $name,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'role' => 'user',
        ]);

        if ($userId === null) {
            return [
                'success' => false,
                'errors' => ['general' => 'We could not create your account right now. Please try again.'],
            ];
        }

        $user = $this->users->findById($userId);

        if ($user === null) {
            return [
                'success' => false,
                'errors' => ['general' => 'Your account was created, but we could not start your session. Please sign in.'],
            ];
        }

        $this->loginUser($user);

        return ['success' => true, 'user' => $user];
    }

    public function attempt(array $input): array
    {
        $email = strtolower(trim((string) ($input['email'] ?? '')));
        $password = (string) ($input['password'] ?? '');

        $errors = [];

        if ($email === '') {
            $errors['email'] = 'Email is required.';
        } elseif (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Please enter a valid email address.';
        }

        if ($password === '') {
            $errors['password'] = 'Password is required.';
        }

        if ($errors !== []) {
            return ['success' => false, 'errors' => $errors];
        }

        $user = $this->users->findByEmail($email);

        if ($user === null || ! password_verify($password, (string) ($user['password'] ?? ''))) {
            return [
                'success' => false,
                'errors' => ['general' => 'The provided credentials are invalid.'],
            ];
        }

        unset($user['password']);
        $this->loginUser($user);

        return ['success' => true, 'user' => $user];
    }

    public function logout(): void
    {
        session_forget('auth.user');
        session_forget('url.intended');
        clear_old_input();

        if (session_status() === PHP_SESSION_ACTIVE) {
            session_regenerate_id(true);
        }
    }

    private function loginUser(array $user): void
    {
        unset($user['password']);
        session_put('auth.user', $user);
        clear_old_input();
        unset($_SESSION['_flash']);
        session_regenerate_id(true);
    }
}


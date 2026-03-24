<?php

declare(strict_types=1);

return [
    'public' => [
        '/' => 'HomeController@index',
        '/books' => 'BookController@index',
        '/books/show' => 'BookController@show',
    ],
    'auth' => [
        '/login' => 'AuthController@login',
        '/register' => 'AuthController@register',
    ],
    'user' => [
        '/dashboard' => 'DashboardController@index',
        '/loans/my' => 'LoanController@myLoans',
        '/loans/request' => 'LoanController@requestForm',
    ],
    'admin' => [
        '/admin' => 'AdminDashboardController@index',
        '/admin/books' => 'BookManagementController@index',
        '/admin/categories' => 'CategoryManagementController@index',
        '/admin/users' => 'UserManagementController@index',
        '/admin/loans' => 'LoanVerificationController@index',
    ],
];


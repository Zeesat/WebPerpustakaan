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
        'POST /login' => 'AuthController@authenticate',
        '/register' => 'AuthController@register',
        'POST /register' => 'AuthController@store',
        '/forgot-password' => 'AuthController@forgotPassword',
        '/reset-password' => 'AuthController@resetPassword',
        'POST /logout' => 'AuthController@logout',
    ],
    'user' => [
        '/dashboard' => 'DashboardController@index',
        '/loans' => 'LoanController@myLoans',
        '/loans/my' => 'LoanController@myLoans',
        '/loans/request' => 'LoanController@requestForm',
    ],
        'admin' => [
        '/admin' => 'AdminDashboardController@index',
        '/admin/books' => 'BookManagementController@index',
        '/admin/books/create' => 'BookManagementController@createForm',
        'POST /admin/books/store' => 'BookManagementController@store',
        '/admin/books/edit' => 'BookManagementController@editForm',
        'POST /admin/books/update' => 'BookManagementController@update',
        'POST /admin/books/delete' => 'BookManagementController@destroy',
        '/admin/categories' => 'CategoryManagementController@index',
        'POST /admin/categories/store' => 'CategoryManagementController@store',
        'POST /admin/categories/update' => 'CategoryManagementController@update',
        'POST /admin/categories/delete' => 'CategoryManagementController@destroy',
        '/admin/users' => 'UserManagementController@index',
        '/admin/loans' => 'LoanVerificationController@index',
    ],
];


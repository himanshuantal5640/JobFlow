<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;


Route::get('/login', [AuthController::class, 'login'])
    ->name('login');

Route::post('/login', [AuthController::class, 'authenticate'])
    ->name('login.post');

Route::get('/register', [AuthController::class, 'register'])
    ->name('register');

Route::post('/register', [AuthController::class, 'store'])
    ->name('register.post');
Route::get('/', function () {

$jobs = [
(object)[
'title'=>'Software Engineer',
'company'=>'Google',
'skills'=>json_encode(['React','Node']),
'match'=>87
],
(object)[
'title'=>'Designer',
'company'=>'Stripe',
'skills'=>json_encode(['Figma']),
'match'=>73
]
];

return view('pages.home', compact('jobs'));
});
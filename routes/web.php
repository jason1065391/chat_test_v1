<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


use App\Http\Controllers\MessageController;
use App\Http\Controllers\UserController;

// 显示聊天页面
Route::get('/chat', function () {
    return view('chat'); // Make sure the view exists in resources/views
});

// 获取消息
Route::get('/messages', [MessageController::class, 'fetchMessages']);

// 发送消息
Route::post('/send-message', [MessageController::class, 'sendMessage']);

// 获取用户列表
Route::get('/users', [UserController::class, 'index']);
<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

use App\Http\Controllers\MessageController;

Route::get('/chat', function () {
    return view('chat');
});
// http://localhost/chat_test_v1/public/chat


Route::get('/messages', [MessageController::class, 'fetchMessages']);
// http://localhost/chat_test_v1/public/messages

Route::post('/send-message', [MessageController::class, 'sendMessage']);
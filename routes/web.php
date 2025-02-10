<?php

use App\Http\Controllers\CommentPageController;

Route::get('/comments', [CommentPageController::class, 'index']);


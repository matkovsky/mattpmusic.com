<?php

use App\Http\Controllers\EpisodeController;
use App\Http\Controllers\RedirectController;
use Illuminate\Support\Facades\Route;

Route::get('/', [EpisodeController::class, 'index'])->name('episodes.index');
Route::get('/episode/{episode:slug}', [EpisodeController::class, 'show'])->name('episodes.show');

// Old Tumblr URL redirects
Route::get('/post/{id}/{slug?}', [RedirectController::class, 'tumblrRedirect']);

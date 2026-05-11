<?php

use App\Http\Controllers\EpisodeController;
use App\Http\Controllers\RedirectController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;

Route::get('/', [EpisodeController::class, 'index'])->name('episodes.index');
Route::get('/episode/{episode:slug}', [EpisodeController::class, 'show'])->name('episodes.show');
Route::get('/search', [SearchController::class, 'index'])->name('search');

// Old Tumblr URL redirects
Route::get('/post/{id}/{slug?}', [RedirectController::class, 'tumblrRedirect']);

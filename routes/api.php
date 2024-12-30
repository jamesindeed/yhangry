<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SetMenuController;

Route::get('set-menus', [SetMenuController::class, 'index']);

<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\SetMenuController;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(Request $request, SetMenuController $setMenuController)
    {
        return Inertia::render('Dashboard', [
            'setMenuData' => $setMenuController->index($request),
            'cuisineSlug' => $request->input('cuisineSlug'),
        ]);
    }
}

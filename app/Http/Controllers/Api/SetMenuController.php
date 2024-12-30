<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SetMenu;
use App\Models\Cuisine;
use Illuminate\Http\Request;

/**
 * Bonus - Security 
 * 1. Input validation
 * 2. Rate limiting
 * 3. Error handling / Logging
 *
 * Bonus - Efficiency
 * 1. Use eager loading to reduce database queries
 * 2. Pagination
 * 3. Caching
 * 4. Raw SQL could perform better than Eloquent
 */

class SetMenuController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'cuisineSlug' => 'nullable|string|max:255',
        ]);

        $cuisineSlug = $request->cuisineSlug;

        $menus = SetMenu::with(['cuisines' => function ($query) {
            $query->select('id', 'name');
        }])
            ->when($cuisineSlug, function ($query, $slug) {
                $query->whereHas(
                    'cuisines',
                    fn($q) => $q->where('name', $slug)
                );
            })
            ->where('status', 1)
            ->orderByDesc('number_of_orders')
            ->paginate(20)
            ->through(fn($menu) => [
                'name' => $menu->name,
                'description' => $menu->description,
                'price' => $menu->price_per_person,
                'minSpend' => $menu->min_spend,
                'thumbnail' => $menu->thumbnail,
                'cuisines' => $menu->cuisines->map(fn($cuisine) => [
                    'name' => $cuisine->name,
                    'slug' => $cuisine->name
                ])
            ]);

        $cuisines = Cuisine::withCount(['setMenus' => fn($q) => $q->where('status', 1)])
            ->withSum(['setMenus' => fn($q) => $q->where('status', 1)], 'number_of_orders')
            ->whereHas('setMenus', fn($q) => $q->where('status', 1))
            ->orderByDesc('set_menus_sum_number_of_orders')
            ->get()
            ->map(fn($cuisine) => [
                'name' => $cuisine->name,
                'slug' => $cuisine->name,
                'number_of_orders' => (int)$cuisine->set_menus_sum_number_of_orders,
                'set_menus_count' => $cuisine->set_menus_count
            ]);

        return [
            'filters' => ['cuisines' => $cuisines],
            'setMenus' => $menus
        ];
    }
}

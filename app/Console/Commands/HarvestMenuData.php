<?php

namespace App\Console\Commands;

use App\Models\SetMenu;
use App\Models\Cuisine;
use App\Models\MenuGroup;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

/**
 * Part 1 - Command to harvest the data from the API and store it in the database
 *
 * Notes: Added sleep to respect the rate limiting constraint specifid (fails otherwise)
 *
 * Possible improvements:
 * 1. Add retry for fails
 * 2. Add proper error logging
 * 3. Cache response?
 */

class HarvestMenuData extends Command
{
    protected $signature = 'menus:harvest';

    public function handle()
    {
        $currentPage = 1;
        $lastPage = 1;

        do {
            $this->line("Fetching page {$currentPage}...");
            $response = Http::get("https://staging.yhangry.com/booking/test/set-menus", [
                'page' => $currentPage
            ]);

            $data = $response->json();
            $lastPage = $data['meta']['last_page'] ?? 1;

            foreach ($data['data'] as $menuData) {
                $menu = SetMenu::updateOrCreate(
                    ['name' => $menuData['name']],
                    [
                        'description' => $menuData['description'] ?? null,
                        'display_text' => $menuData['display_text'] ?? 1,
                        'image' => $menuData['image'] ?? null,
                        'thumbnail' => $menuData['thumbnail'] ?? null,
                        'is_vegan' => $menuData['is_vegan'] ?? false,
                        'is_vegetarian' => $menuData['is_vegetarian'] ?? false,
                        'is_halal' => $menuData['is_halal'] ?? false,
                        'is_kosher' => $menuData['is_kosher'] ?? false,
                        'is_seated' => $menuData['is_seated'] ?? false,
                        'is_standing' => $menuData['is_standing'] ?? false,
                        'is_canape' => $menuData['is_canape'] ?? false,
                        'is_mixed_dietary' => $menuData['is_mixed_dietary'] ?? false,
                        'is_meal_prep' => $menuData['is_meal_prep'] ?? false,
                        'status' => $menuData['status'] ?? 1,
                        'price_per_person' => $menuData['price_per_person'] ?? 0,
                        'min_spend' => $menuData['min_spend'] ?? 0,
                        'number_of_orders' => $menuData['number_of_orders'] ?? 0,
                        'price_includes' => $menuData['price_includes'],
                        'highlight' => $menuData['highlight'],
                        'available' => $menuData['available'] ?? true,
                    ]
                );

                if (isset($menuData['cuisines'])) {
                    $cuisineIds = [];
                    foreach ($menuData['cuisines'] as $cuisine) {
                        $cuisineModel = Cuisine::firstOrCreate(['name' => $cuisine['name']]);
                        $cuisineIds[] = $cuisineModel->id;
                    }
                    $menu->cuisines()->sync($cuisineIds);
                }

                if (isset($menuData['groups']['groups'])) {
                    MenuGroup::where('set_menu_id', $menu->id)->delete();
                    foreach ($menuData['groups']['groups'] as $name => $value) {
                        if ($name !== 'ungrouped' && $value) {
                            MenuGroup::create([
                                'set_menu_id' => $menu->id,
                                'name' => $name,
                                'dishes_count' => $menuData['groups']['dishes_count'] ?? 0,
                                'selectable_dishes_count' => $menuData['groups']['selectable_dishes_count'] ?? 0
                            ]);
                        }
                    }
                }
            }

            // Sleep for not error when getting rate limited
            if ($currentPage < $lastPage) {
                sleep(1);
            }

            $currentPage++;
        } while ($currentPage <= $lastPage);

        $this->info('Completed Successfully');
    }
}

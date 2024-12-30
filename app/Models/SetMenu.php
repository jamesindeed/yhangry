<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class SetMenu extends Model
{
    protected $fillable = [
        'name',
        'description',
        'display_text',
        'image',
        'thumbnail',
        'is_vegan',
        'is_vegetarian',
        'is_halal',
        'is_kosher',
        'is_seated',
        'is_standing',
        'is_canape',
        'is_mixed_dietary',
        'is_meal_prep',
        'status',
        'price_per_person',
        'min_spend',
        'number_of_orders',
        'price_includes',
        'highlight',
        'available'
    ];

    protected $casts = [
        'display_text' => 'boolean',
        'is_vegan' => 'boolean',
        'is_vegetarian' => 'boolean',
        'is_halal' => 'boolean',
        'is_kosher' => 'boolean',
        'is_seated' => 'boolean',
        'is_standing' => 'boolean',
        'is_canape' => 'boolean',
        'is_mixed_dietary' => 'boolean',
        'is_meal_prep' => 'boolean',
        'available' => 'boolean',
        'price_includes' => 'array'
    ];

    public function cuisines()
    {
        return $this->belongsToMany(Cuisine::class);
    }

    public function groups()
    {
        return $this->hasMany(MenuGroup::class);
    }
}

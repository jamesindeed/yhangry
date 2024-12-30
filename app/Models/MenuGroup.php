<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuGroup extends Model
{
    protected $fillable = [
        'set_menu_id',
        'name',
        'dishes_count',
        'selectable_dishes_count'
    ];

    public function setMenu()
    {
        return $this->belongsTo(SetMenu::class);
    }
}

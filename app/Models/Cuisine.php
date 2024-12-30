<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cuisine extends Model
{
    protected $fillable = ['name'];

    public function setMenus()
    {
        return $this->belongsToMany(SetMenu::class);
    }
}

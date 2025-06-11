<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
// Relasi: Satu Restoran dimiliki oleh satu User
public function user()
{
    return $this->belongsTo(User::class);
}

// Relasi: Satu Restoran memiliki banyak Menu
public function menus()
{
    return $this->hasMany(Menu::class);
}

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $table = 'items';
    protected $primaryKey = 'id';
    protected $fillable = ['name', 'price', 'cost', 'description'];

    public function entries()
    {
        return $this->hasMany(Entries::class);
    }
}

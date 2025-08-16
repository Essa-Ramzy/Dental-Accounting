<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Item extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'items';
    protected $primaryKey = 'id';
    protected $fillable = ['name', 'price', 'cost', 'description'];

    protected static function booted()
    {
        static::deleting(fn($item) => $item->entries()->delete());
    }

    public function entries()
    {
        return $this->hasMany(Entry::class);
    }
}

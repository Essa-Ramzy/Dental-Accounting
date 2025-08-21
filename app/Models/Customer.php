<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'customers';
    protected $primaryKey = 'id';
    protected $fillable = ['name'];

    protected static function booted()
    {
        static::deleting(function ($customer) {
            if ($customer->isForceDeleting()) {
                if ($customer->entries) {
                    $customer->entries()->withTrashed()->forceDelete();
                }
            } else {
                $customer->entries()->delete();
            }
        });
    }

    public function entries()
    {
        return $this->hasMany(Entry::class);
    }
}

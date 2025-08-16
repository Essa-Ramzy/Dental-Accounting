<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Entry extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'entries';
    protected $primaryKey = 'id';
    protected $fillable = ['customer_id', 'item_id', 'date', 'teeth', 'amount', 'unit_price', 'discount', 'price', 'cost'];
    protected $casts = ['date' => 'date'];

    public function getTeethListAttribute()
    {
        return collect(explode(', ', $this->teeth))
            ->map(fn($t) => explode('-', trim($t)))
            ->flatMap(fn($pair) => collect(str_split($pair[1]))
                ->map(fn($n) => "$pair[0]-$n"));
    }

    public function getUnitCostAttribute()
    {
        return $this->cost / $this->amount;
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}

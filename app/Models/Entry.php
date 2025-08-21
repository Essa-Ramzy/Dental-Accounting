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

    protected static function booted()
    {
        static::restoring(function ($entry) {
            if ($entry->customer()->withTrashed()->first() && $entry->customer()->withTrashed()->first()->trashed()) {
                $entry->customer()->withTrashed()->first()->restore();
            }
            if ($entry->item()->withTrashed()->first() && $entry->item()->withTrashed()->first()->trashed()) {
                $entry->item()->withTrashed()->first()->restore();
            }
        });
    }

    public function getTeethListAttribute()
    {
        if (empty($this->teeth)) {
            return collect();
        }

        return collect(explode(', ', $this->teeth))
            ->flatMap(function ($pair) {
                if (strpos($pair, '-') === false) {
                    return collect();
                }
                [$quadrant, $numbers] = explode('-', $pair, 2);
                return collect(str_split($numbers))
                    ->map(fn($n) => "$quadrant-$n");
            })
            ->filter();
    }

    public function getUnitCostAttribute()
    {
        if ($this->amount == 0) {
            return 0;
        }
        return $this->cost / $this->amount;
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function customerWithTrashed()
    {
        return $this->belongsTo(Customer::class)->withTrashed();
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function itemWithTrashed()
    {
        return $this->belongsTo(Item::class)->withTrashed();
    }
}

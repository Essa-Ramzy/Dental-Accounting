<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Entries extends Model
{
    protected $table = 'entries';
    protected $primaryKey = 'id';
    protected $fillable = ['customer_id', 'item_id', 'date', 'teeth', 'discount', 'price', 'cost'];
    protected $casts = ['date' => 'date'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}

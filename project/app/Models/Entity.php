<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Entity extends Model
{
    public string $test = 'Test';

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}

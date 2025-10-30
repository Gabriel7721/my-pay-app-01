<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasUuids;

    protected $fillable = [
        'id',
        'user_id',
        'amount',
        'currency',
        'status',
        'email',
        'name'
    ];

    public $incrementing = false;

    protected $keyType = 'string';

    public function items() {
        return $this->hasMany(OrderItem::class);
    }

    public function payment() {
        return $this->hasOne(Payment::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}

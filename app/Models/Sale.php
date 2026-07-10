<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;

class Sale extends Model
{
    protected $fillable = [
        'user_id',
        'total_amount'
    ];
    
     // Sale belongs to a user (cashier)
    public function user(){
        return $this->belongsTo(User::class);
    }
    
    // Sale has many sale items
    public function saleItems(){
        return $this->hasMany(SaleItem::class);
    }
}

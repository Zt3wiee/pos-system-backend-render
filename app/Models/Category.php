<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name'];
    // One category has many products
    // This means that a category can have multiple products associated with it.
    public function products(){
        return $this->hasMany(Product::class);
    }
}

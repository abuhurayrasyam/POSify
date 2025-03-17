<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Category;
use App\Models\InvoiceProduct;

class Product extends Model
{
    protected $fillable = [
        'user_id',
        'category_id',
        'name',
        'price',
        'unit',
        'image'
    ];
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function category(){
        return $this->belongsTo(Category::class);
    }
    public function invoiceProducts(){
        return $this->hasMany(InvoiceProduct::class);
    }
}

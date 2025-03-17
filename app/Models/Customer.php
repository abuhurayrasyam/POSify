<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Invoice;

class Customer extends Model
{
    protected $fillable = [
        'name',
        'email',
        'mobile',
        'user_id'
    ];
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function invoices(){
        return $this->hasMany(Invoice::class);
    }
}

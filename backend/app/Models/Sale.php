<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sale extends Model
{
    use SoftDeletes;
    
    protected $fillable = ['description', 'seller_id', 'amount', 'sale_date'];

    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }
}

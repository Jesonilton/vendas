<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Commission extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'seller_id',
        'sale_id',
        'commission',
    ];

    public function seller() {
        return $this->belongsTo(Seller::class);
    }

    public function sale() {
        return $this->belongsTo(Sale::class);
    }
}

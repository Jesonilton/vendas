<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Seller extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'email'];

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $fillable = ['name', 'cost', 'price', 'quantity', 'faults', 'created_at', 'updated_at'];

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }
}

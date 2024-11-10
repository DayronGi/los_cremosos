<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['product_id', 'total_sale', 'quantity', 'profit', 'date'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

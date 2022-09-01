<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table = 'products';

    public function category() { return $this->belongsTo(ProductCategory::class, 'product_categories_id', 'id'); }
    public function trxDetails() { return $this->hasMany(TransactionDetail::class, 'transactions_id', 'id'); }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table = 'products';

    public function category() { $this->belongsTo(ProductCategory::class, 'product_categories_id', 'id'); }
    public function trxDetails() { $this->hasMany(TransactionDetail::class, 'transactions_id', 'id'); }
}

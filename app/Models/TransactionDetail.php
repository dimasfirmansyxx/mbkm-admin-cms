<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    use HasFactory;

    protected $table = 'transaction_details';

    public function product() { return $this->belongsTo(Product::class, 'products_id', 'id'); }
    public function trx() { return $this->belongsTo(Transaction::class, 'transactions_id', 'id'); }
}

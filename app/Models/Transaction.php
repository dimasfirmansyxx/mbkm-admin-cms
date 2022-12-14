<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $tables = 'transactions';

    public function trxDetails() { return $this->hasMany(TransactionDetail::class, 'transactions_id', 'id'); }
    public function vocUsages() { return $this->hasOne(VoucherUsage::class, 'transactions_id', 'id'); }
}

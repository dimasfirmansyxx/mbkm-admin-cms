<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoucherUsage extends Model
{
    use HasFactory;

    protected $table = 'voucher_usages';

    public function voucher() { $this->belongsTo(Voucher::class, 'vouchers_id', 'id'); }
    public function trx() { $this->belongsTo(Transaction::class, 'transactions_id', 'id'); }
}

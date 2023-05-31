<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Billing extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'billings';

    protected $fillable = [
        'trainee_id',
        'amount_due',
        'payment_status',
        'payment_date',
        'visa',
        'cvc',
    ];

    public function trainee()
    {
        return $this->belongsTo(Trainee::class);
    }
}

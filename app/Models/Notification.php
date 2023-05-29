<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notification extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['type', 'notifiable_type', 'notifiable_id', 'data'];

    protected $table = 'notifications';


    public function scopeByLevel($query)
    {
        if (auth()->user()->guard == 'manager') {
            return $query->where([
                'type' => 'register_Advisor',
            ])->orWhere([
                'type' => 'register_Trainee',
            ]);
        }
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

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
        } else if (auth()->user()->guard == 'advisor') {
            return $query->where([
                'type' => 'assignCourse',
                'notifiable_id' => Auth::user()->advisor->id,
            ]);
        } else if (auth()->user()->guard == 'trainee') {
            return $query->where([
                'type' => 'assignCourse',
                'notifiable_id' => Auth::user()->trainee->id,
            ]);
        }
    }
}

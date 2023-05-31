<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Meeting extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'trainee_id',
        'advisor_id',
        'datetime',
        'status',
        'details',
    ];

    public function trainee()
    {
        return $this->belongsTo(Trainee::class);
    }

    public function advisor()
    {
        return $this->belongsTo(Advisor::class);
    }

    public function scopeByLevel($query)
    {
        if (auth()->user()->guard == 'advisor') {
            return $query->where([
                'advisor_id' => Auth::user()->advisor->id,
            ])->with(['advisor' => function ($q) {
                return $q->with('user');
            }]);
        }
        if (auth()->user()->guard == 'trainee') {
            return $query->where([
                'trainee_id' => Auth::user()->trainee->id,
            ])->with(['trainee' => function ($q) {
                return $q->with('user');
            }]);
        }
        return $query;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Field extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name'];

    public function advisors()
    {
        return $this->belongsToMany(Advisor::class, 'advisor_field');
    }

    public function trainees()
    {
        return $this->belongsToMany(Trainee::class, 'trainee_field', 'field_id', 'trainee_id')
            ->withPivot('status');
    }
}

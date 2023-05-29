<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'course_num',
        'desc',
        'field_id',
        'advisor_id',
        'duration',
        'duration_unit',
        'location',
        'start_date',
        'end_date',
        'fees',
        'capacity',
        'num_trainee',
    ];

    public function field()
    {
        return $this->belongsTo(Field::class);
    }

    public function advisor()
    {
        return $this->belongsTo(Advisor::class);
    }

    public function trainees()
    {
        return $this->belongsToMany(Trainee::class, 'course_trainee');
    }

    public function advisors()
    {
        return $this->belongsToMany(Advisor::class, 'course_advisor');
    }
}
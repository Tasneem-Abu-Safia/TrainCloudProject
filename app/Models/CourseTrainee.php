<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class CourseTrainee extends Model
{
    use HasFactory;

    protected $table = 'course_trainee';
    protected $fillable = ['course_id', 'trainee_id', 'advisor_id'];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function trainee()
    {
        return $this->belongsTo(Trainee::class);
    }

    public function advisor()
    {
        return $this->belongsTo(Advisor::class, 'advisor_id');
    }

    public function scopeByLevel($query)
    {
        if (auth()->user()->guard == 'advisor') {
            return $query->where([
                'advisor_id' => Auth::user()->advisor->id,
            ]);
        }
        return $query;
    }

}

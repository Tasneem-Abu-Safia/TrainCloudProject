<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseTrainee extends Model
{
    use HasFactory;
    protected $table = 'course_trainee';

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function trainee()
    {
        return $this->belongsTo(Trainee::class);
    }
}

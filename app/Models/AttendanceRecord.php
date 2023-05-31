<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AttendanceRecord extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['course_id', 'trainee_id', 'status', 'date'];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function trainee()
    {
        return $this->belongsTo(Trainee::class);
    }
}

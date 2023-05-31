<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Trainee extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['user_id', 'phone', 'address', 'degree', 'status', 'files'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_trainee');
    }

    public function fields()
    {
        return $this->belongsToMany(Field::class, 'trainee_field', 'trainee_id', 'field_id')
            ->withPivot('status');
    }

    public function meetings()
    {
        return $this->hasMany(Meeting::class);
    }

    public function attendanceRecords()
    {
        return $this->hasMany(AttendanceRecord::class);
    }

    public function billing()
    {
        return $this->hasOne(Billing::class);
    }


}

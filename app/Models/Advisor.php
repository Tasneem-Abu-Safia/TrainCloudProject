<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Advisor extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['user_id', 'phone', 'address', 'degree', 'status', 'files'];

    public function fields()
    {
        return $this->belongsToMany(Field::class, 'advisor_field');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_advisor');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}

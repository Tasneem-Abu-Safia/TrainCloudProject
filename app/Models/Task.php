<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'course_id',
        'advisor_id',
        'title',
        'description',
        'file',
        'start_date',
        'end_date',
        'mark',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function advisor()
    {
        return $this->belongsTo(Advisor::class);
    }

    public function submissions()
    {
        return $this->hasMany(TaskSubmission::class);
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaskSubmission extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'task_id',
        'trainee_id',
        'file',
        'mark',
        'status',
    ];


    public function trainee()
    {
        return $this->belongsTo(Trainee::class);
    }

    public function submissions()
    {
        return $this->hasMany(TaskSubmission::class);
    }

}

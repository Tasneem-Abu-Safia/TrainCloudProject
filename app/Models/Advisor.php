<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Advisor extends Model
{
    use HasFactory ,SoftDeletes;

    protected $fillable = ['user_id', 'phone', 'address', 'degree', 'status', 'files', 'degree'];

    public function fields()
    {
        return $this->belongsToMany(Field::class, 'advisor_field');
    }
}

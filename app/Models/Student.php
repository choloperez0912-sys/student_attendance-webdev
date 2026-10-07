<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = ['student_number', 'name', 'photo'];

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}
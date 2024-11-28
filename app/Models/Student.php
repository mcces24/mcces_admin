<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',          // Student's name
        'email',         // Student's email
        'date_of_birth', // Student's date of birth
        'enrollment_date', // Date of enrollment
        // Add other fields as necessary
    ];

    // Optionally, define relationships (e.g., if a student has many courses)
    // public function courses()
    // {
    //     return $this->belongsToMany(Course::class);
    // }
}

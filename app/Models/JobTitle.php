<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobTitle extends Model
{
    use HasFactory;

    // If the table name is different from the plural of the model name
    protected $table = 'jobtitles';

    // If the primary key is not auto-incrementing
    public $incrementing = true;

    // Disable timestamps if your table doesn't have created_at and updated_at columns
    public $timestamps = false;

    public function employees()
    {
        return $this->hasMany(Employee::class, 'job_title', 'id');
    }
    

}

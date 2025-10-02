<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayGrade extends Model
{
    use HasFactory;

    // If the table name is different from the plural of the model name
    protected $table = 'paygrades';

    // If the primary key is not auto-incrementing
    public $incrementing = true;

    // Disable timestamps if your table doesn't have created_at and updated_at columns
    public $timestamps = false;

    public function employees()
    {
        return $this->hasMany(Employee::class, 'pay_grade', 'id');
    }
    

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    // If the table name is different from the plural of the model name
    protected $table = 'companystructures';
    
    protected $fillable = ['title', 'description', 'address'] ;
    // Disable timestamps if your table doesn't have created_at and updated_at columns
    public $timestamps = false;
    
    // Define the inverse relationship
    public function employees()
    {
        return $this->hasMany(Employee::class, 'company', 'id');
    }
}

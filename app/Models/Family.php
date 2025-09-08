<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Family extends Model
{
    use HasFactory;

    // If the table name is different from the plural of the model name
    protected $table = 'family';

    // Specify the primary key if it's different from 'id'
    // protected $primaryKey = 'id';

    // If the primary key is not auto-incrementing
    // public $incrementing = false;

    public function getSpouseStatusAttribute($value)
    {
        if (is_null($value) || $value === 'N/A') {
            return 'N/A'; // Return blank if the value is null
        }

        return $value === 'Yes' ? 'Employed' : 'Unemployed';
    }

    // Disable timestamps if your table doesn't have created_at and updated_at columns
    public $timestamps = false;

    // Specify which attributes are mass assignable (if you're using mass assignment)
    protected $fillable = ['name', 'ssn_num', 'spouse_name', 'spouse_status', 'spouse_ic', 'spouse_tax', 'noc_under', 'tax_under', 'noc_above', 'tax_above', 'child1', 'child2', 'child3', 'child4', 'child5', 'child6', 'child7', 'child8', 'child9', 'child10', 'contact1_name', 'contact1_no', 'contact1_rel', 'contact1_add', 'contact2_name', 'contact2_no', 'contact2_rel', 'contact2_add', 'contact3_name', 'contact3_no', 'contact3_rel', 'contact3_add'] ;

    public function employees()
    {
        return $this->hasMany(Employee::class, 'family', 'id');
    }
}

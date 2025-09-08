<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    // If the table name is different from the plural of the model name
    protected $table = 'employees';

    // Specify the primary key if it's different from 'id'
    // protected $primaryKey = 'id';

    // If the primary key is not auto-incrementing
    // public $incrementing = false;

    protected $casts = [
        'birthday' => 'date',
        'joined_date' => 'date',
        'termination_date' => 'date',
        'expiry_date' => 'date',
    ];

    // Disable timestamps if your table doesn't have created_at and updated_at columns
    public $timestamps = false;

    // Specify which attributes are mass assignable (if you're using mass assignment)
    protected $fillable = ['employee_id', 'first_name', 'middle_name', 'last_name', 'nationality', 'birthday', 'gender', 'marital_status', 'ssn_num', 'nic_num', 'other_id', 'driving_license', 'driving_license_exp_date', 'employment_status', 'job_title', 'pay_grade', 'work_station_id', 'branch', 'address1', 'address2', 'city', 'country', 'state', 'postal_code', 'home_phone', 'mobile_phone', 'work_phone', 'work_email', 'private_email', 'joined_date', 'confirmation_date', 'supervisor', 'indirect_supervisors', 'company', 'department', 'custom1', 'custom2', 'custom3', 'custom4', 'custom5', 'custom6', 'custom7', 'custom8', 'custom9', 'custom10', 'termination_date', 'notes', 'status', 'ethnicity', 'immigration_status', 'epf_no', 'socso', 'lhdn_no', 'family', 'qualification', 'experience'];

    // Specify which attributes should be hidden for arrays (e.g., sensitive data)
    protected $hidden = ['password'];
    public function companyStructure()
    {
        return $this->belongsTo(Company::class, 'company', 'id');
    }
    public function stateName()
    {
        return $this->belongsTo(State::class, 'state', 'id');
    }

    public function ethnicityName()
    {
        return $this->belongsTo(Ethnicity::class, 'ethnicity', 'id');
    }
    public function employmentStatus()
    {
        return $this->belongsTo(E_Status::class, 'employment_status', 'id');
    }
    public function jobTitle()
    {
        return $this->belongsTo(JobTitle::class, 'job_title', 'id');
    }
    public function payGrade()
    {
        return $this->belongsTo(PayGrade::class, 'pay_grade', 'id');
    }
    public function countryName()
    {
        return $this->belongsTo(Country::class, 'country', 'code');
    }
    public function national()
    {
        return $this->belongsTo(Nationality::class, 'nationality', 'id');
    }

    public function familyDetails()
    {
        return $this->belongsTo(Family::class, 'family', 'id');
    }
    public function departmentName()
    {
        return $this->belongsTo(Department::class, 'department', 'id');
    }
}

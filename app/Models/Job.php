<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;

    // Specify the table name
    protected $table = 'recruitment';

    public $timestamps = false;

    // Define fillable columns
    protected $fillable = ['title', 'vacancies', 'applicants', 'interviewed', 'hired'];
}

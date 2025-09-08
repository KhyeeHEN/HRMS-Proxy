<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyEvent extends Model
{
    use HasFactory;

    protected $table = 'company_events';

    protected $fillable = [
        'title',
        'start_date',
        'end_date',
        'comments'
    ];
}

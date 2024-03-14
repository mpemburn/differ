<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComparisonResult extends Model
{
    use HasFactory;
    protected $fillable = [
        'source',
        'before_date',
        'after_date',
        'filename',
        'diff_percentage'
    ];
}

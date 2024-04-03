<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComparisonResult extends Model
{
    use HasFactory;
    protected $fillable = [
        'source',
        'test_number',
        'before_date',
        'after_date',
        'filename',
        'diff_percentage'
    ];

    public function scopeLatestTest($query, ?string $source)
    {
        $max = ComparisonResult::where('source', $source)->max('test_number');

        return ComparisonResult::where('source', $source)->where('test_number', $max);
    }
}

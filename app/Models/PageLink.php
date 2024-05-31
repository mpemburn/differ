<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageLink extends Model
{
    use HasFactory;

    public $fillable = [
        'image',
        'url',
        'when',
        'source_file',
        'test_name'
    ];
}

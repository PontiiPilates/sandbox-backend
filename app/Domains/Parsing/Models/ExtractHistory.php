<?php

namespace App\Domains\Parsing\Models;

use Illuminate\Database\Eloquent\Model;

class ExtractHistory extends Model
{
    protected $fillable = [
        'extraction_ulid',
        'extraction_date',
        'extraction_type',
        'count_extraction',
        'count_saved',
        'prepared_date',
        'prepared_ulid',
    ];
}

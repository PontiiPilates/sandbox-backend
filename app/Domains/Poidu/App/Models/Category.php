<?php

namespace App\Domains\Poidu\App\Models;

use App\Domains\Poidu\Pipeline\Models\EventMining;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    /** @use HasFactory<\Database\Factories\CategoryFactory> */
    use HasFactory;

    public function events(): HasMany
    {
        return $this->hasMany(EventMining::class);
    }
}

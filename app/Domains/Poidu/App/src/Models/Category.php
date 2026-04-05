<?php

namespace App\Domains\Poidu\App\src\Models;

use App\Domains\Poidu\Pipeline\src\Models\EventMining;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    /** @use HasFactory<\Database\Factories\CategoryFactory> */
    use HasFactory;

    public function eventsMining(): HasMany
    {
        return $this->hasMany(EventMining::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }
}

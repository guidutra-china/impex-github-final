<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
    protected $fillable = ['name', 'color'];

    public function companies(): belongsToMany
    {
        return $this->morphedByMany(Company::class, 'taggable');
    }
    public function products(): belongsToMany
    {
        return $this->morphedByMany(Company::class, 'taggable');
    }


}

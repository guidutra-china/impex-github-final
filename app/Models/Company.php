<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'city',
        'state',
        'zip',
        'country',
        'phone',
        'email',
        'website',
        'is_supplier',
        'is_customer',
    ];

    public function tags(): morphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function contacts(): BelongsToMany
    {
        return $this->belongsToMany(Contact::class);
    }
}
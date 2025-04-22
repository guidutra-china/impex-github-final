<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;


class Company extends Model implements HasMedia
{

    use InteractsWithMedia;
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
        'type',
    ];

    protected $casts = [

        'images' => 'array',

    ];

    public function tags(): morphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function contacts(): BelongsToMany
    {
        return $this->belongsToMany(Contact::class);
    }

    public function images(): MorphToMany
    {
        return $this->morphToMany(Media::class, 'media');
    }

    public function productsWithClients(): HasMany
    {
        return $this->hasMany(Product::class, 'client_id');
    }

    public function productsWithSuppliers(): HasMany
    {
        return $this->hasMany(Product::class, 'supplier_id');
    }
    public function scopeClients($query)
    {
        return $query->whereIn('type', ['client', 'both']);
    }

    public function scopeSuppliers($query)
    {
        return $query->whereIn('type', ['supplier', 'both']);
    }


}
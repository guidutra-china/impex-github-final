<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\HasMedia;

class Product extends Model implements HasMedia
{
    use InteractsWithMedia;
    protected $fillable = [
        'name',
        'description',
        'sku_client',
        'sku_supplier',
        'hscode',
        'ncm',
        'cost',
        'price',
        'currency',
        'familyproducts_id'
    ];

    protected $casts = [

        'images' => 'array',

    ];

    public function family(): BelongsTo
    {
        return $this->belongsTo(Family::class);
    }

    public function images(): MorphToMany
    {
        return $this->morphToMany(Media::class, 'media');
    }

    public function tags(): morphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }
    public function client(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'client_id');
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'supplier_id');
    }

}

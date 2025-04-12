<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Contact extends Model
{
    protected $fillable = ['name', 'email', 'phone', 'position','wechat','other','birthday'];
    public function companies(): belongsToMany
    {
        return $this->belongsToMany(Company::class);
    }
}

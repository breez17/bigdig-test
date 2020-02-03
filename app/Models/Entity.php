<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * Class Entity
 * @package App\Models
 * @property int id
 * @property string name
 * @property \DateTime date
 * @property string image
 * @property EntityFile[]|Collection files
 */
class Entity extends Model
{
    public $timestamps = false;

    protected $casts = [
        'date' => 'datetime',
    ];

    public const MAX_FILES = 2;

    /**
     * @return HasMany
     */
    public function files(): HasMany
    {
        return $this->hasMany(EntityFile::class,'entity_id','id');
    }
}

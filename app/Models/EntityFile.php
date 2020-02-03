<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class EntityFile
 * @package App\Models
 * @property int id
 * @property int entity_id
 * @property string name
 * @property \DateTime created_at
 * @property \DateTime updated_at
 */
class EntityFile extends Model
{
    protected $casts = [
        'created_at' => 'datetime',
    ];

    /**
     * @var array
     */
    protected $appends = ['path'];

    /**
     * @return string
     */
    public function getPathAttribute(): string
    {
        $dateCreated = $this->created_at->format('Y-m-W');

        return asset('storage/upload/' . $dateCreated . '/'. $this->name);
    }
}

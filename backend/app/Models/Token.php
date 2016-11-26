<?php

namespace VkMusic\Models;


/**
 * @property string token
 * @author Sergei Melnikov <me@rnr.name>
 */
class Token extends Model
{
    protected $casts = [
        'data' => 'array'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
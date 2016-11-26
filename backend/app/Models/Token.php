<?php

namespace VkMusic\Models;


/**
 * @property string token
 * @property array $data
 * @property User user
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
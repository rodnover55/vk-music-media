<?php

namespace VkMusic\Models;


/**
 * @property int id
 * @author Sergei Melnikov <me@rnr.name>
 */
class Favorite extends Model
{
    public function resource() {
        return $this->morphTo();
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
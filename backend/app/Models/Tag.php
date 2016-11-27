<?php

namespace VkMusic\Models;


/**
 * @property string tag
 * @author Sergei Melnikov <me@rnr.name>
 */
class Tag extends Model
{
    public function favorite() {
        return $this->morphOne(Favorite::class, 'resource');
    }
}
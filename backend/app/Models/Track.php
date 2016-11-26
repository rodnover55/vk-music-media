<?php

namespace VkMusic\Models;

/**
 * @property int owner_id
 * @property int aid
 * @author Sergei Melnikov <me@rnr.name>
 */
class Track extends Model
{
    public function posts() {
        return $this->belongsToMany(Post::class, 'posts_tracks');
    }
}
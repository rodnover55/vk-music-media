<?php

namespace VkMusic\Models;
use Illuminate\Database\Eloquent\Collection;


/**
 * @property Collection|Tag[] tags
 * @property string description
 * @property Collection|Track[] tracks
 */
class Post extends Model
{
    public function tags() {
        return $this->belongsToMany(Tag::class, 'posts_tags');
    }

    public function tracks() {
        return $this->belongsToMany(Track::class, 'posts_tracks');
    }
}
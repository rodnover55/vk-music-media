<?php

namespace VkMusic\Models;
use Illuminate\Database\Eloquent\Collection;


/**
 * @property Collection|Tag[] tags
 * @author Sergei Melnikov <me@rnr.name>
 */
class Post extends Model
{
    public function tags() {
        return $this->belongsToMany(Tag::class, 'posts_tags');
    }
}
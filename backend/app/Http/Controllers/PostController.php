<?php

namespace VkMusic\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use VkMusic\Models\Post;
use VkMusic\Models\Track;


/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class PostController extends Controller
{
    public function index() {
        $posts = Post::with(['tags', 'tracks'])->get();


        return new JsonResponse($this->transformPosts($posts));
    }

    public function transformPosts(Collection $posts) {
        $data = $posts->map(function (Post $item) {
            $tags = $item->tags->pluck('tag')->toArray();

            $post = $item->attributesToArray();
            $post['tracks'] = $item->tracks->map(function (Track $track) {
                return $track->attributesToArray();
            })->toArray();

            $post['tags'] = $tags;

            return $post;
        })->toArray();

        return $data;
    }
}
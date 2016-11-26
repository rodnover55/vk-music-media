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

    public function show($id) {
        $post = Post::with(['tags', 'tracks'])->findOrFail($id);

        return new JsonResponse($this->transformPost($post));
    }

    public function transformPosts(Collection $posts) {
        $data = $posts->map(function (Post $item) {
            return $this->transformPost($item);
        })->toArray();

        return $data;
    }

    public function transformPost(Post $item) {
        $tags = $item->tags->pluck('tag')->toArray();

        $post = $item->attributesToArray();
        $post['tracks'] = $item->tracks->map(function (Track $track) {
            return $track->attributesToArray();
        })->toArray();

        $post['tags'] = $tags;

        return $post;
    }
}
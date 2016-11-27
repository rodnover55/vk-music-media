<?php

namespace VkMusic\Http\Controllers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use VkMusic\Models\Post;
use VkMusic\Models\Track;


/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class PostController extends Controller
{
    public function index(Request $request) {
        /** @var Post|Builder $query */
        $query = Post::with(['tags', 'tracks', 'favorite' => function ($query) {
            $token = $this->getToken();

            /** @var Builder$query */
            $query->where('user_id', $token->user->uid);
        }]);

        if ($request->has('tags')) {
            $tags = explode(',', $request->get('tags'));

            $query->whereHas('tags', function ($query) use ($tags) {
                /** @var Builder $query */
                $query->whereIn('tag', $tags);
            });
        }

        if ($request->has('artist')) {
            $artist = $request->get('artist');

            $query->whereHas('tracks', function ($query) use ($artist) {
                /** @var Builder $query */
                $query->where(\DB::raw('lower(tracks.artist)'), mb_strtolower($artist));
            });
        }

        $favorites = $request->get('favorites');

        if (!empty($favorites)) {
            $query->whereHas('favorite', function ($query) {
                $token = $this->getToken();
                $query->where('user_id', $token->user->uid);
            });
        }

        $posts = $query->get();

        return new JsonResponse($this->transformPosts($posts));
    }

    public function show($id) {
        $post = Post::with(['tags', 'tracks', 'favorite' => function ($query) {
            $token = $this->getToken();
            /** @var Builder$query */
            $query->where('user_id', $token->user->uid);
        }])->findOrFail($id);

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
        $post['favorite'] = isset($item->favorite) ? ($item->favorite->id) : (null);

        return $post;
    }
}
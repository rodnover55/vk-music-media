<?php

namespace VkMusic\Http\Controllers;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use VkMusic\Models\Tag;


/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class TagController extends Controller
{
    public function index(Request $request) {
        $query = Tag::query();

        if ($request->has('q')) {
            $q = $request->get('q');
            $query->where('tag', 'like', "%{$q}%");
        }

        $favorites = $request->get('favorites');

        if (!empty($favorites)) {
            $query->whereHas('favorite', function ($query) {
                $token = $this->getToken();
                /** @var Builder $query */
                $query->where('user_id', $token->user->uid);
            });
        }
        $tags = $query->pluck('tag');

        return new JsonResponse($tags);
    }
}
<?php

namespace VkMusic\Http\Controllers;
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
        $tags = $query->pluck('tag');

        return new JsonResponse($tags);
    }
}
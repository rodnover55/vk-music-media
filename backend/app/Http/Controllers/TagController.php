<?php

namespace VkMusic\Http\Controllers;
use Illuminate\Http\JsonResponse;
use VkMusic\Models\Tag;


/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class TagController extends Controller
{
    public function index() {
        $query = Tag::query();

        $tags = $query->pluck('tag');

        return new JsonResponse($tags);
    }
}
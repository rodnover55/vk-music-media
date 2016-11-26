<?php

namespace VkMusic\Http\Controllers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use VkMusic\Http\Requests\FavoriteRequest;
use VkMusic\Models\Favorite;


/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class FavoritesController extends Controller
{
    public function store(FavoriteRequest $request) {
        $favorite = Favorite::where([
            'resource_id' => $request->resource_id,
            'resource_type' => $request->resource_type
        ])->first();

        if (isset($favorite)) {
            throw new BadRequestHttpException(json_encode([
                'error' => 'Already marked'
            ]));
        }

        $favorite = Favorite::create([
            'resource_id' => $request->resource_id,
            'resource_type' => $request->resource_type
        ]);

        return new JsonResponse($favorite);
    }

    public function destroy($id) {
        /** @var Favorite $favorite */
        $favorite = Favorite::find($id);

        if (empty($favorite)) {
            throw new NotFoundHttpException(json_encode([
                'error' => 'Favorite not found'
            ]));
        }

        $favorite->delete();

        return (new Response())->setStatusCode(Response::HTTP_NO_CONTENT);
    }
}
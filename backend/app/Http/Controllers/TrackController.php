<?php

namespace VkMusic\Http\Controllers;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use VkMusic\Models\Track;
use VkMusic\Service\VkApi;


/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class TrackController extends Controller
{
    /**
     * @param int $trackId
     * @return JsonResponse
     */
    public function show($trackId) {
        /** @var Track $data */
        $data = Track::findOrFail($trackId);

        $id = implode('_', [$data->owner_id, $data->aid]);
        $token = $this->getToken();

        /** @var VkApi $vkApi */
        $vkApi = $this->container->make(VkApi::class);

        $response = $vkApi
            ->setAccessToken($token->data['access_token'])
            ->sendOpen('audio.getById', [
                'audios' => $id
            ])['response'];

        $track = $data->attributesToArray();
        $track['link'] = $response['url'];

        return new JsonResponse($track);
    }

    public function index(Request $request) {
        $query = Track::query();

        if ($request->has('tags')) {
            $tags = explode(',', $request->get('tags'));

            $query->whereHas('posts.tags', function ($query) use ($tags) {
                /** @var Builder $query */
                $query->whereIn('tag', $tags);
            });
        }

        if ($request->has('artist')) {
            $artist = $request->get('artist');

            $query->where(\DB::raw('lower(tracks.artist)'), mb_strtolower($artist));
        }

        $favorites = $request->get('favorites');

        if (!empty($favorites)) {
            $query->whereHas('favorite', function ($query) {
                $token = $this->getToken();
                /** @var Builder $query */
                $query->where('user_id', $token->user->uid);
            });
        }

        $tracks = $query->get();

        return new JsonResponse($this->transformTracks($tracks));
    }

    public function transformTracks(Collection $tracks) {
        return $tracks->map(function (Track $track) {
            return $this->transformTrack($track);
        });
    }

    public function transformTrack(Track $data) {
        $track = $data->attributesToArray();

        $track['favorite'] = $data->favorite->id ?? null;

        return $track;
    }
}
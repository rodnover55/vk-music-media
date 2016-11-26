<?php

namespace VkMusic\Http\Controllers;
use Illuminate\Http\JsonResponse;
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
}
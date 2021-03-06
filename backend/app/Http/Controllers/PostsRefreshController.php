<?php

namespace VkMusic\Http\Controllers;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use VkMusic\Models\Post;
use VkMusic\Models\Tag;
use VkMusic\Models\Track;
use VkMusic\Service\VkApi;
use DateTime;


/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class PostsRefreshController extends Controller
{
    public function store() {
        $token = $this->getToken();

        $data = $token->data;

        /** @var VkApi $vkApi */
        $vkApi = $this->container->make(VkApi::class);

        $vkApi
            ->setApiId($data['api_id'])
            ->setUrl('https://api.vk.com/method/');

        $response = $vkApi->sendOpen('wall.get', [
            'owner_id' => '-' . $data['group_id'],
            'count' => 1000,
            'extended' => 1,
            'fields' => 'id,name,screen_name'
        ])['response'];


        $savedGroups = array_map(function (array $group) {
            return $this->saveGroup($group);
        }, $response['groups']);

        $wall = $response['wall'];
        array_shift($wall);

        foreach ($wall as $post) {
            $this->savePost($post);
        }

        return (new Response())->setStatusCode(Response::HTTP_NO_CONTENT);
    }

    public function savePost($data) {
        $audios = Collection::make($data['attachments'] ?? [])->filter(
            function (array $item) {
                return $item['type'] == 'audio';
            })->map(function (array $item) {
            return $item['audio'];
        });

        if ($audios->isEmpty()) {
            return null;
        }

        $gid = -1 * $data['from_id'];

        if ($gid < 0) {
            return null;
        }

        /** @var Post $post */
        $post = Post::firstOrCreate([
            'pid' => $data['id'],
            'group_id' => $gid
        ], [
            'created_at' => (new DateTime())->setTimestamp($data['date']),
            'title' => $this->getTitle($data),
            'image' => $data['attachment']['photo']['src'] ?? null,
            'description' => $data['text'],
        ]);

        $tags = $this->getTags($post->description);
        $post->tags()->sync(array_pluck($tags, 'id'));

        $tracks = $this->getTracks($audios->toArray());

        $post->tracks()->sync(array_pluck($tracks, 'id'));

        return $post;
    }

    public function saveGroup($group) {
        return null;
    }

    public function getTitle(array $post) {
        return "Post {$post['id']} of {$post['from_id']}";
    }

    /**
     * @param $text
     * @return Tag[]
     */
    public function getTags($text) {
        $matches = [];
        $tags = [];

        if (preg_match_all('/#(\w+)/u', $text, $matches)) {
            $tags = array_map(function ($tag) {
                /** @var Tag $tag */
                $tag = Tag::firstOrCreate([
                    'tag' => $tag
                ]);

                return $tag;
            }, $matches[1]);
        }

        return $tags;
    }

    /**
     * @param array $tracks
     * @return array|Track[]
     */
    public function getTracks($tracks) {
        return array_map(function (array $item) {
            return $this->saveTrack($item);
        }, $tracks);
    }

    public function saveTrack($item): Track {
        return Track::firstOrCreate([
            'aid' => $item['id'] ?? $item['aid'],
            'owner_id' => $item['owner_id']
        ], [
            'artist' => $item['artist'],
            'title' => $item['title'],
            'duration' => $item['duration']
        ]);
    }
}
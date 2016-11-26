<?php

namespace VkMusic\Http\Controllers;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Response;
use VkMusic\Models\Post;
use VkMusic\Service\VkApi;
use DateTime;


/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class PostsRefreshController extends Controller
{
    public function store(Guard $auth) {
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
        $gid = -1 * $data['from_id'];

        if ($gid < 0) {
            return null;
        }

        $post = Post::firstOrCreate([
            'pid' => $data['id'],
            'group_id' => $gid
        ], [
            'created_at' => (new DateTime())->setTimestamp($data['date']),
            'title' => $this->getTitle($data),
            'image' => $data['attachment']['photo']['src'] ?? null,
            'description' => $data['text'],
        ]);

        $post->save();

        return $post;
    }

    public function saveGroup($group) {
        return null;
    }

    public function getTitle(array $post) {
        return "Post {$post['id']} of {$post['from_id']}";
    }
}
<?php

namespace VkMusic\Http\Controllers;
use Illuminate\Contracts\Auth\Guard;
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
        ]);


        $savedGroups = array_map(function (array $group) {
            return $this->saveGroup($group);
        }, $response['groups']);

        $wall = $response['wall'];
        array_shift($wall);

        foreach ($wall as $post) {
            $this->savePost($post);
        }
    }

    public function savePost($data) {
        $post = new Post([
            'pid' => $data['id'],
            'created_at' => (new DateTime())->setTimestamp($data['date']),
            'title' => $this->getTitle($data),
            'image' => $data['attachment']['photo']['src'] ?? null,
            'description' => $data['text'],
            'group_id' => -1 * $data['from_id']
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
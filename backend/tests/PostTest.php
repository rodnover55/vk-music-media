<?php

namespace VkMusic\Tests;

use VkMusic\Models\Post;
use VkMusic\Tests\Support\DatabaseTruncate;


/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class PostTest extends TestCase
{
    use DatabaseTruncate;

    public function testAll() {
        $object = $this->loadYmlFixture('get-posts.yml');

        $this->auth($object['token']->token)->getJson('/api/posts');

        $this->assertResponseOk();

        $json = $this->decodeResponseJson();

        foreach ($json as $post) {
            $this->seeJsonStructure([
                'id', 'pid', 'group_id', 'created_at', 'title', 'image', 'description',
                'created_at', 'updated_at',
                'tags' => [
                    '*'=> []
                ],
                'tracks' => [
                    '*' => [
                        'id', 'aid', 'owner_id', 'title', 'artist', 'duration',
                        'created_at', 'updated_at'
                    ]
                ]
            ], $post);
        }

        /** @var Post $post */
        $post = $object['post'];
        $this->assertCount($post->tags->count(), $json[0]['tags']);
        $this->assertCount($post->tracks->count(), $json[0]['tracks']);
    }

    public function testPostItem() {
        $object = $this->loadYmlFixture('get-posts.yml');

        $postId = $object['post']->id;
        $this->auth($object['token']->token)->getJson("/api/posts/{$postId}");

        $this->assertResponseOk();

        $json = $this->decodeResponseJson();

        $this->seeJsonStructure([
            'id', 'pid', 'group_id', 'created_at', 'title', 'image', 'description',
            'created_at', 'updated_at',
            'tags' => [
                '*'=> []
            ],
            'tracks' => [
                '*' => [
                    'id', 'aid', 'owner_id', 'title', 'artist', 'duration',
                    'created_at', 'updated_at'
                ]
            ]
        ], $json);
    }
}
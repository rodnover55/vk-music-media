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
                'created_at', 'updated_at', 'favorite',
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

    public function testGetItem() {
        $object = $this->loadYmlFixture('get-posts.yml');

        $postId = $object['post']->id;
        $this->auth($object['token']->token)->getJson("/api/posts/{$postId}");

        $this->assertResponseOk();

        $json = $this->decodeResponseJson();

        $this->seeJsonStructure([
            'id', 'pid', 'group_id', 'created_at', 'title', 'image', 'description',
            'created_at', 'updated_at', 'favorite',
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

    public function testGetByTags() {
        $object = $this->loadYmlFixture(['get-posts.yml', 'second-post.yml']);

        $this->auth($object['token']->token)->getJson('/api/posts?tags=test');

        $this->assertResponseOk();

        $json = $this->decodeResponseJson();

        $this->assertCount(1, $json);

        $this->assertEquals($object['post-2']->getKey(), $json[0]['id']);
    }

    public function testGetByMultipleTags() {
        $objects = $this->loadYmlFixture(['get-posts.yml', 'second-post.yml']);

        $this->auth($objects['token']->token)->getJson('/api/posts?tags=test,folk');

        $this->assertResponseOk();

        $json = $this->decodeResponseJson();

        $this->assertCount(2, $json);

        $this->assertEquals([
            $objects['post']->getKey(),
            $objects['post-2']->getKey()
        ], array_pluck($json, 'id'));
    }

    public function testByArtist() {
        $object = $this->loadYmlFixture(['get-posts.yml', 'second-post.yml']);

        $this->auth($object['token']->token)->getJson('/api/posts?artist=Test');

        $this->assertResponseOk();

        $json = $this->decodeResponseJson();

        $this->assertCount(1, $json);

        $this->assertEquals($object['post-2']->getKey(), $json[0]['id']);
    }

    public function testByFavorites() {
        $object = $this->loadYmlFixture([
            'get-posts.yml', 'second-post.yml', 'favorite-post.yml'
        ]);

        $this->auth($object['token']->token)->getJson('/api/posts?favorites=true');

        $this->assertResponseOk();

        $json = $this->decodeResponseJson();

        $this->assertCount(1, $json);

        $this->assertEquals($object['post']->favorite->id, $json[0]['favorite']);
    }
}
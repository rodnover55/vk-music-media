<?php

namespace VkMusic\Tests;

use Illuminate\Http\Response;
use VkMusic\Tests\Support\DatabaseTruncate;


/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class FavoriteTest extends TestCase
{
    use DatabaseTruncate;

    public function testPost() {
        $objects = $this->loadYmlFixture(['post.yml', 'token.yml']);

        $postId = $objects['posts-1']->id;

        $this->auth($objects['token']->token)->postJson('/api/favorites', [
            'resource_id' => $postId,
            'resource_type' => 'post'
        ]);

        $this->assertResponseStatus(Response::HTTP_NO_CONTENT);

        $this->seeInDatabase('favorites', [
            'resource_id' => $postId,
            'resource_type' => 'post'
        ]);
    }
}
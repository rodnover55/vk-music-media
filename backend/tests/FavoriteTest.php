<?php

namespace VkMusic\Tests;

use Illuminate\Http\Response;
use VkMusic\Models\Favorite;
use VkMusic\Models\Token;
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
        /** @var Token $token */
        $token = $objects['token'];

        $this->auth($token->token)->postJson('/api/favorites', [
            'resource_id' => $postId,
            'resource_type' => 'post'
        ]);

        $this->assertResponseOk();

        $this->seeJsonStructure([
            'id', 'resource_id', 'resource_type', 'created_at', 'updated_at'
        ]);

        $this->seeInDatabase('favorites', [
            'resource_id' => $postId,
            'resource_type' => 'post',
            'user_id' => $token->user->uid
        ]);
    }

    public function testDelete() {
        $objects = $this->loadYmlFixture(['post.yml', 'token.yml', 'favorite.yml']);

        $postId = $objects['posts-1']->id;
        /** @var Favorite $favorite */
        $favorite = $objects['favorite'];

        $this->auth($objects['token']->token)->deleteJson("/api/favorites/{$favorite->id}", [
            'resource_id' => $postId,
            'resource_type' => 'post'
        ]);

        $this->assertResponseStatus(Response::HTTP_NO_CONTENT);

        $this->dontSeeInDatabase('favorites', $favorite->attributesToArray());
    }

    public function testPostFavorite() {
        $objects = $this->loadYmlFixture(['post.yml', 'token.yml', 'favorite.yml']);

        $postId = $objects['posts-1']->id;
        /** @var Favorite $favorite */
        $favorite = $objects['favorite'];

        $this->auth($objects['token']->token)->getJson("/api/posts/{$postId}");

        $this->assertResponseOk();

        $json = $this->decodeResponseJson();

        $this->assertEquals($favorite->id, $json['favorite']);
    }

    public function testTagFavorite() {

    }

    public function testTrackFavorite() {
        $objects = $this->loadYmlFixture([
            'track.yml', 'token.yml', 'favorite-track.yml'
        ]);

        /** @var Favorite $favorite */
        $favorite = $objects['favorite'];

        $this->auth($objects['token']->token)->getJson("/api/tracks");

        $this->assertResponseOk();

        $json = $this->decodeResponseJson();

        $this->assertEquals($favorite->id, $json[0]['favorite']);
    }
}
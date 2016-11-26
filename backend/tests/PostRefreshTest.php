<?php

namespace VkMusic\Tests;

use Illuminate\Http\Response;
use VkMusic\Http\Controllers\PostsRefreshController;
use VkMusic\Tests\Support\DatabaseTruncate;
use DateTime;


/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class PostRefreshTest extends TestCase
{
    use DatabaseTruncate;

    public function testUnauth() {
        $this->postJson('/api/posts-refresh');

        $this->assertResponseStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function testGrab() {
        $objects = $this->loadFixture('token.yml');

        $this->auth($objects['token']->token)->postJson('/api/posts-refresh');

        $this->assertResponseStatus(Response::HTTP_NO_CONTENT);
    }

    public function testSavePost() {
        /** @var PostsRefreshController $controller */
        $controller = $this->app->make(PostsRefreshController::class);

        $post = json_decode(file_get_contents(self::ROOT_TEST . '/fixtures/new-post.json'), true);

        $controller->savePost($post);

        $this->seeInDatabase('posts', [
            'pid' => $post['id'],
            'created_at' => (new DateTime())->setTimestamp($post['date']),
            'title' => $controller->getTitle($post),
            'image' => $post['attachment']['photo']['src'] ?? null,
            'description' => $post['text'],
            'group_id' => -1 * $post['from_id']
        ]);
    }
}
<?php

namespace VkMusic\Tests;

use Illuminate\Http\Response;
use VkMusic\Http\Controllers\PostsRefreshController;
use VkMusic\Service\VkApi;
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
        $objects = $this->loadYmlFixture('token.yml');

        $this->app->instance(VkApi::class, new class($this) extends VkApi {
            private $test;

            public function __construct(TestCase $test)
            {
                $this->test = $test;
            }

            public function sendOpen(string $method, array $data)
            {
                return $this->test->loadJsonFixture('posts-response.json');
            }
        });

        $this->auth($objects['token']->token)->postJson('/api/posts-refresh');

        $this->assertResponseStatus(Response::HTTP_NO_CONTENT);
    }

    public function testSavePost() {
        /** @var PostsRefreshController $controller */
        $controller = $this->app->make(PostsRefreshController::class);

        $post = $this->loadJsonFixture('new-post.json');

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

    public function testDontSaveExits() {
        /** @var PostsRefreshController $controller */
        $controller = $this->app->make(PostsRefreshController::class);

        $post = $this->loadJsonFixture('new-post.json');
        $this->loadYmlFixture('post.yml');

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

    public function testDontSaveUser() {
        /** @var PostsRefreshController $controller */
        $controller = $this->app->make(PostsRefreshController::class);

        $post = $this->loadJsonFixture('user-post.json');

        $controller->savePost($post);

        $this->dontSeeInDatabase('posts', [
            'pid' => $post['id'],
            'group_id' => -1 * $post['from_id']
        ]);

        $this->dontSeeInDatabase('posts', [
            'pid' => $post['id'],
            'group_id' => $post['from_id']
        ]);
    }
}
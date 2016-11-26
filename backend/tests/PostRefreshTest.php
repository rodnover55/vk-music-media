<?php

namespace VkMusic\Tests;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Response;
use VkMusic\Http\Controllers\PostsRefreshController;
use VkMusic\Models\Post;
use VkMusic\Models\Tag;
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

        /** @var Post $post */
        $post = Post::with(['tags'])->where([
            'pid' => $post['id'],
            'created_at' => (new DateTime())->setTimestamp($post['date']),
            'title' => $controller->getTitle($post),
            'image' => $post['attachment']['photo']['src'] ?? null,
            'description' => $post['text'],
            'group_id' => -1 * $post['from_id']
        ])->first();

        $tags = $controller->getTags($post['text']);

        $this->assertEquals($tags, $post->tags);

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

    /**
     * @dataProvider tagsProvider
     * @param array $tags
     * @param string $text
     */
    public function testGetTags(array $tags, string $text) {
        /** @var PostsRefreshController $controller */
        $controller = $this->app->make(PostsRefreshController::class);

        $actual = $controller->getTags($text);

        $this->assertEquals($tags, array_pluck($actual, 'tag'));

        foreach ($actual as $item) {
            $this->seeInDatabase('tags', $item->attributesToArray());
        }
    }

    public function tagsProvider() {
        return [
            'base' => [
                ['abc', '1234', 'abc_1234'],
                "qwere #abc \n #1234 qdqwfwefergg adqwdweff #abc_1234"
            ]
        ];
    }

    public function testGetTagDontSaveExists() {
        $objects = $this->loadYmlFixture('tag.yml');

        /** @var Tag $tag */
        $tag = $objects['tag'];
        /** @var PostsRefreshController $controller */
        $controller = $this->app->make(PostsRefreshController::class);

        $actual = $controller->getTags("#{$tag->tag}");

        $this->assertEquals([$tag->toArray()], Collection::make($actual)->toArray());
    }
}
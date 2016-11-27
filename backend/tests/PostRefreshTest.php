<?php

namespace VkMusic\Tests;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Response;
use VkMusic\Http\Controllers\PostsRefreshController;
use VkMusic\Models\Post;
use VkMusic\Models\Tag;
use VkMusic\Models\Track;
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

        $postData = $this->loadJsonFixture('new-post.json');

        $controller->savePost($postData);

        /** @var Post $post */
        $post = Post::with(['tags', 'tracks'])->where([
            'pid' => $postData['id'],
            'created_at' => (new DateTime())->setTimestamp($postData['date']),
            'title' => $controller->getTitle($postData),
            'image' => $postData['attachment']['photo']['src'] ?? null,
            'description' => $postData['text'],
            'group_id' => -1 * $postData['from_id']
        ])->first();

        $tags = $controller->getTags($postData['text']);

        $this->assertCount(4, $post->tags);

        $this->assertEquals(array_map(function (Tag $tag) {
            return $tag->attributesToArray();
        }, $tags), $post->tags->map(function (Tag $tag) {
            return $tag->attributesToArray();
        })->toArray());

        $tracks = $controller->getTracks(Collection::make($postData['attachments'])->filter(
            function (array $item) {
                return $item['type'] == 'audio';
            })->map(function (array $item) {
            return $item['audio'];
        })->toArray());

        $this->assertCount(3, $post->tracks);

        $this->assertEquals(array_values(array_map(function (Track $track) {
            return $track->attributesToArray();
        }, $tracks)), $post->tracks->map(function (Track $track) {
            return $track->attributesToArray();
        })->values()->toArray());
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

    public function testSaveEmpty() {
        /** @var PostsRefreshController $controller */
        $controller = $this->app->make(PostsRefreshController::class);

        $post = $this->loadJsonFixture('empty-post.json');

        $controller->savePost($post);

        $this->dontSeeInDatabase('posts', [
            'pid' => $post['id'],
            'group_id' => -1 * $post['from_id']
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

    public function testSaveTrack() {
        $track = $this->loadJsonFixture('track.json');

        /** @var PostsRefreshController $controller */
        $controller = $this->app->make(PostsRefreshController::class);

        $track = $controller->saveTrack($track);

        $this->seeInDatabase('tracks', $track->attributesToArray());
    }

    public function testDontSaveExistingTrack() {
        /** @var Track $expected */
        $expected = $this->loadYmlFixture('track.yml')['track'];

        $track = $this->loadJsonFixture('track.json');

        /** @var PostsRefreshController $controller */
        $controller = $this->app->make(PostsRefreshController::class);

        $track = $controller->saveTrack($track);

        $this->assertEquals($expected->attributesToArray(), $track->attributesToArray());

        $this->seeInDatabase('tracks', $track->attributesToArray());
    }
}
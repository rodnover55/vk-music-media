<?php

namespace VkMusic\Tests;

use Illuminate\Http\Request;
use VkMusic\Tests\Support\DatabaseTruncate;


/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class TagTest extends TestCase
{
    use DatabaseTruncate;

    public function testGetAll() {
        $objects = $this->loadYmlFixture(['tag.yml', 'token.yml']);

        $this->auth($objects['token']->token)->getJson('/api/tags');

        $this->assertResponseOk();

        $json = $this->decodeResponseJson();

        $this->assertEquals([
            $objects['tag']->tag,
            $objects['tag-2']->tag,
            $objects['tag-3']->tag
        ], $json);
    }

    public function testGetByQuery() {
        $objects = $this->loadYmlFixture(['tag.yml', 'token.yml']);

        $this->auth($objects['token']->token)->getJson('/api/tags?q=abc');

        $this->assertResponseOk();

        $json = $this->decodeResponseJson();

        $this->assertCount(2, $json);

        $this->assertEquals([$objects['tag']->tag, $objects['tag-3']->tag], $json);
    }

    public function testByFavorites() {
        $object = $this->loadYmlFixture([
            'tag.yml', 'token.yml', 'favorite-tag.yml'
        ]);

        $this->auth($object['token']->token)->getJson('/api/tags?favorites=true');

        $this->assertResponseOk();

        $json = $this->decodeResponseJson();

        $this->assertCount(1, $json);

        $this->assertEquals($object['tag']->tag, $json[0]);
    }
}
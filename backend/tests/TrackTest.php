<?php

namespace VkMusic\Tests;

use VkMusic\Models\Track;
use VkMusic\Tests\Support\DatabaseTruncate;


/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class TrackTest extends TestCase
{
    use DatabaseTruncate;

//    public function testGetItem() {
//        $objects = $this->loadYmlFixture(['track.yml', 'token.yml']);
//
//        $trackId = $objects['track']->id;
//
//        $this->auth($objects['token']->token)->getJson("/api/tracks/{$trackId}");
//
//        $this->assertResponseOk();
//
//        $this->seeJsonStructure([
//            'id', 'aid', 'owner_id', 'title', 'artist', 'duration',
//            'created_at', 'updated_at', 'link'
//        ]);
//    }

    public function testAllByTag() {
        $objects = $this->loadYmlFixture(['get-posts.yml', 'second-post.yml']);

        $this->auth($objects['token']->token)->getJson("/api/tracks?tags=test");

        $this->assertResponseOk();

        $json = $this->decodeResponseJson();

        $this->assertCount(1, $json);

        $track = $objects['tracks-4'];

        $this->assertEquals($track->getKey(), $json[0]['id']);

        $this->seeJsonStructure([
            'id', 'aid', 'owner_id', 'title', 'artist', 'duration',
            'created_at', 'updated_at'
        ], $json[0]);
    }

    public function testAllByMultipleTag() {
        $objects = $this->loadYmlFixture(['get-posts.yml', 'second-post.yml']);

        $this->auth($objects['token']->token)->getJson("/api/tracks?tags=test,folk");

        $this->assertResponseOk();

        $json = $this->decodeResponseJson();

        $this->assertCount(4, $json);

        $expected = [
            $objects['tracks-1']->getKey(),
            $objects['tracks-2']->getKey(),
            $objects['tracks-3']->getKey(),
            $objects['tracks-4']->getKey()
        ];

        $actual = array_pluck($json, 'id');

        sort($expected);
        sort($actual);
        $this->assertEquals($expected, $actual);

        $this->seeJsonStructure([
            'id', 'aid', 'owner_id', 'title', 'artist', 'duration',
            'created_at', 'updated_at'
        ], $json[0]);
    }

    public function testAllByArtist() {
        $objects = $this->loadYmlFixture(['get-posts.yml', 'second-post.yml']);

        $this->auth($objects['token']->token)->getJson("/api/tracks?artist=test");

        $this->assertResponseOk();

        $json = $this->decodeResponseJson();

        $this->assertCount(1, $json);

        $track = $objects['tracks-4'];

        $this->assertEquals($track->getKey(), $json[0]['id']);

        $this->seeJsonStructure([
            'id', 'aid', 'owner_id', 'title', 'artist', 'duration',
            'created_at', 'updated_at'
        ], $json[0]);
    }
}
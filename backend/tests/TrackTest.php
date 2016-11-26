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

    public function testGetItem() {
        $objects = $this->loadYmlFixture(['track.yml', 'token.yml']);

        $trackId = $objects['track']->id;

        $this->auth($objects['token']->token)->getJson("/api/tracks/{$trackId}");

        $this->assertResponseOk();

        $this->seeJsonStructure([
            'id', 'aid', 'owner_id', 'title', 'artist', 'duration',
            'created_at', 'updated_at', 'link'
        ]);
    }
}
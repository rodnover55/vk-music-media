<?php

namespace VkMusic\Tests;

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

        $this->assertEquals([$objects['tag']->tag], $json);
    }

}
<?php

namespace VkMusic\Tests;

use Illuminate\Http\Response;


/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class CommonTest extends TestCase
{
    public function testApiNotFound() {
        $this->getJson('/api/not-found');

        $this->assertResponseStatus(Response::HTTP_NOT_FOUND);
    }
}
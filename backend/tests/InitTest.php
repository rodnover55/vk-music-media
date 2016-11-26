<?php

namespace VkMusic\Tests;


/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class InitTest extends TestCase
{
    public function testInit() {
        $data = http_build_query();
        $this->get("/?{$data}");
    }
}
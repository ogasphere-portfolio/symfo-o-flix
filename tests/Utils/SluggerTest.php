<?php

namespace App\Tests\Utils;

use App\Utils\Slugger;
use PHPUnit\Framework\TestCase;

class SluggerTest extends TestCase
{
    public function testSomething(): void
    {
        $slugger = new Slugger();

        $slug = $slugger->makeSlug('Mr Robot');


        $this->assertEquals('mr-robot', $slug);
    }
}

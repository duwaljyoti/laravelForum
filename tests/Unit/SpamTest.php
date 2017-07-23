<?php

namespace App\tests\Unit;

use Tests\TestCase;

class SpamTest extends TestCase
{
    private $spam;

    public function setUp()
    {
        $this->spam = new \App\Inspections\Spam;
    }

    public function testItShouldDetectInvalidKeywords()
    {
        $this->expectException('Exception');
        $this->spam->detect('bad words');
    }

    public function testItShouldDetectUnNecessaryKeyDownEvents()
    {
        $this->expectException('Exception');
        $this->spam->detect('aaaaa');
    }
}
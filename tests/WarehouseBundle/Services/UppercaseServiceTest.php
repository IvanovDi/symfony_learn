<?php

namespace Tests\WarehouseBundle\Services;

use PHPUnit\Framework\TestCase;
use WarehouseBundle\Services\UppercaseService;

class UppercaseServiceTest extends TestCase
{
    protected $object;

    public function __construct()
    {
        $this->object = new UppercaseService();
    }

    public function testToUppercase()
    {
        $this->assertEquals('HHHH',  $this->object->toUppercase('hhhh'));
    }
}
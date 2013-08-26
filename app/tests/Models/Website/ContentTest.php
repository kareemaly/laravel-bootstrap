<?php namespace Models\Website;


use Mockery;
use Website\Content\Content;

class ContentTest extends \TestCase {


    public function testCreate()
    {
        $content = $this->factory->create('Website\Content\Content');

        $this->assertTrue($content instanceof Content);

        return $content;
    }


    public function testInPlace()
    {
        $content = $this->testCreate();

        $place1Mock = Mockery::mock('Website\Place\Place');
        $place1Mock->shouldReceive('is')->with('kareem')->andReturn(false);

        $place2Mock = Mockery::mock('Website\Place\Place');
        $place2Mock->shouldReceive('is')->with('kareem')->andReturn(false);

        $place3Mock = Mockery::mock('Website\Place\Place');
        $place3Mock->shouldReceive('is')->with('kareem')->andReturn(true);

        $content->setAttribute('places', array($place1Mock, $place2Mock));

        $this->assertFalse($content->inPlace('kareem'));

        $content->setAttribute('places', array($place1Mock, $place2Mock, $place3Mock));

        $this->assertTrue($content->inPlace('kareem'));
    }

}
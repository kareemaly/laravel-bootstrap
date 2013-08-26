<?php namespace Models\Website;


use Website\Place\Place;

class PlaceTest {

    public function testCreate()
    {
        $place = $this->factory->create('Website\Place\Place');

        $this->assertTrue($place instanceof Place);

        return $place;
    }
}
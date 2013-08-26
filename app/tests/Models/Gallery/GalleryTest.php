<?php namespace Models\Gallery;

class GalleryTest extends \TestCase {

    public function testCreateGallery()
    {
        return $this->factory->create('Gallery\Gallery\Gallery');
    }

    public function testAddingImages()
    {
        $gallery = $this->testCreateGallery();

        $gallery->addImage($this->factory->create('Gallery\Image\Image'));
        $gallery->addImage($this->factory->create('Gallery\Image\Image'));
        $gallery->addImage($this->factory->create('Gallery\Image\Image'));

        $this->assertEquals(3, $gallery->images->count());
    }
}
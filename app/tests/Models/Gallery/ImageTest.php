<?php namespace Models\Gallery;

use Gallery\Image\Image;
use Gallery\Version\Version;
use Mockery;

class ImageTest extends \TestCase {

    private function createVersion()
    {
        return $this->factory->instance('Gallery\Version\Version', array(
            'image_id' => 0
        ));
    }

    /**
     * @expectedException \Zizaco\FactoryMuff\SaveException
     */
    public function testImageMustBeCreatedByValidUser()
    {
        $this->factory->create('Gallery\Image\Image',array(
            'user_id' => 652542
        ));
    }

    public function testAddingOneVersion()
    {
        $image = $this->factory->create('Gallery\Image\Image');

        $version = $this->createVersion();

        $image->add($version);

        $this->assertTrue($image->versions[0]->url == $version->url);
    }

    public function testAddingManyVersions()
    {
        $image = $this->factory->create('Gallery\Image\Image');

        $image->add(array($this->createVersion(), $this->createVersion(), $this->createVersion()));

        $this->assertTrue($image->versions->count() == 3);

        return $image;
    }

    public function testVersionExistsForThisImage()
    {
        $image = $this->factory->create('Gallery\Image\Image');

        $this->assertFalse($image->exists());

        $image->add($this->createVersion());

        $this->assertTrue($image->exists());
    }

    public function testGetVersionFromDimensions()
    {
        $image = $this->factory->create('Gallery\Image\Image');

        $image->add($this->createVersion());

        $versionAlgorithm = Mockery::mock('\Gallery\Version\VersionAlgorithm');
        $versionAlgorithm->shouldReceive('nearestDim')->times(1)->andReturn($versionAlgorithm);
        $versionAlgorithm->shouldReceive('smallestDim')->times(1)->andReturn($versionAlgorithm);
        $versionAlgorithm->shouldReceive('largestDim')->times(1)->andReturn($versionAlgorithm);
        $versionAlgorithm->shouldReceive('byImage')->times(3)->andReturn($versionAlgorithm);
        $versionAlgorithm->shouldReceive('first')->times(3)->andReturn($image->versions[0]);

        $image->setVersionAlgorithm($versionAlgorithm);

        $this->assertTrue($image->getNearest(120, 120) instanceof \Gallery\Version\Version);
        $this->assertTrue($image->getSmallest()        instanceof \Gallery\Version\Version);
        $this->assertTrue($image->getLargest()         instanceof \Gallery\Version\Version);
    }


    public function testCreateImageInstance()
    {
        return $this->factory->create('Gallery\Image\Image', array(
            'imageable_type' => 'Test/Test',
            'imageable_id'   => '1'
        ));
    }

    public function testImageIsBeingOrderedWhenSaved()
    {
        $image = $this->testCreateImageInstance();
        $this->assertEquals(1, $image->getOrder());

        $image = $this->testCreateImageInstance();
        $this->assertEquals(2, $image->getOrder());

        $image = $this->testCreateImageInstance();
        $this->assertEquals(3, $image->getOrder());
        $image->delete();

        $image = $this->testCreateImageInstance();
        $this->assertEquals(3, $image->getOrder());
    }


    public function testCanOverrideImage()
    {
        $image = $this->factory->create('Gallery\Image\Image');

        $image1 = $this->factory->create('Gallery\Image\Image');

        $this->assertEquals(1, $image->getOrder());
        $this->assertEquals(2, $image1->getOrder());

        $image->override($image1);

        $this->assertNull(Image::find($image1->id));
        $this->assertEquals(2, $image->getOrder());
    }


    public function testCanExchangeImageOrders()
    {
        $image = $this->testCreateImageInstance();
        $image1 = $this->testCreateImageInstance();

        $image->exchange($image1);

        $this->assertEquals(2, $image->getOrder(), "test");
        $this->assertEquals(1, $image1->getOrder(), "zcx");
    }


    public function testGetLastOrderMethod()
    {
        $this->testCanExchangeImageOrders();

        $image = $this->testCreateImageInstance();

        $this->assertEquals(3, $image->getLastOrder());
    }
}
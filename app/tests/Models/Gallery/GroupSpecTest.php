<?php namespace Models\Gallery;

use Mockery;

class GroupSpecTest extends \TestCase {

    public function testCreateNew()
    {
        return $this->factory->create('Gallery\GroupSpec\GroupSpec', array(
           'uri' => 'users/profile/{user}/image.jpg'
        ));
    }

    public function testGetUriAfterReplacing()
    {
        $groupConfig = $this->testCreateNew();

        $uri = $groupConfig->getUri(array(
            'user' => '1'
        ));

        $this->assertEquals($uri, 'users/profile/1/image.jpg');
    }

    public function testCreateOperations()
    {
        $groupConfig = $this->testCreateNew();

        $groupConfig->operations()->create(array(
            'method' => 'crop',
            'args'   => '150,150',
            'type'   => '\Intervention\Image\Image',
        ));

        $groupConfig->operations()->create(array(
            'method' => 'resize',
            'args'   => '130,60',
            'type'   => '\Intervention\Image\Image',
        ));

        $operation = $groupConfig->operations()->create(array(
            'method' => 'rotate',
            'type'   => '\Intervention\Image\Image',
        ));

        return $groupConfig;
    }

    public function testLoopThroughAllOperations()
    {
        $groupConfig = $this->testCreateOperations();

        $this->assertEquals(3, $groupConfig->operations->count());

        $this->assertEquals($groupConfig->operations[0]->getMethod(), 'crop');
        $this->assertEquals($groupConfig->operations[1]->getMethod(), 'resize');
        $this->assertEquals($groupConfig->operations[2]->getMethod(), 'rotate');
    }

    public function testManipulateObjectWithOperations()
    {
        $groupConfig = $this->testCreateOperations();

        $imageUploader = Mockery::mock('\Intervention\Image\Image');
        $imageUploader->shouldReceive('crop')->with(150, 150)->times(1);
        $imageUploader->shouldReceive('resize')->with(130, 60)->times(1);
        $imageUploader->shouldReceive('rotate')->withNoArgs()->times(1);

        $imageUploader = $groupConfig->manipulate($imageUploader);

        $this->assertTrue($imageUploader instanceof \Intervention\Image\Image);
    }
}
<?php namespace Models\Gallery;

class GroupTest extends \TestCase {

    public function testCreateNewImageGroup()
    {
        $imageGroup = $this->factory->create('Gallery\Group\Group');

        $this->assertNotNull($imageGroup);

        return $imageGroup;
    }

    public function testHasManyGroupSpecs()
    {
        $imageGroup = $this->testCreateNewImageGroup();

        $imageGroup->specs()->create(array(
            'uri' => 'users/profile/{user}/image.jpg'
        ));

        $imageGroup->specs()->create(array(
            'uri' => 'users/profile/{user}/image.jpg'
        ));

        $this->assertEquals(2, $imageGroup->specs->count());
    }
}
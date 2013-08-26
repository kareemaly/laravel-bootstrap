<?php namespace Models\Gallery;

use Gallery\Version\Version;

class VersionTest extends \TestCase {

    /**
     * @expectedException \Zizaco\FactoryMuff\SaveException
     */
    public function testMustEnterUrl()
    {

        $this->factory->create('Gallery\Version\Version', array(
            'url' => '',
        ));
    }

    /**
     * @expectedException \Zizaco\FactoryMuff\SaveException
     */
    public function testMustEnterWidthAndHeight()
    {
        $this->factory->create('Gallery\Version\Version', array(
            'width' => ''
        ));
    }

    /**
     * @expectedException \Zizaco\FactoryMuff\SaveException
     */
    public function testMustBelongToValidImage()
    {
        $this->factory->create('Gallery\Version\Version', array(
            'image_id' => 98465132
        ));
    }

    public function testCreatingVersionAttachedToImage()
    {
        $version = $this->factory->instance('Gallery\Version\Version');

        $this->assertTrue($version->save(), $this->showValidationMessages($version));
    }

    public function testGeneratingVersionDimensionsFromUrl()
    {
        $version = Version::generate(Version::$factory['url']);

        $this->assertNotNull($version);
    }

    public function testGeneratingVersionFromInvalidUrl()
    {
        $version = Version::generate('zcxvv');

        $this->assertNull($version);
    }
}
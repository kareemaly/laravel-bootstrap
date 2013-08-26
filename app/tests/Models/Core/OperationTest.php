<?php namespace Models\Core;

use Mockery;
use core\Operation\Operation;

class OperationTest extends \TestCase {

    public function testAddNewOperation()
    {
        $operation = $this->factory->create('\Core\Operation\Operation', array(
            'type'   => '\Intervention\Image\Image',
            'method' => 'crop',
            'args'   => '150,150',
        ));

        $this->assertTrue($operation->save());

        return $operation;
    }

    /**
     * @depends testAddNewOperation
     */
    public function testGetMethodAndArguments( Operation $operation )
    {
        $this->assertEquals($operation->getMethod(), 'crop');

        $this->assertEquals($operation->getArguments(), array('150', '150'));
    }

    /**
     * @depends testAddNewOperation
     */
    public function testCallMethodWithArgumentsOnObject(Operation $operation)
    {
        $image = Mockery::mock($operation->getType());
        $image->shouldReceive('crop')->with(150, 150)->times(1);

        $operation->call($image);
    }
}
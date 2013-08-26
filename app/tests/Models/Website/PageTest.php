<?php namespace Models\Website;


use Website\Page\Page;

class PageTest extends \TestCase {

    public function testCreate()
    {
        $page = $this->factory->create('Website\Page\Page');

        return $page;
    }


    public function testPageTreeStructure()
    {
        $parentPage = $this->factory->instance('Website\Page\Page');

        $child1 = $this->testCreate();
        $child1->parent()->associate($parentPage);

        $child2 = $this->testCreate();
        $child2->parent()->associate($parentPage);

        $this->assertEquals(2, $parentPage->children->count());

        $this->assertTrue($child1->parent instanceof Page);
        $this->assertTrue($child2->parent instanceof Page);

    }


}


<?php namespace Controllers;

use Blog\Archive;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\Paginator;

class HomeControllerTest extends \TestCase {

    public function testViewHasNecessaryVariables()
    {
        $response = $this->route('GET', 'home');

        $this->assertTrue($response->isOk());

        $view = $response->original;
    }

}
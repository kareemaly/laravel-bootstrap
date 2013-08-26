<?php

class HomeController extends BaseController {

    /**
     * @return Response
     */
    public function index()
	{
        $pageTitle = 'Recent tutorials';

		return View::make('home.index', compact('pageTitle'));
	}
}
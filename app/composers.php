<?php
use Asset\Asset;
use Website\Page\PageAlgorithm;

// Share error messages along all views
View::share('errors', (array) Session::get('errors', array()));

// Share success messages along all views
View::share('success', (array) Session::get('success', array()));

// Share authenticated user
View::share('authUser', Auth::user());

View::composer('layout1.index', function($view)
{
    $view->metaDescription = "";
    $view->metaKeywords    = "";
    $view->pageTitle       = isset($view->pageTitle) ? $view->pageTitle: 'Page title';

    Asset::addPage('layout1');
});

View::composer('layout1.header', function($view)
{
    $view->menuPages = PageAlgorithm::make()->inMenu()->get();
});

// Parts ................................................
View::composer('parts.modal', function($view)
{
    Asset::addPlugin('modal');
});

View::composer('parts.php_javascript', function($view)
{
    $view->sqlNow  = date('Y-m-d H:i:s');
    $view->baseUrl = URL::asset('');
});
/////////////////////////////////////////////////////////
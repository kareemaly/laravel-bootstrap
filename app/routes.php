<?php

use Membership\User\User;
use Website\Page\Page;

EasyRoute::controller('HomeController', array(

    'home'    => array('/', 'index'),
));

EasyRoute::controller('ContactUsController', array(

    'contact-us' => array('contact-us.html', 'index,send')
));

EasyRoute::controller('SiteMapController', array(

    'sitemap' => array('sitemap', 'xml')

));

try{
    // Loop through all pages and add route for their slugs...
    // This will throw exception if table didn't exist (yet) that's why I had to catch it.
    foreach(Page::all() as $page)
    {
        Route::get($page->slug, function() use ($page)
        {
            return View::make('pages.one', compact('page'));
        });
    }
}catch(Exception $e){}

Route::get('message-to-user.html', array('as' => 'message-to-user', function()
{
    if(! $messenger = Messenger::get()) return Redirect::route('home');

    return View::make('messenger.index', compact('messenger'));
}));

<?php

use Asset\Asset;
use PathManager\Path;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tracking\Tracker;

// Initialize my packages

// Initialize Asset class
Asset::init(URL::asset(''), app_path() . '/assets/plugins', app_path() . '/assets/pages');

// Initialize Path class
Path::init(URL::asset(''), public_path());

// Set mechanism for tracker class
Tracker::instance()->setMechanism(function()
{
   return Route::currentRouteName();
});

/*
|--------------------------------------------------------------------------
| Register The Laravel Class Loader
|--------------------------------------------------------------------------
|
| In addition to using Composer, you may use the Laravel class loader to
| load your controllers and models. This is useful for keeping all of
| your classes in the "global" namespace without Composer updating.
|
*/
ClassLoader::addDirectories(array(

    app_path().'/libraries',
	app_path().'/commands',
	app_path().'/controllers',
	app_path().'/models',
	app_path().'/database/seeds',
    app_path().'/exceptions'

));

/*
|--------------------------------------------------------------------------
| Application Error Logger
|--------------------------------------------------------------------------
|
| Here we will configure the error logger setup for the application which
| is built on top of the wonderful Monolog library. By default we will
| build a rotating log file setup which creates a new file each day.
|
*/

$logFile = 'log-'.php_sapi_name().'.txt';

Log::useDailyFiles(storage_path().'/logs/'.$logFile);

/*
|--------------------------------------------------------------------------
| Application Error Handler
|--------------------------------------------------------------------------
|
| Here you may handle any errors that occur in your application, including
| logging them or displaying custom views for specific errors. You may
| even register several error handlers to handle different types of
| exceptions. If nothing is returned, the default error view is
| shown, which includes a detailed stack trace during debug.
|
*/

App::error(function(Exception $exception, $code)
{
	Log::error($exception);
});



App::missing(function()
{
    $sitemap = SiteMap::instance()->toHtml();

    $messenger = new Messenger('404 - Page can not be found', 'Sorry but the page you are looking for cannot be found<br />' . $sitemap);

    return View::make('messenger.index', compact('messenger'));
});


App::error(function(GeneralException $exception, $code)
{
    dd($exception->getMessage());
});

/*
|--------------------------------------------------------------------------
| Maintenance Mode Handler
|--------------------------------------------------------------------------
|
| The "down" Artisan command gives you the ability to put an application
| into maintenance mode. Here, you will define what is displayed back
| to the user if maintenace mode is in effect for this application.
|
*/

App::down(function()
{
	return Response::make("Be right back!", 503);
});

/*
|--------------------------------------------------------------------------
| Require The Filters File
|--------------------------------------------------------------------------
|
| Next we will load the filters file for the application. This gives us
| a nice separate location to store our route and application filter
| definitions instead of putting them all in the main routes file.
|
*/

require app_path().'/filters.php';

/*
|--------------------------------------------------------------------------
| Load composer file
|--------------------------------------------------------------------------
|
| Loading composer file which includes all variables to be shared among
| different views.
|
*/

require app_path().'/composers.php';

/*
|--------------------------------------------------------------------------
| Load bindings file
|--------------------------------------------------------------------------
|
| Loading bindings file which includes all bindings in the IoC container.
|
*/

require app_path().'/bindings.php';
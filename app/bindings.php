<?php

use Gallery\Group\Group;
use Gallery\Group\GroupAlgorithm;
use Gallery\ImageFacade;
use Gallery\ImageManager;
use PathManager\Path;
use Symfony\Component\HttpFoundation\File\UploadedFile;

// Automatically Inject ImageManager
App::bind('\Gallery\ImageManager', function($app, Group $group)
{
    return new ImageManager($group, Path::makeFromBase(), App::make('\Gallery\Version\Version'));
});

App::bind('\Gallery\ImageFacade', function($app, UploadedFile $file)
{
    return new ImageFacade($file, new GroupAlgorithm);
});
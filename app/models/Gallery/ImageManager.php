<?php namespace Gallery;

use Gallery\Group\Group;
use Gallery\GroupSpec\GroupSpec;
use Gallery\Version\Version;
use Intervention\Image\Image as ImageUploader;
use PathManager\Path;

class ImageManager {

    /**
     * @var Group
     */
    protected $group;

    /**
     * @var Path
     */
    protected $path;

    /**
     * @var Version
     */
    protected $versions;

    /**
     * @param Group $group
     * @param Path $path Base path
     * @param Version $versions
     * @return \Gallery\ImageManager
     */
    public function __construct(Group $group, Path $path, Version $versions)
    {
        $this->group    = $group;
        $this->path     = $path;
        $this->versions = $versions;
    }

    /**
     * @param ImageUploader $imageUploader
     * @param array $replacers
     * @param GroupSpec $groupSpec
     * @return Version|Version[]
     */
    public function upload(ImageUploader $imageUploader, array $replacers = array(), GroupSpec $groupSpec = null)
    {
        if(! is_null($groupSpec))

            return $this->uploadOne($imageUploader, $replacers, $groupSpec);

        return $this->uploadAll($imageUploader, $replacers);
    }

    /**
     * @param ImageUploader $imageUploader
     * @param array $replacers
     * @param GroupSpec $groupSpec
     * @return Version
     */
    protected function uploadOne(ImageUploader $imageUploader, array $replacers = array(), GroupSpec $groupSpec)
    {
        $uri = $groupSpec->getUri($replacers);

        $destination = $this->path->make((string) $this->path . '\\' . $uri);

        $destination->makeSureItExists();

        $groupSpec->manipulate($imageUploader)->save((string) $destination);

        return $this->versions->newInstance(array(
            'width'  => $imageUploader->width,
            'height' => $imageUploader->height,
            'url'    => $destination->toUrl()
        ));
    }

    /**
     * @param ImageUploader $imageUploader
     * @param array $replacers
     * @return Version[]
     */
    protected function uploadAll(ImageUploader $imageUploader, array $replacers = array())
    {
        $versions = array();

        foreach($this->group->getSpecs() as $groupSpec)
        {
            $versions[] = $this->uploadOne($imageUploader, $replacers, $groupSpec);
        }

        return $versions;
    }
}
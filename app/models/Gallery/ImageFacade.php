<?php namespace Gallery;

use Gallery\Group\GroupAlgorithm;
use Gallery\Version\Version;
use Intervention\Image\Image as ImageUploader;
use Membership\User\User;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App;

class ImageFacade {

    /**
     * @var UploadedFile
     */
    protected $file;

    /**
     * @var GroupAlgorithm
     */
    protected $groupAlgorithm;

    /**
     * @var ImageManager
     */
    protected $imageManager;

    /**
     * @param UploadedFile $file
     * @param \Gallery\Group\GroupAlgorithm $groupAlgorithm
     */
    public function __construct(UploadedFile $file, GroupAlgorithm $groupAlgorithm)
    {
        $this->file = $file;
        $this->groupAlgorithm = $groupAlgorithm;
    }

    /**
     * Quick factory method to create upload images and get versions
     *
     * @param string $group
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
     * @param array $replacers
     * @param \Intervention\Image\Image $imageUploader
     * @return \Gallery\Version\Version[]
     */
    public static function versions($group, array $replacers = array(), UploadedFile $file, ImageUploader $imageUploader = null)
    {
        $image = App::make('\Gallery\ImageFacade', $file);

        return $image->upload($group, $replacers, $imageUploader);
    }

    /**
     * @param UploadedFile $file
     */
    public function setFile( UploadedFile $file )
    {
        $this->file = $file;
    }

    /**
     * @param GroupAlgorithm $groupAlgorithm
     */
    public function setGroupAlgorithm(GroupAlgorithm $groupAlgorithm)
    {
        $this->groupAlgorithm = $groupAlgorithm;
    }

    /**
     * @return GroupAlgorithm
     */
    public function getGroupAlgorithm()
    {
        return $this->groupAlgorithm;
    }

    /**
     * @return \Gallery\ImageManager
     */
    public function getImageManager()
    {
        return $this->imageManager;
    }

    /**
     * @param \Gallery\ImageManager $imageManager
     */
    public function setImageManager($imageManager)
    {
        $this->imageManager = $imageManager;
    }

    /**
     * @param $name
     * @return Group
     */
    public function getGroupByName( $name )
    {
        return $this->getGroupAlgorithm()->byName($name)->first();
    }

    /**
     * @return ImageUploader
     */
    public function makeUploader()
    {
        return ImageUploader::make($this->file->getRealPath());
    }

    /**
     * @param string $groupName
     * @param array $replacers
     * @param ImageUploader $imageUploader
     * @return \Gallery\Version\Version[]
     */
    public function upload( $groupName, array $replacers = array(), ImageUploader $imageUploader = null )
    {
        if(! $imageUploader) $imageUploader = $this->makeUploader();

        // Uploading image with given group specifications...
        $imageManager = App::make('\Gallery\ImageManager', $this->getGroupByName($groupName));

        return $imageManager->upload($imageUploader, $replacers);
    }
}
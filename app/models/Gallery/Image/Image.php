<?php namespace Gallery\Image;

use BaseModel;
use Gallery\Version\Version;
use Gallery\Version\VersionAlgorithm;
use Membership\User\User;

class Image extends \BaseModel implements \PolymorphicInterface {

    /**
     * @var array
     */
    protected $extensions = array('Ordered', 'Acceptable');

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'images';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array();

	/**
	 * The attributes that can't be mass assigned
	 *
	 * @var array
	 */
    protected $guarded = array('id', 'accepted');

    /**
     * Whether or not to softDelete
     *
     * @var bool
     */
    protected $softDelete = false;

    /**
     * Validations rules
     *
     * @var array
     */
    protected $rules = array(
        'user_id' => 'required|exists:users,id',
    );

    /**
     * For factoryMuff package to be able to fill attributes.
     *
     * @var array
     */
    public static $factory = array(
        'user_id' => 'factory|Membership\User\User',
    );

    /**
     * @var VersionAlgorithm
     */
    protected $versionAlgorithm;

    /**
     * @param array $attributes
     * @param VersionAlgorithm $versionAlgorithm
     * @return Image
     */
    public function __construct(array $attributes = array(), VersionAlgorithm $versionAlgorithm = null)
    {
        parent::__construct($attributes);

        $this->versionAlgorithm = $versionAlgorithm ?: new VersionAlgorithm();
    }

    /**
     * @param \Gallery\Version\VersionAlgorithm $versionAlgorithm
     */
    public function setVersionAlgorithm( VersionAlgorithm $versionAlgorithm )
    {
        $this->versionAlgorithm = $versionAlgorithm;
    }

    /**
     * @return \Gallery\Version\VersionAlgorithm
     */
    public function getVersionAlgorithm()
    {
        return $this->versionAlgorithm;
    }

    /**
     * @return \Illuminate\Database\Query\Builder
     */
    public function getOrderGroup()
    {
        return $this->where('imageable_type', $this->imageable_type)
                    ->where('imageable_id', $this->imageable_id);
    }

    /**
     * @param array $attributes
     * @param User $user
     * @param BaseModel $model
     * @return Image
     */
    public static function createAndAttach(array $attributes, User $user, BaseModel $model)
    {
        $image = $user->images()->create($attributes);

        $image->attachTo($model);

        return $image;
    }

    /**
     * @param \BaseModel $model
     * @return $this
     */
    public function attachTo( \BaseModel $model )
    {
        $this->imageable_type = get_class($model);
        $this->imageable_id   = $model->id;

        $this->save();

        return $this;
    }

    /**
     * Determines if there's any version for this image
     *
     * @return boolean
     */
    public function exists()
    {
        return ! $this->versions()->get(array('id'))->isEmpty();
    }

    /**
     * Update image versions
     *
     * @param  array $versions
     * @return void
     */
    public function replace( $versions )
    {
        // Delete all versions first
        $this->versions()->delete();

        // Now upload all versions.
        $this->add( $versions );
    }

    /**
     * Add versions for this image.
     *
     * @param  array|Version $versions
     * @return Image
     */
    public function add( $versions )
    {
        return is_array($versions) ? $this->addMany( $versions ) : $this->addOne( $versions );
    }

    /**
     * Add one version
     *
     * @param \Gallery\Version\Version $version
     * @return Image
     */
    private function addOne( Version $version )
    {
        // Now save this version and attach it to this image.
        $this->versions()->save( $version );

        return $this;
    }

    /**
     * Add many versions.
     *
     * @param  array $versions
     * @return Image
     */
    private function addMany( array $versions )
    {
        foreach ($versions as $version)
        {
            $this->addOne($version);
        }

        return $this;
    }

    /**
     * Get url of the largest image by looping through all versions
     * and get the largest width and height.
     *
     * @return string
     */
    public function getLargest()
    {
        return $this->getVersionAlgorithm()->largestDim()->byImage($this)->first();
    }

    /**
     * Get url of the smallest image by looping through all versions
     * and get the smallest width and height.
     *
     * @return Version
     */
    public function getSmallest()
    {
        return $this->getVersionAlgorithm()->smallestDim()->byImage($this)->first();
    }

    /**
     * Get version from given width and height by looping through all versions
     * and get the nearest one to the given width and height.
     *
     * @param  int $width
     * @param  int $height
     * @return Version
     */
    public function getNearest( $width, $height )
    {
        return $this->getVersionAlgorithm()->nearestDim($width, $height)->byImage($this)->first();
    }

    /**
     * Delete image with deleting all it's versions
     *
     * @return void
     */
    public function delete()
    {
        foreach ($this->versions as $version)
        {
            $version->delete();
        }

        parent::delete();
    }

    /**
     * Get version array
     *
     * @return Query
     */
    public function versions()
    {
        return $this->hasMany('Gallery\Version\Version');
    }

    /**
     * The model this image is morphed to.
     *
     * @return Query
     */
    public function imageable()
    {
        return $this->morphTo();
    }

    /**
     * The user created this image.
     *
     * @return Query
     */
    public function user()
    {
        return $this->belongsTo('Membership\User\User');
    }

    /**
     * Histories for this model.
     *
     * @return Query
     */
    public function histories()
    {
        return $this->morphMany('Website\History\History', 'historable');
    }
}
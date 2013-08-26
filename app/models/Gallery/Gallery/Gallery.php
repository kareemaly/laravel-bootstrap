<?php namespace Gallery\Gallery;

use Gallery\Image\Image;

class Gallery extends \BaseModel implements \PolymorphicInterface {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'galleries';

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
    protected $guarded = array('id');

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
        'user_id' => 'required|exists:users,id'
    );

    /**
     * For factoryMuff package to be able to fill attributes.
     *
     * @var array
     */
    public static $factory = array(
        'title' => 'string',
        'description' => 'text',
        'user_id' => 'factory|Membership\User\User'
    );

    /**
     * @param \BaseModel $model
     * @return $this
     */
    public function attachTo( \BaseModel $model )
    {
        $this->gallerable_type = get_class($model);
        $this->gallerable_id   = $model->id;

        $this->save();
    }

    /**
     * @return bool
     */
    public function hasImages()
    {
        return $this->images()->count() > 0;
    }

    /**
     * @param Image $image
     * @return Image
     */
    public function addImage( Image $image )
    {
        return $this->images()->save($image);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function gallerable()
    {
        return $this->morphTo();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('Membership\User\User');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function images()
    {
        return $this->morphMany('Gallery\Image\Image', 'imageable');
    }
}
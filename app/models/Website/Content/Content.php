<?php namespace Website\Content;

class Content extends \BaseModel {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'contents';

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
        'title' => 'required',
    );

    /**
     * For factoryMuff package to be able to fill attributes.
     *
     * @var array
     */
    public static $factory = array(
        'title' => 'string',
        'description' => 'text'
    );

    /**
     * Has many places
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function places()
    {
        return $this->morphMany('Website\Place\Place', 'placeable');
    }

    /**
     * Return true if this content has place with the given identifier
     *
     * @param  string $identifier
     * @return bool
     */
    public function inPlace( $identifier )
    {
        foreach ($this->places as $place)
        {
            if($place->is($identifier))

                return true;
        }

        return false;
    }
}
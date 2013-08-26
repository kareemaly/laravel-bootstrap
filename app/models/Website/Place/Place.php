<?php namespace Website\Place;

class Place extends \BaseModel {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'places';

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
        'identifier' => 'required',
        'name' => 'required'
    );

    /**
     * For factoryMuff package to be able to fill attributes.
     *
     * @var array
     */
    public static $factory = array(
        'identifier' => 'string',
        'name' => 'string',
    );

    /**
     * Get name which is actually the identifier
     *
     * @return string
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * Check if the place is correct
     *
     * @param $identifier
     * @return bool
     */
    public function is( $identifier )
    {
        return $this->identifier == $identifier;
    }

    /**
     * Get place by identifier
     *
     * @param  string $identifier
     * @return Place
     */
    public static function getByIdentifier( $identifier )
    {
        return static::where('identifier', $identifier)->first();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function content()
    {
        return $this->belongsTo( 'Website\Content\Content' );
    }

    /**
     * Get page for this place if exists
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function page()
    {
        return $this->belongsTo( 'Website\Page\Page' );
    }
}
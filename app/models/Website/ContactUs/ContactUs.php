<?php namespace Website\ContactUs;

class ContactUs extends \BaseModel {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'contact_us';

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
        'message' => 'required',
        'user_id' => 'required|exists:users,id'
    );

    /**
     * For factoryMuff package to be able to fill attributes.
     *
     * @var array
     */
    public static $factory = array(
        'title' => 'string',
        'message' => 'text',
        'user_id' => 'factory|Membership\User\User'
    );

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('Membership\User\User');
    }
}
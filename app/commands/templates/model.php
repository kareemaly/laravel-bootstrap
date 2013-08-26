<?php

return '<?php namespace ' .$namespace . ';

class ' .$class. ' extends \BaseModel {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = \'' . $table . '\';

	/**
	 * The attributes excluded from the model\'s JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array();

	/**
	 * The attributes that can\'t be mass assigned
	 *
	 * @var array
	 */
    protected $guarded = array(\'id\');

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
    );

    /**
     * For factoryMuff package to be able to fill attributes.
     *
     * @var array
     */
    public static $factory = array(
    );

}';
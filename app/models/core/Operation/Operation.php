<?php namespace core\Operation;

class Operation extends \BaseModel implements \PolymorphicInterface {

    /**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'operations';

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
        'type'   => 'required',
        'method' => 'required',
    );

    /**
     * @var array
     */
    public static $factory = array(
        'type'   => '\Intervention\Image\Image',
        'method' => 'string',
        'args'   => 'arg1,arg2,arg3',
    );

    /**
     * @param \BaseModel $model
     * @return $this
     */
    public function attachTo( \BaseModel $model )
    {
        $this->operable_type = get_class($model);
        $this->operable_id   = $model->id;

        $this->save();

        return $this;
    }

    /**
     * @param $code
     * @return \BaseModel
     */
    public static function createFromCodeFormat( $code )
    {
        $start = strpos($code, '(') + 1;
        $end = strpos($code, ')');

        $method= substr($code, 0, strpos($code, '('));
        $args = substr($code, $start, $end - $start);

        return static::create(array(
            'method' => $method,
            'args'   => $args
        ));
    }

    /**
     * @param $type
     */
    public function setType( $type )
    {
        $this->type = $type;
    }

    /**
     * @param $method
     */
    public function setMethod( $method )
    {
        $this->method = $method;
    }

    /**
     * @param array $arguments
     */
    public function setArguments( array $args )
    {
        $this->args = implode(',', $args);
    }

    /**
     * @return mixed
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @return array
     */
    public function getArguments()
    {
        if($this->args == '') return array();

        return explode(',', $this->args);
    }

    /**
     * @return string
     */
    public function getCodeFormat()
    {
        return $this->getMethod() . '(' . $this->args . ')';
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function operable()
    {
        return $this->morphTo();
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param $object
     * @return mixed
     */
    public function call($object)
    {
        $type = $this->getType();

        if($object instanceof $type) {

            return call_user_func_array(array($object, $this->getMethod()), $this->getArguments());
        }
    }
}
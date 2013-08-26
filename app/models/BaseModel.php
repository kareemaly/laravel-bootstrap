<?php

use Helpers\Helper;
use Illuminate\Database\Eloquent\Collection;

abstract class BaseModel extends Illuminate\Database\Eloquent\Model {

    /**
     * To solve PHP 5.3 no trait issue
     *
     * @var array
     */
    protected $extensions = array();

    /**
     * Validation rules
     *
     * @var array
     */
    protected $rules = array();

    /**
     * Validation custom messages
     *
     * @var array
     */
    protected $customMessages = array();

    /**
     * @var Illuminate\Validation\Validator
     */
    protected $validator = null;

    /**
     * @var bool
     */
    protected $dontValidate = false;

    /**
     * This will determine if any of the concrete classes will ever be validated
     *
     * @var bool
     */
    protected static $neverValidate = false;

    /**
     * @return void
     */
    public static function turnOffValidations()
    {
        static::$neverValidate = true;
    }

    /**
     * @return void
     */
    public static function turnOnValidations()
    {
        static::$neverValidate = false;
    }

    /**
     * We are giving all models the ability to clean it's attributes from XSS attack.
     *
     * @return void
     */
    public function cleanXSS()
    {
        Helper::instance()->cleanXSS($this->getAttributes());
    }

    /**
     * @return\Illuminate\Support\MessageBag
     */
    public function getValidatorMessages()
    {
        return $this->getValidator()->messages();
    }

    /**
     * This model should be validated.
     *
     * @return void
     */
    public function shouldValidate()
    {
        $this->dontValidate = false;
    }

    /**
     * This model should not be validated.
     *
     * @return void
     */
    public function dontValidate()
    {
        $this->dontValidate = true;
    }

    /**
     * This method will check if the given attributes are valid or not..
     * Remember that there is a validator object that holds the last validation.
     *
     * @return bool
     */
    public function isValid()
    {
        return $this->getValidator()->passes();
    }

    /**
     * @return void
     */
    public function resetValidator()
    {
        $this->validator = null;
    }

    /**
     * @param  string $name
     * @return void
     */
    public function validatePolymorphic( $name )
    {
        $type = $name . '_type';
        $id   = $name . '_id';

        // Add rules in the validations
        $this->rules[$type] = 'required';

        // Add custom messages
        $customMessage = 'No model is attached to this model.';
        $this->customMessages[$type . '.required'] = $this->getAttribute($type) . ',' . $customMessage;
        $this->customMessages[$id   . '.required'] = $customMessage;
        $this->customMessages[$id   . '.exists']       = $customMessage;

        $related = $this->getAttribute($type);

        // Check if class exists.
        if(class_exists($related)) {

            $model = new $related();

            $this->rules[$id] = 'required|exists:' . $model->getTable() . ',id';
        } else {

            // If type class doesn't exists then clear it to fail in the validation step.
            $this->setAttribute($type, '');
        }
    }

    /**
     * This method holds the state of the last validator.
     * If null then it will validate with the current model attributes.
     *
     * @return \Illuminate\Validation\Validator|null
     */
    public function getValidator()
    {
        if($this->validator) return $this->validator;

        // Search for polymorphic relationship to be validated
        if($key = array_search('polymorphic', $this->rules)) {

            $this->validatePolymorphic( $key );
        }

        return $this->validator = Validator::make($this->getAttributes(), $this->rules, $this->customMessages);
    }

    /**
     * Each time this method is called the model is validated from all over again
     *
     * @return bool
     */
    public function validate()
    {
        if($this->beforeValidate() === false) return false;

        // Will reset validator to validate the model again
        $this->resetValidator();

        if($this->getValidator()->passes()) {

            return true;
        }

        return false;
    }

    /**
     * This will validate the model and save it.
     *
     * @param array $attributes
     * @return BaseModel
     */
    public static function create( array $attributes )
    {
        $model = new static($attributes);

        $model->save();

        return $model;
    }

    /**
     * This will validate the model and save it.
     *
     * @param array $options
     * @return bool
     */
    public function save(array $options = array())
    {
        $validation = true;

        // If this model is not told to not validate then validate it..
        if(! $this->dontValidate && ! static::$neverValidate) $validation = $this->validate();

        if($validation) {

            if($this->beforeSave() === false) return false;

            return parent::save($options);
        }

        return false;
    }

    /**
     * Before validate event..
     *
     * @return mixed
     */
    public function beforeValidate()
    {
        $this->useMethodIfExists('beforeValidate');
    }

    /**
     * Before save event..
     *
     * @return mixed
     */
    public function beforeSave()
    {
        $this->useMethodIfExists('beforeSave');
    }

    /**
     * @param string $format
     * @return string
     */
    public function getCreatedAt( $format = '' )
    {
        $created_at = $this->getAttribute('created_at');

        if(! $format) return $created_at;

        else return date($format, strtotime($created_at));
    }

    /**
     * @param $method
     * @return null
     */
    protected function getExtensionObjectFromMethod($method)
    {
        foreach($this->extensions as $extension)
        {
            $className = $this->getExtensionFullName($extension);

            if(! class_exists($className)) continue;

            $object = new $className( $this );

            if(method_exists($object, $method)) return $object;
        }

        return null;
    }

    /**
     * @param $method
     */
    protected function useMethodIfExists($method)
    {
        if($this->usesMethod($method))
        {
            return $this->useMethod($method, array());
        }

        return null;
    }

    /**
     * @param  string $method
     * @return bool
     */
    protected function usesMethod($method)
    {
        return $this->getExtensionObjectFromMethod($method) != null;
    }

    /**
     * @param $method
     * @param $parameters
     * @return mixed
     */
    protected function useMethod($method, $parameters)
    {
        $object = $this->getExtensionObjectFromMethod($method);

        return call_user_func_array(array($object, $method), $parameters);
    }

    /**
     * @param $class
     * @return bool
     */
    public function doesUse( $class )
    {
        return array_search($class, $this->extensions) !== false;
    }

    /**
     * @param array $models
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function newCollection(array $models = array())
    {
        foreach($this->extensions as $extension)
        {
            if(method_exists($this->getExtensionFullName($extension), 'newCollection'))

                return call_user_func($this->getExtensionFullName($extension) . '::newCollection', $models);
        }

        return new Collection($models);
    }

    /**
     * @param string $use
     * @return string
     */
    protected function getExtensionFullName( $use )
    {
        return '\\Extensions\\' . $use . '\\' . $use;
    }

    /**
     * Using extensions
     *
     * @param string $method
     * @param array $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        if($this->usesMethod($method))
        {
            return $this->useMethod($method, $parameters);
        }

        return parent::__call($method, $parameters);
    }
}
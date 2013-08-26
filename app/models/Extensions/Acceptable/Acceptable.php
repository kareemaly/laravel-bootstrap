<?php namespace Extensions\Acceptable;

use BaseModel;

class Acceptable implements AcceptableInterface {

    /**
     * @var BaseModel
     */
    protected $model;

    /**
     * @param BaseModel $model
     */
    public function __construct( BaseModel $model )
    {
        $this->model = $model;
    }

    /**
     * @return bool
     */
    public function isAccepted()
    {
        return (boolean) $this->model->accepted;
    }

    /**
     * Accept current object.
     */
    public function accept()
    {
        $this->model->accepted = true;

        $this->model->save();
    }

    /**
     * Unaccept current object.
     */
    public function unaccept()
    {
        $this->model->accepted = false;

        $this->model->save();
    }

    /**
     * Throws an exception if not accepted
     *
     * @throws NotAcceptedException
     */
    public function failIfNotAccepted()
    {
        if(! $this->model->accepted)

            throw new NotAcceptedException;
    }
}
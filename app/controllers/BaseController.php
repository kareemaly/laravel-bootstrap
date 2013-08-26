<?php

use Illuminate\Support\MessageBag;

class BaseController extends Controller {

    /**
     * @var \Illuminate\Support\MessageBag
     */
    protected $errors = null;

	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	protected function setupLayout()
	{
		if ( ! is_null($this->layout))
		{
			$this->layout = View::make($this->layout);
		}
	}

    /**
     * @return \Illuminate\Support\MessageBag
     */
    public function getErrors()
    {
        return $this->errors ?: $this->errors = new MessageBag();
    }

    /**
     * @param BaseModel $model
     */
    protected function addErrors( BaseModel $model )
    {
        $this->errors = $model->getValidatorMessages()->merge($this->getErrors()->toArray());
    }

    /**
     * @param \BaseModel $model
     * @param array $inputs
     * @param callable $successClosure
     * @return \BaseModel
     */
    protected function createOrAddErrors( BaseModel $model, array $inputs, Closure $successClosure = null )
    {
        $model = $model->create($inputs);

        if(! $model->isValid()) $this->addErrors($model);

        return $model;
    }

    /**
     * Handle user creation or updating If not authenticated
     *
     * @param array $inputs
     * @return \Membership\User\User|null
     */
    protected function handleUserCreation( array $inputs )
    {
        // If the user wasn't authenticated then create or update user with the given input
        if(! Auth::user()) {

            // Creating or updating user if it already exists
            $user = $this->users->createOrUpdate($inputs);

            if($user->isValid()) return $user;

            else $this->addErrors( $user );
        }
    }

    /**
     * @param string $successMessage
     * @param array $jsonAppend
     * @return mixed
     */
    protected function returnJson($successMessage,array $jsonAppend = array())
    {
        if(! $this->getErrors()->isEmpty()) {

            return Response::json(array('message' => 'error', 'body' => $this->getErrors()->all(":message")));
        }

        $successArray = array('message' => 'success', 'body' => (array) $successMessage);

        return Response::json(array_merge($successArray, $jsonAppend));
    }

    /**
     * This method will check if the errors are empty and redirect with success or with errors..
     *
     * @param $successMessage
     * @return mixed
     */
    protected function redirectBack( $successMessage )
    {
        if(! $this->getErrors()->isEmpty())

            return Redirect::back()->with('errors', $this->getErrors()->all(":message"))->withInput();

        return Redirect::back()->with('success', (array) $successMessage);
    }

    /**
     * @param $successMessage
     * @param $jsonAppend
     * @return mixed
     */
    protected function chooseResponse($successMessage, array $jsonAppend = array())
    {
        if(Request::ajax()) return $this->returnJson($successMessage, $jsonAppend);

        else return $this->redirectBack($successMessage);
    }

    /**
     * @param $title
     * @param $description
     * @param $redirect
     * @param $redirectSeconds
     * @param $redirectUrl
     * @return mixed
     */
    protected function messageToUser($title, $description, $redirect = false, $redirectSeconds = 0, $redirectUrl = '')
    {
        $messenger = new Messenger($title, $description, $redirect, $redirectSeconds, $redirectUrl);

        $messenger->flash();

        return Redirect::route('message-to-user');
    }
}
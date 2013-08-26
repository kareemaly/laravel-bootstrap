<?php

use Membership\User\User;
use Website\ContactUs\ContactUs;

class ContactUsController extends BaseController {

    /**
     * @var Website\ContactUs\ContactUs
     */
    protected $contactUs;

    /**
     * @var Membership\User\User
     */
    protected $users;

    /**
     * @param ContactUs $contactUs
     * @param Membership\User\User $users
     */
    public function __construct(ContactUs $contactUs, User $users)
    {
        $this->contactUs = $contactUs;
        $this->users = $users;
    }

    /**
     * @return mixed
     */
    public function index()
    {
        $pageTitle = 'Contact us page';

        return View::make('contact_us.index', compact('pageTitle'));
    }

    /**
     * @return mixed
     */
    public function send()
    {
        // Handle user creation
        if($user = $this->handleUserCreation(Input::get('User')))
        {
            // Creating new instance from contact us and save it to database
            $contactUs = $this->contactUs->newInstance(Input::get('Contact'));

            $user->contacts()->save($contactUs);

            if(! $contactUs->isValid()) $this->addErrors($contactUs);
        }

        // Redirect with either this success message or errors.
        return $this->redirectBack('Message has been sent successfully');
    }
}
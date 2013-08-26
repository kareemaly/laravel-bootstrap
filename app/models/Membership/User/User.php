<?php namespace Membership\User;

use Gallery\Image\Image;
use Helpers\Helper;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;
use Illuminate\Hashing\BcryptHasher;

class User extends \BaseModel implements UserInterface, RemindableInterface {

    /**
     * @var array
     */
    protected $extensions = array('Acceptable');

    /**
     * Users types
     */
    const VISITOR = 0;
    const NORMAL = 1;
    const ADMINISTRATOR = 9;
    const DEVELOPER = 10;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = array('password');

    /**
     * @var array
     */
    protected $guarded = array('id', 'password', 'type');

    /**
     * Force validation of this model
     *
     * @var boolean
     */
    protected $forceValidation = true;

    /**
     * Validation rules
     *
     * @var array
     */
    protected $rules = array(
        'username' => 'min:6',
        'email'    => 'required|email',
        'password' => 'regex:((?=.*\d)(?=.*[a-z]).{8,20})',
        'ip'       => 'required|ip',
        'type'     => 'in:0,1,9,10',
        'website'  => 'url'
    );

    /**
     * @var array
     */
    protected $customMessages = array(
        'password.regex' => 'Password must be from 8 to 20 characters and contain at least 1 digit'
    );

    /**
     * For factoryMuff package to be able to fill the post attributes.
     *
     * @var array
     */
    public static $factory = array(

        'username' => 'kareem3d',
        'email' => 'email',
        'password' => 'kareem123',

    );

    /**
     * @return array
     */
    public static function getTypes()
    {
        return array(
            static::VISITOR => 'visitor',
            static::NORMAL => 'normal',
            static::ADMINISTRATOR => 'administrator',
            static::DEVELOPER => 'developer'
        );
    }

    /**
     * @param array $attributes
     * @return User
     */
    public static function createOrUpdate(array $attributes)
    {
        if(array_key_exists('email', $attributes) && $user = static::getByEmail($attributes['email']))
        {
            $user->update($attributes);

            return $user;
        }

        return static::create($attributes);
    }

    /**
     * @param $email
     * @return User
     */
    public static function getByEmail($email)
    {
        return static::where('email', $email)->first();
    }

    /**
     * Get administrator user. If not found get developer.
     *
     * @return User
     */
    public static function getBoss()
    {
        return static::where('type', self::ADMINISTRATOR)->first() ?:
               static::where('type', self::DEVELOPER)->first();
    }

    /**
     * @return \Illuminate\Hashing\HasherInterface
     */
    public static function getHasher()
    {
        return new BcryptHasher();
    }

    /**
     * @return void
     */
    public function beforeValidate()
    {
        // Clean from XSS attach
        $this->cleanXSS();

        // Update user IP.
        $this->makeIP();
    }

    /**
     * @param string $checkPassword
     * @return bool
     */
    public function checkPassword( $checkPassword )
    {
        return $this->getHasher()->check($checkPassword, $this->password);
    }

    /**
     * @return void
     */
    public function makePassword()
    {
        $this->password = $this->getHasher()->make($this->password);
    }

    /**
     * @return void
     */
    public function beforeSave()
    {
        // If password is dirty which means it did change
        if($this->isDirty('password')) {

            $this->makePassword();
        }
    }

    /**
     * @return void
     */
    public function makeIP()
    {
        $this->ip = Helper::instance()->getCurrentIP();
    }

    /**
     * @return bool
     */
    public function isAdministrator()
    {
        return $this->type == self::ADMINISTRATOR;
    }

    /**
     * @return bool
     */
    public function isDeveloper()
    {
        return $this->type == self::DEVELOPER;
    }

    /**
     * @return bool
     */
    public function isVisitor()
    {
        return $this->type == self::VISITOR;
    }

    /**
     * @return bool
     */
    public function isNormal()
    {
        return $this->type == self::NORMAL;
    }

    /**
     * @return mixed
     */
    public function getTypeString()
    {
        $types = $this->getTypes();

        return ucfirst($types[$this->type]);
    }

    /**
     * @param $name
     */
    public function setName( $name )
    {
        $parts = explode(" ", $name);

        $this->first_name = $parts[0];

        if(count($parts) > 1) $this->last_name = $parts[1];
    }

    /**
     * @return string
     */
    public function getName()
    {
        return ucfirst($this->first_name) . ' ' . ucfirst($this->last_name);
    }

    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->password;
    }

    /**
     * Get the e-mail address where password reminders are sent.
     *
     * @return string
     */
    public function getReminderEmail()
    {
        return $this->email;
    }

    /**
     * @param Image $image
     * @return mixed
     */
    public function setProfileImage( Image $image )
    {
        if($profileImage = $this->getProfileImage())

            $image->override($profileImage);

        return $this->profileImage()->save($image);
    }

    /**
     * @return Image
     */
    public function getProfileImage()
    {
        return $this->profileImage;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function profileImage()
    {
        return $this->morphOne('Gallery\Image\Image', 'imageable');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function images()
    {
        return $this->hasMany('Gallery\Image\Image');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function galleries()
    {
        return $this->hasMany('Gallery\Gallery\Gallery');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function contacts()
    {
        return $this->hasMany('Website\ContactUs\ContactUs');
    }
}
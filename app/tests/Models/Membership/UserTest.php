<?php

namespace Models\Membership;

use Helpers\Helper;
use Membership\User\User;

class UserTest extends \TestCase {

    public function testValidatingUserBeforeSaving()
    {
        $user = $this->factory->instance('Membership\User\User', array('email' => ''));

        $this->assertFalse($user->save());

        // Validate type
        $user->type = 555;

        $this->assertFalse($user->save());
    }

    public function testAcceptedAttributeDefaultToFalse()
    {
        $user = $this->factory->create('Membership\User\User');

        $this->assertFalse((bool)User::find($user->id)->accepted);
    }

    public function testIPIsAutomaticallyUpdated()
    {
        $user = $this->factory->create('Membership\User\User');

        $this->assertEquals($user->ip, Helper::instance()->getCurrentIP());
    }

    public function testAutomaticallyCleaningFromXSSAttack()
    {
        $string = '<div style="background:#2b2b2b">KAreeem</div>';

        $user = $this->factory->create('Membership\User\User', array(
           'username' => $string
        ));

        Helper::instance()->cleanXSS(array($string));

        $this->assertEquals($string, $user->username);
    }


    public function testPasswordAutomaticallyHashed()
    {
        $user = $this->factory->create('Membership\User\User', array(
            'password' => 'kareem123'
        ));

        // Test if password has changed
        $this->assertNotEquals($user->password, 'kareem123');
        $this->assertTrue($user->checkPassword('kareem123'));
    }


    public function testProfileImage()
    {
        $user = $this->factory->create('Membership\User\User');

        // This image is created by another user but attached to this user
        // as his profile image.
        $user->setProfileImage($this->factory->create('Gallery\Image\Image'));

        $this->assertTrue( User::find($user->id)->getProfileImage() instanceof \Gallery\Image\Image );
    }

}
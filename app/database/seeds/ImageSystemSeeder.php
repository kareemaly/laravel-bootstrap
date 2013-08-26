<?php

use Gallery\Group\Group;
use Gallery\GroupSpec\GroupSpec;

class ImageSystemSeeder extends \Illuminate\Database\Seeder {

    public function run()
    {
        DB::table('image_groups')->delete();
        DB::table('image_group_specs')->delete();

        $interventionType = 'Intervention\Image\Image';

        // Users Profile Image Specifications.....
        $userProfile = Group::create(array('name' => 'User.Profile'));

        $userProfile->specs()->create(array(
            'uri' => 'users/profile/user{user}.jpg'
        ))->operations()->create(array(
            'method' => 'grab',
            'args'   => '150,150',
            'type'   => $interventionType
        ));

        $userProfile->specs()->create(array(
            'uri' => 'users/profile/default/user{user}.jpg'
        ));
        ////////////////////////////////////////////////

        $this->command->info("Image System seeded successfully");
    }

}
<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        //let's migrate it later
		Schema::create('users', function(Blueprint $table)
		{
			$table->increments('id');

            $table->string('first_name');
            $table->string('last_name');
            $table->string('username');
            $table->string('email');
            $table->string('password');
            $table->string('ip');
            $table->string('website');

            $table->smallInteger('type')->default(Membership\User\User::VISITOR);

            $table->boolean('accepted')->default(false);

            $table->softDeletes();

			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('users');
	}

}

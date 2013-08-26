<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlacesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('places', function(Blueprint $table)
		{
            $table->engine = 'InnoDB';
            $table->increments('id');

            $table->string('identifier');
            $table->string('name');

            $table->integer('page_id')->unsigned()->nullable();
            $table->foreign('page_id')->references('id')->on('pages')->onDelete('SET NULL');

            $table->integer('content_id')->unsigned()->nullable();
            $table->foreign('content_id')->references('id')->on('contents')->onDelete('SET NULL');

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
        Schema::drop('places');
	}

}
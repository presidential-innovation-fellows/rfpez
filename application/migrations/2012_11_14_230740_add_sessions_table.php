<?php

class Add_Sessions_Table {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('sessions', function($t){
			$t->increments('id');
			$t->integer('last_activity');
			$t->text('data');
		});
	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('sessions');
	}

}
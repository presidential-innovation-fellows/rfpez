<?php

class Add_User_How_Hear {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('users', function($t){
			$t->string('how_hear')->nullable();
		});
	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('users', function($t){
			$t->drop_column('how_hear');
		});
	}

}
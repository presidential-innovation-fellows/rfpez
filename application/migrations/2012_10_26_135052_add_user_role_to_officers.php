<?php

class Add_User_Role_To_Officers {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('officers', function($t){
			$t->integer('role')->default(0);
		});
	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('officers', function($t){
			$t->drop_column('role');
		});
	}

}
<?php

class Add_New_Email_Fields_To_User {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('users', function($t){
			$t->string('new_email');
			$t->string('new_email_confirm_token');
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
			$t->drop_column('new_email');
			$t->drop_column('new_email_confirm_token');
		});
	}

}
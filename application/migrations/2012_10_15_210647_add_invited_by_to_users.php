<?php

class Add_Invited_By_To_Users {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('users', function($t){
			$t->integer('invited_by')->nullable();
      $t->foreign('invited_by')->references('id')->on('users')->on_delete('SET NULL');
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
      $t->drop_foreign('users_invited_by_foreign');
			$t->drop_column('invited_by');
		});
	}

}
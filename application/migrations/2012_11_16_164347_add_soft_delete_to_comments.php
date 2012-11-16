<?php

class Add_Soft_Delete_To_Comments {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('comments', function($t){
			$t->date('deleted_at')->nullable();
		});
	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('comments', function($t){
			$t->drop_column('deleted_at');
		});
	}

}
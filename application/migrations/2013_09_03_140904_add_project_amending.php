<?php

class Add_Project_Amending {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('projects', function($t){
			$t->boolean('amending')->default(0);
		});
	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('projects', function($t){
			$t->drop_column('amending');
		});
	}

}
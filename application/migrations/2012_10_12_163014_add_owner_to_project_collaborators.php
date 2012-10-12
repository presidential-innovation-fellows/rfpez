<?php

class Add_Owner_To_Project_Collaborators {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('project_collaborators', function($t){
			$t->boolean('owner');
		});
	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('project_collaborators', function($t){
			$t->drop_column('owner');
		});
	}

}
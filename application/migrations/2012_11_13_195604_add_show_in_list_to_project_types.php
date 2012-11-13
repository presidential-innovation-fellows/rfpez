<?php

class Add_Show_In_List_To_Project_Types {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('project_types', function($t){
			$t->boolean('show_in_list');
		});
	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('project_types', function($t){
			$t->drop_column('show_in_list');
		});
	}

}
<?php

class Add_Naics_Treshold {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('project_types', function($t){
			$t->decimal('threshshold', 4, 2)->default(7);
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
				$t->drop_column('threshshold');
			});
	}

}
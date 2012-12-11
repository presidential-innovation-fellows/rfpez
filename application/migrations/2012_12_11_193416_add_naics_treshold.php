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
			$t->decimal('threshold', 4, 2)->nullable();
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
				$t->drop_column('threshold');
			});
	}

}
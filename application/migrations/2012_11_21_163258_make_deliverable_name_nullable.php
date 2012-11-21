<?php

class Make_Deliverable_Name_Nullable {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('deliverables', function($t){
			$t->drop_column('name');
		});
		Schema::table('deliverables', function($t){
			$t->string('name')->nullable();
		});
	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('deliverables', function($t){
			$t->drop_column('name');
		});
		Schema::table('deliverables', function($t){
			$t->string('name');
		});
	}

}
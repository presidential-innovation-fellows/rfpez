<?php

class Add_Title_To_Contracts {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('contracts', function($t){
			$t->string('title');
		});
	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('contracts', function($t){
			$t->drop_column('title');
		});
	}

}
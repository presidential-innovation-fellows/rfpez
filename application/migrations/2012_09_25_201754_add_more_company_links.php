<?php

class Add_More_Company_Links {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('vendors', function($t){
			$t->string('homepage_url')->nullable();
			$t->string('sourcecode_url')->nullable();
		});
	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('vendors', function($t){
			$t->drop_column('homepage_url');
			$t->drop_column('sourcecode_url');
		});
	}

}
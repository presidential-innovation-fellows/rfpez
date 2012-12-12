<?php

class Add_Sba_Fields_To_Vendors {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('vendors', function($t){
			$t->boolean('sba_b2');
			$t->boolean('sba_b3');
			$t->boolean('sba_b4i');
			$t->boolean('sba_b5i');
			$t->boolean('sba_b6');
			$t->boolean('sba_b7');
			$t->boolean('sba_b8i');
			$t->string('sba_b9')->nullable();
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
			$t->drop_column('sba_b2');
			$t->drop_column('sba_b3');
			$t->drop_column('sba_b4i');
			$t->drop_column('sba_b5i');
			$t->drop_column('sba_b6');
			$t->drop_column('sba_b7');
			$t->drop_column('sba_b8i');
			$t->drop_column('sba_b9');
		});
	}

}
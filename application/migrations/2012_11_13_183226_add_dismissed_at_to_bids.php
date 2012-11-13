<?php

class Add_Dismissed_At_To_Bids {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('bids', function($t){
			$t->date('dismissed_at')->nullable();
		});
	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('bids', function($t){
			$t->drop_column('dismissed_at');
		});
	}

}
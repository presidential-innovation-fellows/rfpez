<?php

class Change_Bids_To_Soft_Delete {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('bids', function($t){
			$t->drop_column('deleted_by_vendor');
			$t->date('deleted_at')->nullable();
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
			$t->boolean('deleted_by_vendor');
			$t->drop_column('deleted_at');
		});
	}

}
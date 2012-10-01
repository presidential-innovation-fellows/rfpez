<?php

class Rename_Bid_Other_Notes_To_Employees {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('bids', function($t){
			$t->drop_column('other_notes');
			$t->text('employee_details');
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
			$t->drop_column('employee_details');
			$t->text('other_notes');
		});
	}

}
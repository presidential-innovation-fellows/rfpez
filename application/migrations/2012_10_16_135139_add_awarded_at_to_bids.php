<?php

class Add_Awarded_At_To_Bids {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('bids', function($t){
			$t->date('awarded_at')->nullable();
			$t->text('awarded_message');
			$t->integer('awarded_by')->nullable()->unsigned();
      $t->foreign('awarded_by')->references('id')->on('officers')->on_delete('SET NULL');
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
      $t->drop_foreign('bids_awarded_by_foreign');
			$t->drop_column('awarded_at');
			$t->drop_column('awarded_by');
			$t->drop_column('awarded_message');
		});
	}

}
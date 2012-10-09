<?php

class Add_Answered_By_To_Questions {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('questions', function($t){
			$t->integer('answered_by')->nullable();
		});

		Schema::table('questions', function($t){
			$t->foreign('answered_by')->references('id')->on('officers')->on_delete('SET NULL');
		});
	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('questions', function($t){
			$t->drop_foreign('questions_answered_by_foreign');
			$t->drop_column('answered_by');
		});
	}

}
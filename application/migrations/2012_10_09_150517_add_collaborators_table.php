<?php

class Add_Collaborators_Table {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('contract_collaborators', function($t){
			$t->increments('id');
			$t->integer('officer_id');
			$t->integer('contract_id');
			$t->timestamps();
		});
	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('contract_collaborators');
	}

}
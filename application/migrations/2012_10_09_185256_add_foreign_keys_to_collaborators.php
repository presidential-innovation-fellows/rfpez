<?php

class Add_Foreign_Keys_To_Collaborators {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('contract_collaborators', function($t){
			$t->foreign('officer_id')->references('id')->on('officers')->on_delete('CASCADE');
			$t->foreign('contract_id')->references('id')->on('contracts')->on_delete('CASCADE');
		});
	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('contract_collaborators', function($t){
			$t->drop_foreign('contract_collaborators_officer_id_foreign');
			$t->drop_foreign('contract_collaborators_contract_id_foreign');
		});
	}

}
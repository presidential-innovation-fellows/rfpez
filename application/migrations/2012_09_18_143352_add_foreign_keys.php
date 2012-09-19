<?php

class Add_Foreign_Keys {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('vendors', function($t){
			$t->foreign('user_id')->references('id')->on('users')->on_delete('CASCADE');
		});

		Schema::table('officers', function($t){
			$t->foreign('user_id')->references('id')->on('users')->on_delete('CASCADE');
		});

		Schema::table('bids', function($t){
			$t->foreign('vendor_id')->references('id')->on('vendors')->on_delete('CASCADE');
			$t->foreign('contract_id')->references('id')->on('contracts')->on_delete('CASCADE');
		});

		Schema::table('contracts', function($t){
			$t->foreign('officer_id')->references('id')->on('officers')->on_delete('SET NULL');
		});

		Schema::table('questions', function($t){
			$t->foreign('contract_id')->references('id')->on('contracts')->on_delete('CASCADE');
			$t->foreign('vendor_id')->references('id')->on('vendors')->on_delete('CASCADE');
		});

		Schema::table('service_vendor', function($t){
			$t->foreign('service_id')->references('id')->on('services')->on_delete('CASCADE');
			$t->foreign('vendor_id')->references('id')->on('vendors')->on_delete('CASCADE');
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
			$t->drop_foreign('vendors_user_id_foreign');
		});

		Schema::table('officers', function($t){
			$t->drop_foreign('officers_user_id_foreign');
		});

		Schema::table('bids', function($t){
			$t->drop_foreign('bids_vendor_id_foreign');
			$t->drop_foreign('bids_contract_id_foreign');
		});

		Schema::table('contracts', function($t){
			$t->drop_foreign('contracts_officer_id_foreign');
		});

		Schema::table('questions', function($t){
			$t->drop_foreign('questions_vendor_id_foreign');
			$t->drop_foreign('questions_contract_id_foreign');
		});

		Schema::table('service_vendor', function($t){
			$t->drop_foreign('service_vendor_service_id_foreign');
			$t->drop_foreign('service_vendor_vendor_id_foreign');
		});
	}

}
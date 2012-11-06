<?php

class Add_Sort_Order_To_Deliverables {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('deliverables', function($t){
			$t->integer('sort_order');
		});
	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('deliverables', function($t){
			$t->drop_column('sort_order');
		});
	}

}
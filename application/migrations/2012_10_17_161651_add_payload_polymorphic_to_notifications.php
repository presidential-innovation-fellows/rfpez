<?php

class Add_Payload_Polymorphic_To_Notifications {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('notifications', function($t){
			$t->integer('payload_id')->nullable();
			$t->string('payload_type')->nullable();
		});
	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('notifications', function($t){
			$t->drop_column('payload_id');
			$t->drop_column('payload_type');
		});
	}

}
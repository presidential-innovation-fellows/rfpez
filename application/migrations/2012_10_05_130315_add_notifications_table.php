<?php

class Add_Notifications_Table {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('notifications', function($t){
			$t->increments('id');
			$t->integer('target_id');
			$t->integer('actor_id');
			$t->string('notification_type');
			$t->text('payload');
			$t->boolean('read');
			$t->timestamps();
		});

		Schema::table('notifications', function($t){
			$t->foreign('target_id')->references('id')->on('users')->on_delete('CASCADE');
			$t->foreign('actor_id')->references('id')->on('users')->on_delete('CASCADE');
		});
	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('notifications');
	}

}
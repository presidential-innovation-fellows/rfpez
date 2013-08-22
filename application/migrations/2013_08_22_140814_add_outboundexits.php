<?php

class Add_OutboundExits {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('outboundexits', function($t) {
			$t->increments('id');
			$t->integer('user_id')->unsigned()->nullable();
			$t->string('page_url')->nullable();
			$t->string('outbound_url')->nullable();
			$t->date('when')->nullable();
		});

	    Schema::table('outboundexits', function($t){
	      $t->foreign('user_id')->references('id')->on('users')->on_delete('CASCADE');
	    });
	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
	    Schema::table('outboundexits', function($t) {
	      $t->drop_foreign('outboundexits_user_id_foreign');
	    });

	    Schema::drop('outboundexits');
	}

}
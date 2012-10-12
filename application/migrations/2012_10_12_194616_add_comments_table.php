<?php

class Add_Comments_Table {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('comments', function($t){
			$t->increments('id');
			$t->text('body');
			$t->integer('officer_id')->nullable();
			$t->timestamps();
		});

		Schema::table('comments', function($t){
      $t->foreign('officer_id')->references('id')->on('officers')->on_delete('CASCADE');
		});
	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('comments', function($t){
			$t->drop_foreign('comments_officer_id_foreign');
		});

		Schema::drop('comments');
	}

}
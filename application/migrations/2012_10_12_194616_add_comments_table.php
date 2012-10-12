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
			$t->integer('project_id');
			$t->integer('officer_id')->nullable();
			$t->text('body');
			$t->timestamps();
		});

		Schema::table('comments', function($t){
      $t->foreign('project_id')->references('id')->on('projects')->on_delete('CASCADE');
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
			$t->drop_foreign('comments_project_id_foreign');
			$t->drop_foreign('comments_officer_id_foreign');
		});

		Schema::drop('comments');
	}

}
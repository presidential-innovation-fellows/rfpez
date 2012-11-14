<?php

class Add_Deliverables_Table {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('projects', function($t){
			$t->drop_column('deliverables');
		});

		Schema::create('deliverables', function($t){
			$t->increments('id');
			$t->integer('project_id');
			$t->string('name');
			$t->string('date')->nullable();
			$t->string('length')->nullable();
			$t->timestamps();

      $t->foreign('project_id')->references('id')->on('projects')->on_delete('CASCADE');
		});
	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('projects', function($t){
			$t->text('deliverables');
		});

		Schema::drop('deliverables');
	}

}
<?php

class Remove_Dependency_On_Fbo {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('projects', function($t){
			$t->drop_column('fbo_solnbr');
			$t->date('posted_to_fbo_at')->nullable();
		});

		Schema::table('officers', function($t){
			$t->drop_column('verified_at');
			$t->drop_column('verified_solnbr');
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
			$t->drop_column('posted_to_fbo_at');
			$t->string('fbo_solnbr')->nullable();
		});

		Schema::table('officers', function($t){
			$t->date('verified_at')->nullable();
			$t->string('verified_solnbr')->nullable();
		});
	}

}
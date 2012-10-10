<?php

class Sow_Collaborators {

	public function up()
	{
		Schema::create('sow_collaborators', function($t){
			$t->increments('id');
			$t->integer('officer_id');
			$t->integer('sow_id');
			$t->timestamps();
		});

		Schema::table('sow_collaborators', function($t){
			$t->foreign('officer_id')->references('id')->on('officers')->on_delete('CASCADE');
			$t->foreign('sow_id')->references('id')->on('sows')->on_delete('CASCADE');
		});

	}

	public function down()
	{
		Schema::drop('sow_collaborators');
	}

}
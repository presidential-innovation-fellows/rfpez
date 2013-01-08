<?php

class Remove_Sow_Composer {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::drop('project_section_type');
		Schema::drop('project_sections');
		Schema::drop('deliverables');

		Schema::table('projects', function($t){
			$t->drop_column('background');
			$t->drop_column('sections');
			$t->drop_column('variables');
			$t->drop_column('sow_progress');

			$t->text('body');
			$t->string('etherpad_id');
		});
	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		throw new \Exception("Can't migrate down.");
	}

}
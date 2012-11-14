<?php

class Add_Public_And_Creating_Project_Id_To_Sections {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('project_sections', function($t){
			$t->integer('created_by_project_id')->nullable()->unsigned();
			$t->boolean('public')->default(0);

      $t->foreign('created_by_project_id')->references('id')->on('projects')->on_delete('SET NULL');
		});
	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('project_sections', function($t){
      $t->drop_foreign('project_sections_created_by_project_id_foreign');

			$t->drop_column('created_by_project_id');
			$t->drop_column('public');
		});
	}

}
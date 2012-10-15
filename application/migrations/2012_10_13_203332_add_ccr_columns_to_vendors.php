<?php

class Add_Ccr_Columns_To_Vendors {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('vendors', function($t){
      $t->string('duns')->nullable();
      $t->string('sam_entity_name')->nullable();
      $t->string('dsbs_name')->nullable();
      $t->string('dsbs_user_id')->nullable();
    });
	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('vendors', function($t){
      $t->drop_column('duns');
      $t->drop_column('sam_entity_name');
      $t->drop_column('dsbs_name');
      $t->drop_column('dsbs_user_id');
    });
	}

}
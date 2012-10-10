<?php

class Add_Sow_Composer_Tables {

  /**
   * Make changes to the database.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('sow_templates', function($table) {
      $table->increments('id');
      $table->string('title');
      $table->text('variables');
      $table->boolean("visible")->default(1);
      $table->timestamps();
    });

    Schema::create('sow_template_sections', function($table) {
      $table->increments('id');
      $table->integer('sow_template_id');
      $table->integer('display_order');
      $table->string('section_type');
      $table->string('help_text');
      $table->string('title');
      $table->text('body');
      $table->timestamps();
    });

    Schema::create('sows', function($table) {
      $table->increments('id');
      $table->integer('officer_id')->nullable();
      $table->integer('based_on_sow_template_id')->nullable();
      $table->string('title');
      $table->text('body');
      $table->text('variables'); // {Website Url: "energy.gov", Name: "Energy"}]
      $table->timestamps();
    });

    Schema::create('sow_sections', function($table) {
      $table->increments('id');
      $table->integer('sow_id');
      $table->integer('based_on_sow_template_section_id')->nullable();
      $table->integer('display_order');
      $table->string('section_type');
      $table->string('title');
      $table->text('body');
      $table->timestamps();
    });

    Schema::table('sows', function($table){
      $table->foreign('officer_id')->references('id')->on('officers')->on_delete('SET NULL');
      $table->foreign('based_on_sow_template_id')->references('id')->on('sow_templates')->on_delete('SET NULL');
    });

    Schema::table('sow_sections', function($table){
      $table->foreign('sow_id')->references('id')->on('sows')->on_delete('cascade');
      $table->foreign('based_on_sow_template_section_id')->references('id')->on('sow_template_sections')->on_delete('SET NULL');
    });

    Schema::table('sow_template_sections', function($table){
      $table->foreign('sow_template_id')->references('id')->on('sow_templates')->on_delete('cascade');
    });


  }

  /**
   * Revert the changes to the database.
   *
   * @return void
   */
  public function down()
  {
    Schema::drop('sow_templates');
    Schema::drop('sow_template_sections');
    Schema::drop('sow_sections');
    Schema::drop('sows');
  }

}
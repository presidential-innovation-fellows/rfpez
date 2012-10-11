<?php

class Initial_Schema {

  /**
   * Make changes to the database.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('users', function($t){
      $t->increments('id');
      $t->string('email');

      // Taking a lot of inspiration from the Devise Rails gem.
      // This stuff should be pretty easy to implement, and is
      // hopefully a good enough security practice for starters,
      // as opposed to tracking each login and each password reset request.
      //
      // When the officer signs up, encrypted_password is null.
      // She is then sent an email with a link to confirm her account,
      // which then lets her create a password. The page is actually
      // the "reset password" page, but since we know that she's never
      // had a password (the sign_in_count is 0), we can make it appear
      // differently.
      $t->string('encrypted_password')->nullable();
      $t->string('reset_password_token')->nullable();
      $t->date('reset_password_sent_at')->nullable();
      $t->integer('sign_in_count');
      $t->date('current_sign_in_at')->nullable();
      $t->date('last_sign_in_at')->nullable();
      $t->string('current_sign_in_ip')->nullable();
      $t->string('last_sign_in_ip')->nullable();

      $t->string('new_email');
      $t->string('new_email_confirm_token');

      $t->timestamps();
    });

    Schema::create('bids', function($t){
      $t->increments('id');
      $t->integer('vendor_id');
      $t->integer('project_id');
      $t->text('approach');
      $t->text('previous_work');
      $t->text('employee_details');

      // Serialized prices (in USD) for each deliverable.
      // ex: { "Web Design": 320.01, "Information Architecture": 1293.50 }
      $t->text('prices');

      // Has the officer starred this bid for a potential follow-up?
      $t->boolean('starred');

      // Reason for dismissing the bid.
      // If null, bid has not been dismissed.
      $t->string('dismissal_reason')->nullable();
      $t->text('dismissal_explanation')->nullable();

      $t->date('submitted_at')->nullable();
      $t->boolean('deleted_by_vendor');

      $t->timestamps();
    });

    Schema::create('projects', function($t){
      $t->increments('id');
      $t->string('title');
      $t->text('body');
      $t->string('fbo_solnbr');
      $t->string('agency');
      $t->string('office');
      $t->integer('naics_code');
      $t->date('proposals_due_at');

      $t->timestamps();
    });

    Schema::create('questions', function($t){
      $t->increments('id');
      $t->integer('project_id');
      $t->integer('vendor_id');
      $t->text('question');
      $t->text('answer')->nullable();
      $t->integer('answered_by')->nullable();

      $t->timestamps();
    });

    Schema::create('vendors', function($t){
      $t->increments('id');
      $t->integer('user_id');
      $t->string('company_name');
      $t->string('contact_name');

      $t->string('address');
      $t->string('city');
      $t->string('state');
      $t->string('zip');
      $t->decimal('latitude', 17, 14);
      $t->decimal('longitude', 17, 14);

      // Ballpark price range. Will be a class constant,
      // so 1 = "$10,000 - $20,000", 2 = "$20,000 - $50,000", etc.
      $t->integer('ballpark_price');

      $t->text('more_info')->nullable();
      $t->string('homepage_url');
      $t->string('image_url');
      $t->string('portfolio_url')->nullable();
      $t->string('sourcecode_url')->nullable();

      // @todo
      // We'll also need some way to identify the
      // vendors that have registered on SAM.gov.

      $t->timestamps();
    });

    Schema::create('services', function($t){
      $t->increments('id');
      $t->string('name');
      $t->string('description');

      $t->timestamps();
    });

    // Many-to-many relationship for the services
    // that a vendor offers.
    Schema::create('service_vendor', function($t){
      $t->increments('id');
      $t->integer('service_id');
      $t->integer('vendor_id');

      $t->timestamps();
    });

    Schema::create('officers', function($t){
      $t->increments('id');
      $t->integer('user_id');
      $t->string('phone');
      $t->string('fax');
      $t->string('name');
      $t->string('title');
      $t->string('agency');

      // The date and SOLNBR from FBO that allowed
      // us to verify this officer.
      //
      // If null, officer is not verified.
      $t->date('verified_at')->nullable();
      $t->string('verified_solnbr')->nullable();


      $t->timestamps();
    });

    Schema::create('notifications', function($t){
      $t->increments('id');
      $t->integer('target_id');
      $t->integer('actor_id')->nullable();
      $t->string('notification_type');
      $t->text('payload');
      $t->boolean('read');
      $t->timestamps();
    });

    Schema::create('project_collaborators', function($t){
      $t->increments('id');
      $t->integer('officer_id');
      $t->integer('project_id');
      $t->timestamps();
    });

    Schema::create('sows', function($t) {
      $t->increments('id');
      $t->integer('project_id');
      $t->integer('based_on_sow_template_id')->nullable();
      $t->string('title');
      $t->text('body');
      $t->text('variables'); // {Website Url: "energy.gov", Name: "Energy"}]
      $t->timestamps();
    });

    Schema::create('sow_sections', function($t) {
      $t->increments('id');
      $t->integer('sow_id');
      $t->integer('based_on_sow_template_section_id')->nullable();
      $t->integer('display_order');
      $t->string('section_type');
      $t->string('title');
      $t->text('body');
      $t->timestamps();
    });

    Schema::create('sow_templates', function($t) {
      $t->increments('id');
      $t->string('title');
      $t->text('variables');
      $t->boolean('visible')->default(1);
      $t->timestamps();
    });

    Schema::create('sow_template_sections', function($t) {
      $t->increments('id');
      $t->integer('sow_template_id');
      $t->integer('display_order');
      $t->string('section_type');
      $t->string('help_text');
      $t->string('title');
      $t->text('body');
      $t->timestamps();
    });

    Schema::table('sows', function($t){
      $t->foreign('project_id')->references('id')->on('projects')->on_delete('CASCADE');
      $t->foreign('based_on_sow_template_id')->references('id')->on('sow_templates')->on_delete('SET NULL');
    });

    Schema::table('sow_sections', function($t){
      $t->foreign('sow_id')->references('id')->on('sows')->on_delete('cascade');
      $t->foreign('based_on_sow_template_section_id')->references('id')->on('sow_template_sections')->on_delete('SET NULL');
    });

    Schema::table('sow_template_sections', function($t){
      $t->foreign('sow_template_id')->references('id')->on('sow_templates')->on_delete('cascade');
    });

    Schema::table('project_collaborators', function($t){
      $t->foreign('officer_id')->references('id')->on('officers')->on_delete('CASCADE');
      $t->foreign('project_id')->references('id')->on('projects')->on_delete('CASCADE');
    });

    Schema::table('notifications', function($t){
      $t->foreign('target_id')->references('id')->on('users')->on_delete('CASCADE');
      $t->foreign('actor_id')->references('id')->on('users')->on_delete('CASCADE');
    });

    Schema::table('vendors', function($t){
      $t->foreign('user_id')->references('id')->on('users')->on_delete('CASCADE');
    });

    Schema::table('officers', function($t){
      $t->foreign('user_id')->references('id')->on('users')->on_delete('CASCADE');
    });

    Schema::table('bids', function($t){
      $t->foreign('vendor_id')->references('id')->on('vendors')->on_delete('CASCADE');
      $t->foreign('project_id')->references('id')->on('projects')->on_delete('CASCADE');
    });

    Schema::table('questions', function($t){
      $t->foreign('project_id')->references('id')->on('projects')->on_delete('CASCADE');
      $t->foreign('vendor_id')->references('id')->on('vendors')->on_delete('CASCADE');
      $t->foreign('answered_by')->references('id')->on('officers')->on_delete('SET NULL');
    });

    Schema::table('service_vendor', function($t){
      $t->foreign('service_id')->references('id')->on('services')->on_delete('CASCADE');
      $t->foreign('vendor_id')->references('id')->on('vendors')->on_delete('CASCADE');
    });

  }

  /**
   * Revert the changes to the database.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('sows', function($t){
      $t->drop_foreign('sows_project_id_foreign');
      $t->drop_foreign('sows_based_on_sow_template_id_foreign');
    });

    Schema::table('sow_sections', function($t){
      $t->drop_foreign('sow_sections_sow_id_foreign');
      $t->drop_foreign('sow_sections_based_on_sow_template_section_id_foreign');
    });

    Schema::table('sow_template_sections', function($t){
      $t->drop_foreign('sow_template_sections_sow_template_id_foreign');
    });

    Schema::table('project_collaborators', function($t){
      $t->drop_foreign('project_collaborators_officer_id_foreign');
      $t->drop_foreign('project_collaborators_project_id_foreign');
    });

    Schema::table('notifications', function($t){
      $t->drop_foreign('notifications_target_id_foreign');
      $t->drop_foreign('notifications_actor_id_foreign');
    });

    Schema::table('vendors', function($t){
      $t->drop_foreign('vendors_user_id_foreign');
    });

    Schema::table('officers', function($t){
      $t->drop_foreign('officers_user_id_foreign');
    });

    Schema::table('bids', function($t){
      $t->drop_foreign('bids_vendor_id_foreign');
      $t->drop_foreign('bids_project_id_foreign');
    });

    Schema::table('questions', function($t){
      $t->drop_foreign('questions_project_id_foreign');
      $t->drop_foreign('questions_vendor_id_foreign');
      $t->drop_foreign('questions_answered_by_foreign');
    });

    Schema::table('service_vendor', function($t){
      $t->drop_foreign('service_vendor_service_id_foreign');
      $t->drop_foreign('service_vendor_vendor_id_foreign');
    });

    Schema::drop('bids');
    Schema::drop('questions');
    Schema::drop('notifications');
    Schema::drop('vendors');
    Schema::drop('services');
    Schema::drop('service_vendor');
    Schema::drop('officers');
    Schema::drop('users');
    Schema::drop('sows');
    Schema::drop('sow_sections');
    Schema::drop('sow_templates');
    Schema::drop('sow_template_sections');
    Schema::drop('projects');
    Schema::drop('project_collaborators');
  }

}
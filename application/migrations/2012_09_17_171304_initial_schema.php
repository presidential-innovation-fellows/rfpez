<?php

class Initial_Schema {

  /**
   * Make changes to the database.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('bids', function($t){
      $t->increments('id');
      $t->integer('vendor_id');
      $t->integer('contract_id');
      $t->text('approach');
      $t->text('previous_work');
      $t->text('other_notes');

      // Serialized prices (in USD) for each deliverable.
      // ex: { "Web Design": 320.01, "Information Architecture": 1293.50 }
      $t->text('prices');

      // Has the officer starred this bid for a potential follow-up?
      $t->boolean('starred');

      // Reason for dismissing the bid.
      // If null, bid has not been dismissed.
      $t->string('dismissal_reason')->nullable();
      $t->text('dismissal_explanation')->nullable();

      $t->timestamps();
    });

    Schema::create('contracts', function($t){
      $t->increments('id');
      $t->integer('officer_id');
      $t->string('fbo_solnbr');
      $t->string('agency');
      $t->string('office');
      $t->text('statement_of_work');
      $t->string('set_aside')->nullable();
      $t->string('classification_code')->nullable();
      $t->integer('naics_code');
      $t->timestamp('proposals_due_at');
      $t->timestamp('posted_at');

      $t->timestamps();
    });

    Schema::create('questions', function($t){
      $t->increments('id');
      $t->integer('contract_id');
      $t->integer('vendor_id');
      $t->text('question');
      $t->text('answer')->nullable();

      $t->timestamps();
    });

    Schema::create('vendors', function($t){
      $t->increments('id');
      $t->string('company_name');
      $t->string('contact_name');
      $t->string('email');

      // Taking a lot of inspiration from the Devise Rails gem.
      // This stuff should be pretty easy to implement, and is
      // hopefully a good enough security practice for starters,
      // as opposed to tracking each login and each password reset request.
      $t->string('encrypted_password');
      $t->string('reset_password_token')->nullable();
      $t->timestamp('reset_password_sent_at')->nullable();
      $t->integer('sign_in_count');
      $t->timestamp('current_sign_in_at')->nullable();
      $t->timestamp('last_sign_in_at')->nullable();
      $t->string('current_sign_in_ip')->nullable();
      $t->string('last_sign_in_ip')->nullable();

      $t->string('address');
      $t->string('city');
      $t->string('state');
      $t->string('zip');
      $t->decimal('latitude', 17, 14);
      $t->decimal('longitude', 17, 14);

      // Ballpark price range. Will be a class constant,
      // so 1 = "$10,000 - $20,000", 2 = "$20,000 - $50,000", etc.
      $t->integer('ballpark_price');

      $t->string('portfolio_url')->nullable();
      $t->text('more_info')->nullable();

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
      $t->integer('service_id');
      $t->integer('vendor_id');

      $t->timestamps();
    });

    Schema::create('officers', function($t){
      $t->increments('id');
      $t->string('email');
      $t->string('phone');
      $t->string('fax');
      $t->string('name');
      $t->string('title');
      $t->string('agency');

      // If true, grants site admin powers to this officer.
      $t->boolean('is_admin');

      // The date and SOLNBR from FBO that allowed
      // us to verify this officer.
      //
      // If null, officer is not verified.
      $t->timestamp('verified_at')->nullable();
      $t->string('verified_solnbr')->nullable();

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
      $t->timestamp('reset_password_sent_at')->nullable();
      $t->integer('sign_in_count');
      $t->timestamp('current_sign_in_at')->nullable();
      $t->timestamp('last_sign_in_at')->nullable();
      $t->string('current_sign_in_ip')->nullable();
      $t->string('last_sign_in_ip')->nullable();

      $t->timestamps();
    });
  }

  /**
   * Revert the changes to the database.
   *
   * @return void
   */
  public function down()
  {
    Schema::drop('bids');
    Schema::drop('contracts');
    Schema::drop('questions');
    Schema::drop('vendors');
    Schema::drop('services');
    Schema::drop('service_vendor');
    Schema::drop('officers');
  }

}
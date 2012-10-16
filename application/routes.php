<?php

Route::get('/', array('uses' => 'home@index', 'as' => 'root'));

Route::get('validation/email', array('uses' => 'validation@email', 'as' => 'validation_email'));

Route::get('signin', array('uses' => 'auth@new', 'as' => 'signin'));
Route::post('signin', array('uses' => 'auth@create', 'as' => 'signin'));

Route::get('signout', array('uses' => 'auth@delete', 'as' => 'signout'));

Route::get('account', array('uses' => 'users@get_account', 'as' => 'account'));
Route::post('account', array('uses' => 'users@post_account', 'as' => 'account'));

Route::get('account/email', array('uses' => 'users@get_change_email', 'as' => 'change_email'));
Route::post('account/email', array('uses' => 'users@post_change_email', 'as' => 'change_email'));
Route::get('account/password', array('uses' => 'users@get_change_password', 'as' => 'change_password'));
Route::post('account/password', array('uses' => 'users@post_change_password', 'as' => 'change_password'));
Route::get('account/confirmnewemail/(:any)', array('uses' => 'users@confirm_new_email', 'as' => 'confirm_new_email'));
Route::put('account/viewnotifications/(:any)/(:any)', array('uses' => 'users@view_notification_payload', 'as' => 'view_notification_payload'));

Route::get('forgotpassword', array('uses' => 'users@get_forgot_password', 'as' => 'forgot_password'));
Route::post('forgotpassword', array('uses' => 'users@post_forgot_password', 'as' => 'forgot_password'));

Route::get('finishsignup/(:any)', array('uses' => 'users@get_reset_password', 'as' => 'finish_signup'));
Route::get('resetpassword/(:any)', array('uses' => 'users@get_reset_password', 'as' => 'reset_password'));
Route::post('resetpassword/(:any)', array('uses' => 'users@post_reset_password', 'as' => 'reset_password'));

Route::resourceful('vendors', array('new', 'create', 'index', 'show'));

Route::resourceful('officers', array('new', 'create'));
Route::get('officers/typeahead', array('uses' => 'officers@typeahead', 'as' => 'officers_typeahead'));

Route::resourceful('questions', array('create', 'update'));

Route::resourceful('notifications', array('index'));
Route::get('notifications/json', array('uses' => 'notifications@json', 'as' => 'notifications_json'));
Route::put('notifications/(:num)/markasread', array('uses' => 'notifications@mark_as_read', 'as' => 'notification_mark_as_read'));

Route::get('bids/mine', array('uses' => 'bids@mine', 'as' => 'my_bids'));

////////

Route::resourceful('projects', array('new', 'create', 'edit', 'update', 'index', 'show'));

Route::get('projects/mine', array('uses' => 'projects@mine', 'as' => 'my_projects'));

Route::get('projects/(:num)/admin', array('uses' => 'projects@admin', 'as' => 'project_admin'));

Route::post('projects/(:num)/collaborators', array('uses' => 'projects@add_collaborator', 'as' => 'project_collaborators'));
Route::delete('projects/(:num)/collaborators/(:num)', array('uses' => 'projects@destroy_collaborator', 'as' => 'project_collaborators_destroy'));

Route::get('projects/(:num)/bids', array('uses' => 'bids@review', 'as' => 'review_bids'));

Route::get('projects/(:num)/postonfbo', array('uses' => 'projects@post_on_fbo', 'as' => 'project_post_on_fbo'));
Route::post('projects/(:num)/postonfbo', array('uses' => 'projects@post_on_fbo_post', 'as' => 'project_post_on_fbo'));

Route::get('projects/(:num)/comments', array('uses' => 'comments@index', 'as' => 'comments'));
Route::post('projects/(:num)/comments', array('uses' => 'comments@create', 'as' => 'comments'));
Route::get('projects/(:num)/comments/(:num)/destroy', array('uses' => 'comments@destroy', 'as' => 'comment_destroy'));

Route::get('projects/(:num)/bids/new', array('uses' => 'bids@new', 'as' => 'new_bids'));
Route::post('projects/(:num)/bids', array('uses' => 'bids@create', 'as' => 'bids'));
Route::get('projects/(:num)/bids/(:num)', array('uses' => 'bids@show', 'as' => 'bid'));
Route::get('projects/(:num)/bids/(:num)/dismiss', array('uses' => 'bids@dismiss', 'as' => 'bid_dismiss'));
Route::get('projects/(:num)/bids/(:num)/award', array('uses' => 'bids@award', 'as' => 'bid_award'));
Route::get('projects/(:num)/bids/(:num)/star', array('uses' => 'bids@star', 'as' => 'bid_star'));
Route::get('projects/(:num)/bids/(:num)/destroy', array('uses' => 'bids@destroy', 'as' => 'bid_destroy'));
// Route::get('contracts/(:num)/bids/(:num)/sf1449.pdf', array('uses' => 'bids@sf1449', 'as' => 'bid_sf1449'));

/*
|--------------------------------------------------------------------------
| SOW Composer
|--------------------------------------------------------------------------
*/

Route::get('projects/(:num)/sow/background', array('uses' => 'sows@background', 'as' => 'sow_background'));
Route::post('projects/(:num)/sow/background', array('uses' => 'sows@background_post', 'as' => 'sow_background'));

Route::get('projects/(:num)/sow/sections/(:all)', array('uses' => 'sows@section', 'as' => 'sow_section'));
Route::post('projects/(:num)/sow/sections/(:all)', array('uses' => 'sows@section_post', 'as' => 'sow_section_post'));

Route::get('projects/(:num)/sow/fillinblanks', array('uses' => 'sows@fillinblanks', 'as' => 'sow_fillinblanks'));
Route::post('projects/(:num)/sow/fillinblanks', array('uses' => 'sows@fillinblanks_post', 'as' => 'sow_fillinblanks'));

Route::get('projects/(:num)/sow/editdoc', array('uses' => 'sows@editdoc', 'as' => 'sow_editdoc'));
Route::post('projects/(:num)/sow/editdoc', array('uses' => 'sows@editdoc_post', 'as' => 'sow_editdoc'));

Route::get('projects/(:num)/sow/review', array('uses' => 'sows@review', 'as' => 'sow_review'));
// Route::get('sows/(:num)/doc', array('uses' => 'sows@doc', 'as' => 'sow_doc'));
// Route::get('sows/(:num)/print', array('uses' => 'sows@print', 'as' => 'sow_print'));
// Route::get('sows/(:num)/view', array('uses' => 'sows@view', 'as' => 'sow_view'));

/*
|--------------------------------------------------------------------------
| Application 404 & 500 Error Handlers
|--------------------------------------------------------------------------
|
| To centralize and simplify 404 handling, Laravel uses an awesome event
| system to retrieve the response. Feel free to modify this function to
| your tastes and the needs of your application.
|
| Similarly, we use an event to handle the display of 500 level errors
| within the application. These errors are fired when there is an
| uncaught exception thrown in the application.
|
*/

Event::listen('404', function()
{
  return Response::error('404');
});

Event::listen('500', function()
{
  return Response::error('500');
});

/*
|--------------------------------------------------------------------------
| Route Filters
|--------------------------------------------------------------------------
|
| Filters provide a convenient method for attaching functionality to your
| routes. The built-in before and after filters are called before and
| after every request to your application, and you may even create
| other filters that can be attached to individual routes.
|
| Let's walk through an example...
|
| First, define a filter:
|
|   Route::filter('filter', function()
|   {
|     return 'Filtered!';
|   });
|
| Next, attach the filter to a route:
|
|   Router::register('GET /', array('before' => 'filter', function()
|   {
|     return 'Hello World!';
|   }));
|
*/

Route::filter('before', function() {
  // Do stuff before every request to your application...
});

Route::filter('after', function($response) {
  // Do stuff after every request to your application...
});

Route::filter('csrf', function() {
  if (Request::forged()) return Response::error('500');
});

Route::filter('auth', function() {
  if (Auth::guest()) return Redirect::to('signin');
});

Route::filter('no_auth', function() {
  if (Auth::user()) return Redirect::to('/');
});

Route::filter('vendor_only', function() {
  if (Auth::guest()) return Redirect::to('signin');
  if (!Auth::user()->vendor) return Redirect::to('/');
});

Route::filter('officer_only', function() {
  if (Auth::guest()) return Redirect::to('signin');
  if (!Auth::user()->officer) return Redirect::to('/');
});
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

Route::get('projects/(:num)/search-available-sections', array('uses' => 'projects@search_available_sections', 'as' => 'project_search_available_sections'));

Route::get('projects/(:num)/more-templates', array('uses' => 'projects@more_templates', 'as' => 'project_more_templates'));

Route::get('projects/(:num)/admin', array('uses' => 'projects@admin', 'as' => 'project_admin'));

Route::get('projects/(:num)/collaborators', array('uses' => 'projects@get_collaborators'));


Route::post('projects/(:num)/collaborators', array('uses' => 'projects@add_collaborator', 'as' => 'project_collaborators'));
Route::delete('projects/(:num)/collaborators/(:num)', array('uses' => 'projects@destroy_collaborator', 'as' => 'project_collaborators_destroy'));

Route::get('projects/(:num)/bids', array('uses' => 'bids@review', 'as' => 'review_bids'));

Route::get('projects/(:num)/postonfbo', array('uses' => 'projects@post_on_fbo', 'as' => 'project_post_on_fbo'));
Route::post('projects/(:num)/postonfbo', array('uses' => 'projects@post_on_fbo_post', 'as' => 'project_post_on_fbo'));

Route::get('projects/(:num)/comments', array('uses' => 'comments@index', 'as' => 'comments'));
Route::post('projects/(:num)/comments', array('uses' => 'comments@create', 'as' => 'comments'));
Route::delete('projects/(:num)/comments/(:num)', array('uses' => 'comments@destroy', 'as' => 'comment_destroy'));

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

Route::get('projects/(:num)/template', array('uses' => 'projects@template', 'as' => 'project_template'));
Route::get('projects/(:num)/template/(:num)', array('uses' => 'projects@template_post', 'as' => 'project_template_post'));

Route::get('projects/(:num)/background', array('uses' => 'projects@background', 'as' => 'project_background'));
Route::post('projects/(:num)/background', array('uses' => 'projects@background_post', 'as' => 'project_background'));

Route::get('projects/(:num)/sections', array('uses' => 'projects@sections', 'as' => 'project_sections'));
Route::post('projects/(:num)/sections', array('uses' => 'projects@sections_post', 'as' => 'project_sections'));

Route::post('projects/(:num)/sections/(:num)', array('uses' => 'projects@sections_add', 'as' => 'project_section_add'));
Route::delete('projects/(:num)/sections/(:num)', array('uses' => 'projects@sections_remove', 'as' => 'project_section_delete'));

Route::post('projects/(:num)/sections/reorder', array('uses' => 'projects@sections_reorder', 'as' => 'project_sections_reorder'));

Route::get('projects/(:num)/blanks', array('uses' => 'projects@blanks', 'as' => 'project_blanks'));
Route::post('projects/(:num)/blanks', array('uses' => 'projects@blanks_post', 'as' => 'project_blanks'));

Route::get('projects/(:num)/timeline', array('uses' => 'projects@timeline', 'as' => 'project_timeline'));
Route::post('projects/(:num)/timeline', array('uses' => 'projects@timeline_post', 'as' => 'project_timeline'));

Route::get('projects/(:num)/review', array('uses' => 'projects@review', 'as' => 'project_review'));

Route::post('projects/(:num)/toggle-public', array('uses' => 'projects@toggle_public', 'as' => 'project_toggle_public'));

/*
|--------------------------------------------------------------------------
| Admin Section
|--------------------------------------------------------------------------
*/

Route::get('admin', array('uses' => 'admin@index', 'as' => 'admin_index'));

Route::get('admin/project-sections', array('uses' => 'admin@project_sections', 'as' => 'admin_project_sections'));
Route::get('admin/project-sections/(:num)/toggle-public', array('uses' => 'admin@project_sections_toggle_public', 'as' => 'admin_project_sections_toggle_public'));
Route::get('admin/officers', array('uses' => 'admin@officers', 'as' => 'admin_officers'));
Route::get('admin/verify-contracting-officer/(:num)', array('uses' => 'admin@verify_contracting_officer', 'as' => 'admin_verify_contracting_officer'));
Route::get('admin/templates', array('uses' => 'admin@templates', 'as' => 'admin_templates'));
Route::post('admin/templates/(:num)/toggle-recommended', array('uses' => 'admin@template_toggle_recommended', 'as' => 'admin_template_toggle_recommended'));



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
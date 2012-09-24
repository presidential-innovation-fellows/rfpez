<?php

Route::get('/', array('uses' => 'home@index', 'as' => 'root'));

Route::get('signin', array('uses' => 'auth@new', 'as' => 'signin'));
Route::post('signin', array('uses' => 'auth@create', 'as' => 'signin'));

Route::get('signout', array('uses' => 'auth@delete', 'as' => 'signout'));

Route::get('forgotpassword', array('uses' => 'users@get_forgot_password', 'as' => 'forgot_password'));
Route::post('forgotpassword', array('uses' => 'users@post_forgot_password', 'as' => 'forgot_password'));

Route::get('finishsignup/(:any)', array('uses' => 'users@get_reset_password', 'as' => 'finish_signup'));
Route::get('resetpassword/(:any)', array('uses' => 'users@get_reset_password', 'as' => 'reset_password'));
Route::post('resetpassword/(:any)', array('uses' => 'users@post_reset_password', 'as' => 'reset_password'));


Route::resourceful('vendors', array('new', 'create'));
Route::resourceful('officers', array('new', 'create'));
Route::resourceful('contracts', array('new', 'create', 'edit', 'update', 'index', 'show'));

Route::get('contracts/(:num)/bids/new', array('uses' => 'bids@new', 'as' => 'new_bids'));
Route::post('contracts/(:num)/bids', array('uses' => 'bids@create', 'as' => 'bids'));


/*
|--------------------------------------------------------------------------
| Asset Definitions
|--------------------------------------------------------------------------
*/

Bundle::start('basset');

if (Config::get('basset')) Basset\Config::extend(Config::get('basset'));

Basset::scripts('website', function($basset)
{
  $basset->add('main', 'main.js');
  //        ->add('sisyphus', 'sisyphus.min.js')
  //        ->add('app', 'app.js', 'pjax');
});

Basset::styles('website', function($basset)
{
  $basset->add('main', 'main.css');
});


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
  if (Auth::guest()) return Redirect::to('/');
  if (!Auth::user()->is_vendor()) return Redirect::to('/');
});

Route::filter('officer_only', function() {
  if (Auth::guest()) return Redirect::to('/');
  if (!Auth::user()->is_officer()) return Redirect::to('/');
});
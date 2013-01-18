<?php

class Vendors_Controller extends Base_Controller {

  public function __construct() {
    parent::__construct();

    $this->filter('before', 'no_auth')->only(array('new', 'create'));
    $this->filter('before', 'officer_only')->only(array('index', 'show'));
    $this->filter('before', 'vendor_exists')->only(array('show'));
  }

  public function action_new() {
    $view = View::make('vendors.new');
    $this->layout->content = $view;
  }

  public function action_create() {
    $user_input = Input::get('user');
    $user = new User;
    $user->email = $user_input["email"];
    $user->password = $user_input["password"];
    $user->how_hear = $user_input["how_hear"];
    $user->send_emails = isset($user_input["send_emails"]) ? true : false;

    $vendor = new Vendor(Input::get('vendor'));

    if ($user->validator()->passes() && $vendor->validator()->passes()) {
      $user->save();
      $vendor->user_id = $user->id;
      $vendor->save();
      $services = Input::get('services') ? array_keys(Input::get('services')) : array();
      $user->vendor->services()->sync($services);
      Session::regenerate();
      Auth::login($user->id);
      Mailer::send("NewVendorRegistered", array("user" => $user));
      return Redirect::to('/');
    } else {
      Session::flash('errors', array_merge($user->validator()->errors->all(), $vendor->validator()->errors->all()));
      return Redirect::to_route('new_vendors')->with_input();
    }
  }

  public function action_index() {
    $view = View::make('vendors.index');
    $page = intval(Input::get('page') ?: 1);
    $view->vendors = Vendor::join('users', 'user_id', '=', 'users.id')
                           ->where_null('users.banned_at')
                           ->raw_where("EXISTS (SELECT service_id from service_vendor WHERE `vendor_id` = `vendors`.`id`)")
                           ->where(function($q){
                              $q->where(DB::raw("RIGHT(image_url, 4)"), '=', 'jpeg');
                              $q->or_where_in(DB::raw("RIGHT(image_url, 3)"), array('jpg', 'gif', 'png'));
                           })
                           ->select(array('*', 'vendors.id as vendor_id'))
                           ->skip(($page - 1) * 10)
                           ->take(10)
                           ->order_by('vendors.created_at', 'desc')
                           ->get();
    $this->layout->content = $view;
  }

  public function action_show() {
    $view = View::make('vendors.show');
    $view->vendor = Config::get('vendor');
    $this->layout->content = $view;
  }

}

Route::filter('vendor_exists', function() {
  $id = Request::$route->parameters[0];
  $vendor = Vendor::find($id);
  if (!$vendor) return Redirect::to('/vendors');
  Config::set('vendor', $vendor);
});
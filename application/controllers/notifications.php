<?php

class Notifications_Controller extends Base_Controller {

  public function __construct() {
    parent::__construct();

    $this->filter('before', 'auth');
    $this->filter('before', 'i_am_notification_target')->only('mark_as_read');
  }

  public function action_index() {
    $view = View::make('notifications.index');
    $view->notifications = Auth::user()->notifications_received()->order_by('created_at', 'desc')->paginate(10);
    $this->layout->content = $view;
  }

  public function action_mark_as_read() {
    $notification = Config::get('notification');
    if (Input::get('action') == '1') {
      $notification->mark_as_read();
    } else {
      $notification->mark_as_unread();
    }
    return Response::json(array("status" => "success",
                                "unread_count" => Auth::user()->unread_notification_count(),
                                "html" => View::make('notifications.partials.notification')->with('notification', $notification)->render()));
  }

  public function action_json() {
    $return_array = array();
    foreach(Auth::user()->notifications_received()->order_by('read')->order_by('created_at', 'desc')->take(3)->get() as $notification) {
      $return_array[] = array('object' => $notification->to_array(),
                              'parsed' => NotificationParser::parse($notification));
    }
    return Response::json(array('status' => 'success',
                                'results' => $return_array,
                                'count' => Auth::user()->notifications_received()->count()));
  }

}

Route::filter('i_am_notification_target', function() {
  $id = Request::$route->parameters[0];
  $notification = Notification::find($id);
  if (!Auth::user() || Auth::user()->id != $notification->target_id) return Redirect::to('/');
  Config::set('notification', $notification);
});
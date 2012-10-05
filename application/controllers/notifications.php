<?php

class Notifications_Controller extends Base_Controller {

  public function __construct() {
    parent::__construct();

    $this->filter('before', 'auth');
    $this->filter('before', 'i_am_notification_target')->only('mark_as_read');
  }

  public function action_index() {
    $view = View::make('notifications.index');
    $view->notifications = Auth::user()->notifications_received;
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
                                "html" => View::make('partials.media.notification')->with('notification', $notification)->render()));
  }

}

Route::filter('i_am_notification_target', function() {
  $id = Request::$route->parameters[0];
  $notification = Notification::find($id);
  if (!Auth::user() || Auth::user()->id != $notification->target_id) return Redirect::to('/');
  Config::set('notification', $notification);
});
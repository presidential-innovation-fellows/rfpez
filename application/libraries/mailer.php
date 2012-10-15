<?php

Class Mailer {

  public static function send($template_name, $attributes = array()) {

    $transport = Config::get('mailer.transport');
    if (!$transport) return;
    $mailer = Swift_Mailer::newInstance($transport);
    $message = Swift_Message::newInstance();
    $message->setFrom(array('noreply@sba.gov'=>'EasyBid'));


    if ($template_name == "Notification") {
      $notification = $attributes["notification"];
      $parsed = $notification->parsed();

      $message->setSubject($parsed["subject"])
              ->setTo($notification->target->email)
              ->addPart(View::make('mailer.notification_text')->with('notification', $notification), 'text/plain')
              ->setBody(View::make('mailer.notification_html')->with('notification', $notification), 'text/html');

    } elseif ($template_name == "NewOfficerInvited") {
      $invited_by = $attributes["invited_by"];
      $new_user = $attributes["new_user"];
      $project = $attributes["project"];

      $message->setSubject("You've been invited to collaborate on EasyBid by ".$invited_by->email)
              ->setTo($new_user->email)
              ->addPart(View::make('mailer.new_officer_invited_text')
                ->with('new_user', $new_user)
                ->with('invited_by', $invited_by)
                ->with('project', $project), 'text/plain')
              ->setBody(View::make('mailer.new_officer_invited_html')
                ->with('new_user', $new_user)
                ->with('invited_by', $invited_by)
                ->with('project', $project), 'text/html');

    } else {
      throw new \Exception("Can't find the template you specified.");
    }


    // If mailer.send_all_to is set in the config files, ignore the original
    // recipient and instead, send to the email address specified.
    if (Config::has('mailer.send_all_to')) {
      $message->setSubject("(".$message->getHeaders()->get('To').") ".$message->getSubject());
      $message->setTo(Config::get('mailer.send_all_to'));
    }

    $mailer->send($message);

  }

}
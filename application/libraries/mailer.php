<?php

class Mailer {

  public static function send($template_name, $vars = array()) {

    $transport = Config::get('mailer.transport');
    $mailer = Swift_Mailer::newInstance($transport);
    $message = Swift_Message::newInstance();
    $message->setFrom(array('noreply@sba.gov'=>'EasyBid'));

    if ($template_name == "Dismissal") {
      $bid = $vars["bid"];

      $message->setSubject("Sorry, your bid on \"".$bid->contract->title."\" was dismissed.")
              ->setTo($bid->vendor->user->email)
              ->addPart(View::make('mailer.bid_dismissed_text')->with('bid', $bid), 'text/plain')
              ->setBody(View::make('mailer.bid_dismissed_html')->with('bid', $bid), 'text/html');

    } else {
      throw new Exception("Couldn't find the template that you requested.");
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
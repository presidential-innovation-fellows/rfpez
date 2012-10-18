<?php

Class RelativeTime {
  public static function format($date) {
    if (empty($date)) throw new Exception("No date provided");
    if (is_object($date)) return false;

    $periods = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
    $lengths = array("60","60","24","7","4.35","12","10");
    $now= time();
    $unix_date = strtotime($date);

    if(empty($unix_date)) throw new Exception("Bad date");

    if ($now > $unix_date) {
      $difference = $now - $unix_date;
      $tense = "ago";
    } else {
      $difference = $unix_date - $now;
      $tense  = "";
    }

    for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
      $difference /= $lengths[$j];
    }

    $difference = round($difference);

    if ($difference != 1) $periods[$j].= "s";

    return "$difference $periods[$j] {$tense}";
  }
}
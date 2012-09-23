<?php

Class Helper {
  public static function full_title($title = "") {
    if ($title == "") {
      return "EasyBid";
    } else {
      return "$title | EasyBid";
    }
  }
}

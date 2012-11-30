<?php

Class Helper {

  public static function asset($n) {

    if (preg_match('/^css/', $n)) {
      $ext = Config::get('assets.use_minified') === false ? ".css" : ".min.css?t=".Config::get('deploy_timestamp');
      return HTML::style($n.$ext);
    } elseif (preg_match('/^js/', $n)) {
      $ext = Config::get('assets.use_minified') === false ? ".js" : ".min.js?t=".Config::get('deploy_timestamp');
      return HTML::script($n.$ext);
    } else {
      throw new \Exception("Can't handle that asset type.");
    }
  }

  public static function timeago($timestamp) {
    $str = strtotime($timestamp);
    return "<span class='timeago' title='".date('c', $str)."'>".date('r', $str)."</abbr>";
  }

  public static function helper_tooltip($title, $placement = "top", $pull_right = false) {
    return "<span class='helper-tooltip ".($pull_right ? 'pull-right' : '')."' data-title=\"".htmlspecialchars($title)."\" data-trigger='manual' data-placement='$placement'>
        <i class='icon-question-sign icon-white'></i>
      </span>";
  }

  public static function datum($label, $content, $link = false) {
    if ($content) {
      $isEmail = filter_var($content, FILTER_VALIDATE_EMAIL);
      return "<div class='datum'>
                <label>$label</label>
                <div class='content'>".($link ? "<a href='".($isEmail ? "mailto:$content" : $content).
                  "' ".($isEmail ? '' : 'target="_blank"').">" : "")."$content".($link ? '</a>' : '')."</div>
              </div>";
    } else {
      return '';
    }
  }

  public static function flash_errors($errors) {
    if (!is_array($errors)) $errors = array($errors);

    if (Session::has('errors')) {
      Session::flash('errors', array_merge(Session::get('errors'), $errors));
    } else {
      Session::flash('errors', $errors);
    }
  }

  public static function active_nav($section) {
    return (Section::yield('active_nav') == $section) ? true : false;
  }

  public static function active_subnav($section) {
    return (Section::yield('active_subnav') == $section) ? true : false;
  }

  public static function active_sidebar($section) {
    return (Section::yield('active_sidebar') == $section) ? true : false;
  }

  public static function truncate($phrase, $max_words) {
    $phrase_array = explode(' ',$phrase);
    if(count($phrase_array) > $max_words && $max_words > 0) $phrase = implode(' ',array_slice($phrase_array, 0, $max_words)).'...';
    return $phrase;
  }

  public static function full_title($title = "", $action = "") {
    if ($title == "") {
      return "EasyBid";
    } elseif ($action == "") {
      return "$title | EasyBid";
    } else {
      return "$action | $title | EasyBid";
    }
  }

  public static function all_us_states() {
    return array('AL'=>"Alabama",
                'AK'=>"Alaska",
                'AZ'=>"Arizona",
                'AR'=>"Arkansas",
                'CA'=>"California",
                'CO'=>"Colorado",
                'CT'=>"Connecticut",
                'DE'=>"Delaware",
                'DC'=>"District of Columbia",
                'FL'=>"Florida",
                'GA'=>"Georgia",
                'HI'=>"Hawaii",
                'ID'=>"Idaho",
                'IL'=>"Illinois",
                'IN'=>"Indiana",
                'IA'=>"Iowa",
                'KS'=>"Kansas",
                'KY'=>"Kentucky",
                'LA'=>"Louisiana",
                'ME'=>"Maine",
                'MD'=>"Maryland",
                'MA'=>"Massachusetts",
                'MI'=>"Michigan",
                'MN'=>"Minnesota",
                'MS'=>"Mississippi",
                'MO'=>"Missouri",
                'MT'=>"Montana",
                'NE'=>"Nebraska",
                'NV'=>"Nevada",
                'NH'=>"New Hampshire",
                'NJ'=>"New Jersey",
                'NM'=>"New Mexico",
                'NY'=>"New York",
                'NC'=>"North Carolina",
                'ND'=>"North Dakota",
                'OH'=>"Ohio",
                'OK'=>"Oklahoma",
                'OR'=>"Oregon",
                'PA'=>"Pennsylvania",
                'RI'=>"Rhode Island",
                'SC'=>"South Carolina",
                'SD'=>"South Dakota",
                'TN'=>"Tennessee",
                'TX'=>"Texas",
                'UT'=>"Utah",
                'VT'=>"Vermont",
                'VA'=>"Virginia",
                'WA'=>"Washington",
                'WV'=>"West Virginia",
                'WI'=>"Wisconsin",
                'WY'=>"Wyoming");
  }
}

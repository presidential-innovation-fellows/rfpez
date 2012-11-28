<?php

class SowVariableParser {

  public static function parse($input, $variable_values = array(), $mode = "write") {

    preg_match_all('/\{\{\s*([^\}]*)\}\}/', $input, $variables);

    $vars = array();
    $temp_varnames = array();

    foreach($variables[1] as $var) {
      $var_bits = explode("|", $var);
      if (!isset($var_bits[0])) continue;
      $varname = trim($var_bits[0]);

      if (isset($vars[$varname]) && $vars[$varname] != "") {
        //variable has already been parsed
      } else {
        $vars[$varname] = isset($var_bits[1]) ? trim($var_bits[1]) : "";
      }
    }

    $output = $input;
    $count = 0;

    foreach($vars as $key => $help_text) {

      $count++;

      if ($mode == "write") {

        if (!isset($variable_values[$key])) $variable_values[$key] = "";

        $text_input_html = "<input type='text' placeholder='$key' data-variable='$key' data-helper-text='$help_text' name='variables[$key]' value='".$variable_values[$key]."' />";

        $output = preg_replace('/\{\{\s*([^\}]*'.$key.'[^\}]*)\}\}/', $text_input_html, $output);

      } else if ($mode == "read") {
        $output = preg_replace('/\{\{\s*([^\}]*'.$key.'[^\}]*)\}\}/', (@$variable_values[$key] ?: "_______________"), $output);
      }
    }

    if ($mode == "write") {
      return array('output' => $output,
                   'count' => $count);

    } else {
      return $output;
    }
  }

}
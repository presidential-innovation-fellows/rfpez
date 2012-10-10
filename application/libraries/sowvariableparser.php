<?php

class SowVariableParser {

  public static function parse($input, $sow, $mode = "write") {

    preg_match_all('/\{\{\s*([^\}]*)\}\}/', $input, $variables);

    $vars = array();
    $temp_varnames = array();

    foreach($variables[1] as $var) {
      $var_bits = explode("|", $var);

      if (isset($var_bits[0])) {
       $vars[] = trim($var_bits[0]);
      }
    }

    $output = $input;

    foreach($vars as $key) {

      if ($mode == "write") {
        $template_vars = $sow->template->variables;
        $help_text = empty($template_vars[$key]["help_text"]) ? '' : $template_vars[$key]["help_text"];
        $name = $template_vars[$key]["name"];
        $existing_val = $sow->get_variable($key);

        $text_input_html = "<input type='text' placeholder='$name' data-variable='$key'"
                               . " data-helper-text='$help_text' name='variables[$key]'"
                               . " value='$existing_val' />";
        $output = preg_replace('/\{\{\s*([^\}]*'.$key.'[^\}]*)\}\}/', $text_input_html, $output);

      } else if ($mode == "read") {
        $output = preg_replace('/\{\{\s*([^\}]*'.$key.'[^\}]*)\}\}/', $sow->get_variable($key), $output);
      }
    }

    return $output;
  }

}
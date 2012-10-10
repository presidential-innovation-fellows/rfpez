<?php

class SowTemplateParser {

    public static function parse($raw_template)
    {
      //High-level blocks
      $blocks = preg_split('/\-\-\-(.+)\-\-\-/', $raw_template, -1, PREG_SPLIT_DELIM_CAPTURE);
      $trimmed_blocks = array_map('trim', $blocks);

      $tp = new SowTemplate();
      $tp->title = $trimmed_blocks[2];

      $blocks = array_slice($trimmed_blocks, 3);

      $ord = 0;
      $vars = array();

      $section_type = '';

      foreach($blocks as $block) {
        //is this a header or a text block?
        if (preg_match('/^\w/', $block)) {
          $section_type = $block;
          continue;
        }

        $bits = preg_split('/(^#|[\r\n]+#)\s/', $block, -1, PREG_SPLIT_NO_EMPTY);
        //This is getting a little messy, but check if there's a description/helper before the sections
        preg_match('/\-\-(.+)\-\-/', $bits[0], $helpertexts);

        if ($helpertexts) {
          $vars[$section_type] = $helpertexts[1];
          array_shift($bits);
        }

        foreach($bits as $bit) {
          $ts = new SowTemplateSection();
          $sec_bits = preg_split('/[\r\n]#{2,3}/', $bit);
          $ts->title = trim($sec_bits[0]);
          $ts->section_type = $section_type;
          if (count($sec_bits) < 3) { //no help text
            $ts->help_text = '';
            $ts->body = trim($sec_bits[1]);
          } else {
            $ts->help_text = trim($sec_bits[1]);
            $ts->body = trim($sec_bits[2]);
          }

          $ts->display_order = $ord;

          preg_match_all('/\{\{\s*([^\}]*)\}\}/', $ts->body, $variables);
          foreach($variables[1] as $var) {

            $var_bits = explode("|", $var);
            $var_name = trim(isset($var_bits[0]) ? $var_bits[0] : $var);
            //avoid dupes
            if (!array_key_exists($var_name, $vars)) {
              $vars[$var_name] = array();
            }
            if (count($var_bits) > 1) $vars[$var_name]['name'] = trim($var_bits[1]);
            if (count($var_bits) > 2) $vars[$var_name]['help_text'] = trim($var_bits[2]);
          }

          $sections[] = $ts;
          $ord++;
        }
      }

      $tp->variables = $vars;
      $tp->save();
      $tp->template_sections()->save($sections);

      return $tp;
    }

}
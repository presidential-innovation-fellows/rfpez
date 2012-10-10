<?php

class Import_Sow_Templates_Task {

    public function run()
    {
      echo "\nBYE-BYE EXISTING TEMPLATES!\n\n";
      DB::query('UPDATE `sow_templates` set `visible` = 0');

      $template_files = scandir('storage/sow_templates/');
      foreach($template_files as $tf) {
        if ($tf != "." && $tf != "..") {
          echo "Parsing " . $tf . "... ";
          $tp = SowTemplateParser::parse(File::get('storage/sow_templates/' . $tf));
          echo "'" . $tp->title . "' created.\n";
        }
      }
    }

}

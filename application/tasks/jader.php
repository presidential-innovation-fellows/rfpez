<?php

class Jader_Task {

  public function run($force = false) {
    $jade = new Jade\Jade;
    $dir = "application/views/";

    $template_files = getJadeFiles($dir);
    foreach($template_files as $tf) {
      $phpver = str_replace(".jade", ".php", $tf);
      if ($force || !file_exists($dir . $phpver) || filemtime($dir . $tf) > filemtime($dir . $phpver) ) {
        echo "compiling " . $dir . $phpver . "\n";
        $output = $jade->render(File::get($dir.$tf));
        file_put_contents($dir.$phpver, $output);
      }
    }
  }

  public function force() {
    $this->run(true);
  }

}

function getJadeFiles($dir, $prefix = '') {
  $dir = rtrim($dir, '\\/');
  $result = array();
    foreach (scandir($dir) as $f) {
      if (!preg_match('/^\./', $f)) {
        if (is_dir("$dir/$f")) {
          $result = array_merge($result, getJadeFiles("$dir/$f", "$prefix$f/"));
        } else if ( preg_match('/.jade$/', $f) ) {
          $result[] = $prefix.$f;
        }
      }
    }

  return $result;
}


<?php
function cb_iulia_include($compName) {
  $compDir = get_stylesheet_directory() . '/cb-iulia/' . $compName . '-main';

  if(is_dir($compDir)) {
    foreach(glob($compDir . '/*.php') as $file) {
      require_once($file);
    }
  }
}

?>

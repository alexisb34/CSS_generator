<?php
function my_generate_html($html_name, $sprite_name, $check_files ){
    
    $w_html = "<!DOCTYPE html>
    <html>
    <body>
    
    ";
    foreach ($check_files as $file) {


      $w_html .= "<i class='" . $sprite_name . "_" . $file['name'] . "'></i>" . PHP_EOL . PHP_EOL;
    }
    $file_html = fopen($html_name . ".html", "w");
    
    fwrite($file_html, $w_html);
    
    fclose($file_html);

    echo 
    "|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||" . PHP_EOL .
    "||||||||||||||||||||LE FICHIER HTML \e[92m" . $html_name . ".html\e[39m A ETE CREER|||||||||||||||||||||" . PHP_EOL .
    "|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||" . PHP_EOL;
  }

  my_generate_html($html_name, $sprite_name, $check_files );
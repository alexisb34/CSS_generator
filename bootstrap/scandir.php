<?php

$path = "images";

$list_png = my_scandir($path);


function my_scandir($path){
  
  $list_png = [];
  
  $dir = opendir($path);
  while (false !== ($files_dir = readdir($dir))) {

    
    if ($files_dir != '.' && $files_dir != '..' && is_dir($path.'/'.$files_dir))
    
    {
      $list_png = array_merge($list_png, my_scandir($path.'/'.$files_dir));
    }
    elseif ($files_dir != '.' && $files_dir != '..')
    {
      $list_png[] = $path . '/' . $files_dir;
    }
    
  }
  
  closedir($dir);
  
  return $list_png;
  
} 




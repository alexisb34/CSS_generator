<?php

$path = "";

$list_png = my_scandir($path = "images");


function my_scandir($path = "images"){
  
  //On déclare le tableau qui contiendra tous les éléments de nos dossiers
  $list_png = [];
  
  //On ouvre le dossier 
  $dir = opendir($path);
  //Pour chaque élément du dossier
  while (false !== ($files_dir = readdir($dir))) {
    
    /*Si l'élément est lui-même un dossier (en excluant les dossiers parent et actuel),
    on appelle la fonction de listage en modifiant la racine du dossier à ouvrir*/
    
    if ($files_dir != '.' && $files_dir != '..' && is_dir($path.'/'.$files_dir))
    
    {
      /*On fusionne le tableau grâce à la fonction array_merge. Au final,
      tous les résultats de nos appels récursifs à la fonction listage 
      fusionneront dans le même tableau */
      $list_png = array_merge($list_png, my_scandir($path.'/'.$files_dir));
    }
    elseif ($files_dir != '.' && $files_dir != '..')
    {
      //Sinon, l'élément est un fichier : on l'enregistre dans le tableau
      $list_png[] = $path . '/' . $files_dir;
    }
    
  }
  
  closedir($dir);
  
  return $list_png;
  
} 




<?php

// permet de prendre le dernier parametre comme nom de dossier à traiter
$argv;
$path = array_pop($argv);

//////////////////OPTIONS/////////////////////
$name_css = "style";
$sprite_name = "sprite";
$html_name = "class";
$r = false;
$c = false;
$h = false;
# RECURSIVE ########################################################################
$options = getopt("hri::s::c::", ["help", "recursive", "output-image::", "output-style::", "output-class::"]);
if ( isset($options['r']) || isset($options['recursive'])) {
  $r = true; 
}
# RENOMAGE SPRITE ##################################################################
if ( isset($options['i']) and $options['i'] !== FALSE)  {
  $sprite_name = $options['i'];
}
elseif ( isset($options['output-image']) and $options['output-image'] !== FALSE) {
  $sprite_name = $options['output-image'];
}
# RENOMAGE STYLE ###################################################################
if ( isset($options['s']) and $options['s'] !== FALSE)  {
  $name_css = $options['s'];
}
elseif ( isset($options['output-style']) and $options['output-style'] !== FALSE) {
  $name_css = $options['output-style'];
}
# OPTION CLASS HTML ################################################################
if ( isset($options['c']) && $options['c'] == false)  {
  $c = true;
}
elseif (isset($options['c'])) {
  $html_name = $options['c'];
  $c = true;
}
elseif ( isset($options['output-class']) && $options['output-class'] == false) {
  $c = true;
}
elseif (isset($options['output-class'])) {
  $html_name = $options['output-class'];
  $c = true;
}
# HELP #############################################################################
if ( isset($options['h']) || isset($options['help']))  {
  $h = true;
}
echo "
|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||\e[94m

   ________________                                     __            
  / ____/ ___/ ___/   ____ ____  ____  ___  _________ _/ /_____  _____
 / /    \__ \\__ \    / __ `/ _ \/ __ \/ _ \/ ___/ __ `/ __/ __ \/ ___/
/ /___ ___/ /__/ /  / /_/ /  __/ / / /  __/ /  / /_/ / /_/ /_/ / /    
\____//____/____/   \__, /\___/_/ /_/\___/_/   \__,_/\__/\____/_/     
                   /____/\e[39m                                             
   
|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
||||||||||||||||||||||||||||||||\e[92m-h FOR HELP\e[39m||||||||||||||||||||||||||||||||||||
|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||" . PHP_EOL;

if ($h == true) { // AFFICHAGE HELP SI APPEL
  echo"
  \e[33mCSS_generator [OPTIONS]. . assets_folder
  
  DESCRIPTION
  
  Concatenate all images inside a folder in one sprite and write a style sheet ready to use.
  Mandatory arguments to long options are mandatory for short options too.
  
  OPTIONS:
  
  -r, --recursive
  Look for images into the assets_folder passed as arguement and all of its subdirectories.
  
  -i, --output-image=IMAGE
  Name of the generated image. If blank, the default name is « sprite.png ».
  
  -s, --output-style=STYLE
  Name of the generated stylesheet. If blank, the default name is « style.css ».
  
  -c, --output-class=CLASS
  Name of the generated class file. If blank, the default namz is « class.html ».
  
  -h, --help
  Manual for use
  
  ";
  return;
}

function my_scandir($path, $r = false){ // FONCTION LISTAGE PNG ET RECURSIVITE
  
  $list_png = [];
  
  // On ouvre le dossier 
  $dir = opendir($path);
  //Pour chaque élément du dossier
  while (false !== ($files_dir = readdir($dir))) {
    /*Si l'élément est lui-même un dossier (en excluant les dossiers parent et actuel),
    on appelle la fonction de listage en modifiant la racine du dossier à ouvrir*/
    if ($files_dir != '.' && $files_dir != '..' && is_dir($path.'/'.$files_dir) && $r == true)
    {
      /*fusion des tableaux avec 'array_merge'. Au final,
      tous les résultats de nos appels récursifs à la fonction listage 
      fusionneront dans le même tableau */
      $list_png = array_merge($list_png, my_scandir($path.'/'.$files_dir));
    }
    elseif ( $files_dir != '.' && $files_dir != '..' && $files_dir)//Sinon, l'élément est un fichier : on l'enregistre dans le tableau
    {
      if (substr($files_dir, -4) == '.png'){
        $list_png[] = $path . '/' . $files_dir;
      }
    }
  }
  closedir($dir);
  return $list_png; 
} 

if (!is_dir($path)){ // SI AUCUN DOSSIER DONNE EN PARAMETRE
  echo
  "|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||" . PHP_EOL .
  "||||||\e[91mVEUILLEZ DONNER UN DOSSIER AVEC IMAGE PNG A TRAITER EN PARAMETRE\e[39m|||||||||" . PHP_EOL .
  "|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||" . PHP_EOL . PHP_EOL;
  return;
}  

function sprite_generator($path, $r, $sprite_name, $name_css, $c, $html_name){
  $list_png = my_scandir($path,$r);
  
  $imgwidth = [];
  $imgheightCOPY = [];
  $check_files = [];
  $height = 0;
  $empty_img = "";
  
  //Je récupere dans un tableau les dimensions des fichiers,le type, le chemin, le nom //
  foreach ($list_png as $file) {
    list($w, $h, $t) = getimagesize($file);
    $name_file = basename($file, ".png");
    
    $height += $h;
    
    $check_files[]= array('name'=>$name_file, 'file'=> $file, 'type'=> $t, 'height'=>$h, 'width'=>$w);
    array_push($imgwidth, $w);
  }
  // dimension totale de mon sprite
  $Max_img_width = max($imgwidth);
  $Max_img_height = $height;
  
  // création du l'image vierge //
  $empty_img = imagecreatetruecolor($Max_img_width,$Max_img_height);
  
  // Ajout des images dans l'image vierge de haut en bas //
  $pos = 0;
  
  foreach ($check_files as $file) {
    
    if ($file['type'] == IMAGETYPE_PNG) {
      $file_tmp = imagecreatefrompng($file['file']);
    }
    else if ($file['type'] !== IMAGETYPE_PNG) {
      echo ('ERREUR : Ce type d\'image n\'est pas accepté, veuillez sélectionner un format PNG seulement' . PHP_EOL);
      return;
    }
    imagecopy($empty_img, $file_tmp, 0, $pos, 0, 0, $file['width'], $file['height']);
    $pos += $file['height'];
    
    imagepng($empty_img, $sprite_name . '.png');
  }
  // GENERATEUR DU FICHIER CSS et HTML >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>// 
  function my_generate_css($name_css, $sprite_name, $check_files, $c, $html_name){
    
    $pos = 0;
    
    $w_css = ".sprite {
      background-image: url(" . $sprite_name . ".png);
      background-repeat: no-repeat;
      display: block;
    }
    
    ";
    foreach ($check_files as $file) {
      $height = $file['height'];
      $width = $file['width'];
      $namef = $file['name'];
      
      $w_css .=  ".sprite-" . $namef . " {
        width: " . $width . "px;
        height: " . $height . "px;
        background-position: -0px -" . $pos . "px;
      }   
      
      ";    
      $pos += $height;
    }
    $file_css = fopen($name_css . ".css", "w");
    
    fwrite($file_css, $w_css);
    
    fclose($file_css);
    
    echo 
    "|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||" . PHP_EOL .
    "|||||||LE SPRITE_SHEET \e[92m" . $sprite_name . ".png\e[39m ET CSS_SHEET \e[92m" . $name_css . ".css\e[39m ONT ETES CREER |||||||" . PHP_EOL .
    "|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||" . PHP_EOL;
    if( $c == true){
      require_once 'html.php';
    }
  }
  my_generate_css($name_css, $sprite_name, $check_files, $c, $html_name);
}

sprite_generator($path, $r, $sprite_name, $name_css, $c, $html_name);

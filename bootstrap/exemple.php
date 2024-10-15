<?php

function spriter($files = array(), $dest = '', $imgwidth = 120, $imgheight = 90,
 $spacing = 0, $addgreyscale = false) {
  // calcule la taille de l'image
  if ($addgreyscale) {
    $height = $imgheight + $spacing + $imgheight;
  } else {
    $height = $imgheight;
  }
  $files_tmp = array();
  $width = 0;
  foreach ($files as $file) {
    list($w, $h, $t) = getimagesize($file);
    // vérifie que l'image est de la bonne taille
    if (($w == $imgwidth) && ($h == $imgheight)) {
      $width += ($spacing + $imgwidth);
      $files_tmp[] = array('file' => $file, 'type' => $t);
    }
  }

  // créé l'image vide
  $img = imagecreatetruecolor($width, $height);
  $background = imagecolorallocatealpha($img, 255, 255, 255, 127);
  imagefill($img, 0, 0, $background);
  imagealphablending($img, false);
  imagesavealpha($img, true);

  // ajoute les images de la gauche vers la droite
  // (avec l'image en dégradé de gris en dessous si demandé)
  $pos = 0;
  foreach ($files_tmp as $file) {
    if ($file['type'] == IMAGETYPE_GIF) {
      $tmp = imagecreatefromgif($file['file']);
    }elseif ($file['type'] == IMAGETYPE_JPEG) {
      $tmp = imagecreatefromjpeg($file['file']);
    }elseif ($file['type'] == IMAGETYPE_PNG) {
      $tmp = imagecreatefrompng($file['file']);
    } else {
      die('Erreur : type d\'image incorrect');
    }
    imagecopy($img, $tmp, $pos, 0, 0, 0, $imgwidth, $imgheight);
    if ($addgreyscale) {
      imagefilter($tmp, IMG_FILTER_GRAYSCALE);
      imagecopy($img, $tmp, $pos, $imgheight + $spacing, 0, 0, $imgwidth, 
$imgheight);
    }
    $pos += ($spacing + $imgwidth);
    imagedestroy($tmp);
    }

    // affiche l'image ou sauvegarde
    if (empty($dest)) {
      header('Content-Type: images/png');
      imagejpeg($img);
    } else {
      imagejpeg($img, $dest);
    }
  }


  spriter();
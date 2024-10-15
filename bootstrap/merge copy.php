<?php

header("content-type:image/png");

function my_merge_image($first_img_path, $second_img_path){

    $img1 = imagecreatefrompng($first_img_path);
    $img2 = imagecreatefrompng($second_img_path);

    $dest = imagecreatetruecolor(1818, 723);

    imagecopy($dest, $img2,0,0,0,0, 1818, 723);
    imagecopy($dest, $img1,909,0,0,0, 1818, 723);

    header('Content-Type: image/png');

    imagepng($dest,"images/test.png");

   


}
$first_img_path = "images/mrbean.png";
$second_img_path= "images/mrbean2.png";
$dest = "sprite.png";

my_merge_image($first_img_path,$second_img_path);

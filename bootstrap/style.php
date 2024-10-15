<?php

function my_generate_css(){

    $file = fopen("style.css", "w");

    $wfile = fwrite($file ,
        ".sprite {
        background-image: url(spritesheet.png);
        background-repeat: no-repeat;
        display: block;
    }");



    fclose($file);


    
}

my_generate_css();
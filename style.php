<?php

function my_generate_css($name_css = "style", $sprite_name, $check_files){

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
}

$name_css ="style";
my_generate_css($name_css, $sprite_name, $check_files);

require_once 'html.php';
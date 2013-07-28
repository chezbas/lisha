<?php

require 'class.JavaScriptPacker.php';

$my_array ='';
$l_directory = Array('../lisha1.00/js');

getFileDirectory($l_directory[0]);

foreach($my_array as $valeur)
{
    //print_r($valeur);
    $src = $valeur[0]."/".$valeur[1];
    $out = "ztemp".substr($valeur[0],strlen($valeur[2]))."/".$valeur[1];
    echo $out."<br>";

    // Compressor
    $script = file_get_contents($src);

    $t1 = microtime(true);

    $packer = new JavaScriptPacker($script, 'Normal', true, false);
    $packed = $packer->pack();

    $t2 = microtime(true);
    $time = sprintf('%.4f', ($t2 - $t1) );
    echo 'script ', $src, ' packed in ' , $out, ', in ', $time, ' s.', "\n";

    $path_dir = "ztemp".substr($valeur[0],strlen($valeur[2]));

    if(!is_dir($path_dir))
    {
        $chemin = explode("/",$path_dir);
        $origine = './';
        foreach($chemin as $value)
        {
            if(substr($value,-3) == '.js')
            {
                echo 'ici';
                break;
            }
            $origine .= $value.'/';
            if(!is_dir($origine))
            {
                mkdir($origine, 0770);
            }
        }
    }
    file_put_contents($out, $packed);
    // End compressor

    //break;
}

/*
// adapt these 2 paths to your files.
$src = '../lisha1.00/js/affichage.js';
$out = 'affichage.js';



$script = file_get_contents($src);

$t1 = microtime(true);

$packer = new JavaScriptPacker($script, 'Normal', true, false);
$packed = $packer->pack();

$t2 = microtime(true);
$time = sprintf('%.4f', ($t2 - $t1) );
echo 'script ', $src, ' packed in ' , $out, ', in ', $time, ' s.', "\n";

file_put_contents($out, $packed);
*/

function getFileDirectory( $path = '.', $level = 0, $root_path = '' )
{
    global $my_array;

    if($level == 0)
    {
        $my_root_path = $path;
    }
    else
    {
        $my_root_path = $root_path;
    }

    $ignore = array( 'cgi-bin', '.', '..' );

    $dh = @opendir( $path );
    while( false !== ( $file = readdir( $dh ) ) )
    {
        if( !in_array( $file, $ignore ) )
        {
            if( is_dir( "$path/$file" ) )
            {
                getFileDirectory( "$path/$file", ($level+1),$my_root_path );
            }
            else
            {
                if(substr($file,-3) == '.js')
                {
                    $my_array[] = Array($path,$file,$my_root_path,);
                }
            }
        }
    }
    closedir( $dh );
}
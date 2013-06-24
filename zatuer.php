<?php
    $data = Array(Array('2013-01-12',5),Array('2013-01-01',13),Array('2013-01-16',20));

    $i = 0;
    foreach($data AS &$valeur)
    {
        $tmp = $valeur[0];
        $work[strtotime($tmp).$i] = strtotime($tmp)+86400*$valeur[1];
        $i = $i + 1;
    }

    ksort($work);

echo '<pre>';
print_r($work);
echo '</pre>';


echo '<hr>';
echo 'hover';

echo date('Y-m-d',strtotime('+1 day'));


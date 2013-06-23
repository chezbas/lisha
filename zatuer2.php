<?php
    $tata = '<span class="ikcalc">
                <span onmouseover="ikdoc(\'id_aide\');set_text_help(461,\'\',\'\',\'<span class=\'BBVarInInfo\'>ApUP</span>\');"onmouseout="ikdoc();unset_text_help();" class="ikvalorised ikvalorised_varin BBVarIn">4</span>*2</span>';


    $tata = '[b]gras dazada  dazdadzad[/b]'; // Ok
    $tata = '[b]gras  [/b][/b] dazada [/b][/b]  dazdadzad [/b] alert'; // Ok
    $tata = '[b]gras  !!dazada  dazdadzad alert'; // Ok
    $tata = '[b]gras  [/b][/b][/b][/b]!!dazada [/b][/b]  dazdadzad [/b] alert'; // Ok
    $tata = 'ou pas[b]gras  [/b][/b][/b][/b]!!dazada [/b][/b]  dazdadzad zzz[/b][/b][/b] alert'; // Ok

    $tata = '[b]gras[/b]no[b][/b][/b][/b]plus[b][/b][b]gros[/b]'; // Ok

    $tata = '[b]a[/b][/b]b[/b]ad'; // Ok

    $tata = '--[b][b][b]a[/b][/b][/b]--[b]tata[/b]++[b]llm'; // Ok

    $tata = '3b8a61[b]Bold[/b]4226a953[b]a8cd9[b]526[/b]fca6fe9ba5'; // Ok
    $tata = '3b8a61[b]Bold[/b]4226a953[b]a8cd93463464fca6fe9ba5'; // Ok
    $tata = '3b8[b]a61[k][l][m]Bold4226a953a8cd934[b]63464fca6fe9ba5'; // Ok

    $tata = '<html>[br][br][br]pop[br]me[br][br]</html>'; // Ok
    $tata = '<html>[br][br][br][br]</html>'; // Ok
    $tata = '<html>[br][br][br]</html>';
    $tata = '<html>[br]</html>';
    $tata = '<html>[br][br]</html>';
    $tata = '<html>[br][br]77[br]</html>2<html>[br][br][br]</html>';

    //preg_match_all('`\[b\]((.*?(\[/b\]\[/b\])*)*?.*?)(\[/b\])`i',$tata,$out);

    // Assertion exclusion inside : (?!(\[/b\])) means not followed by [/b]
    // You can protect [/b] by inserting [/b][/b]
    //preg_match_all('`\[b\]((.*?(\[/b\]\[/b\])*)*?)\[/b\](?!(\[/b\]))`i',$tata,$out);

    //preg_match_all('`\[b\](.*?(\[/b\]\[ /b\].*?)*)\[/b\](?!(\[/b\]))`i',$tata,$out);

    //preg_match_all('`((\[br\]\[br\])*.*?)\[br\](?!(\[br\]))`i',$tata,$out);
    preg_match_all('`\[br\]\[br\]\[br\]`i',$tata,$out);

    //preg_match_all('`(?<!(\[br\]))\[br\](?!(\[br\]))`i',$tata,$out);
    //$p_text = preg_replace('`\[b\]((.*?(\[/b\]\[/b\])*)*?)\[/b\](?!(\[/b\]))`i','<b>\\1</b>',$tata);
    //$p_text = preg_replace('`(?<!\[br\])(\[br\])(?!\[br\])`ie','str_replace("[br]","<br>","\\1")',$tata);
    //$p_text = preg_replace('`(\[br\]\[br\].*?)*(?!(\[br\]))(\[br\])(?!(\[br\]))`ie','"\\1".str_replace("[br]","<br>","\\2")',$tata);

    //$p_text = preg_replace('`\[br\](\[br\]*.*?)(\[br\])(?!(\[br\]))`ie','"\\1".str_replace("[br]","<br>","\\2")',$tata);
    //$p_text = preg_replace('`((\[br\]\[br\])*.*?)\[br\](?!(\[br\]))`ie','str_replace("[br][br]","[br]","\\1")."<br>"',$tata);

    $p_text = preg_replace('`(?<!\[br\])\[br\](?!(\[br\]))`ie','str_replace("[br]","<br>","\\0")',$tata);
    $p_text = preg_replace('`(?<!\[br\])\[br\]\[br\]\[br\](?!(\[br\]))`ie','str_replace("[br][br][br]","[br]<br>","\\0")',$p_text);
    //$p_text = str_replace('[br][br][br]','[br]<br>',$p_text);
    $p_text = str_replace('[br][br]','[br]',$p_text);

    //$tata = substr($tata,0,-7);

    //preg_match_all('`<span class="ikcalc">[^>]*>([^<]*)`i',$tata,$out);
    //preg_match_all('`<span onmouseover="ikdoc.+">(.)+</span>`i',$tata,$out);

    //preg_match_all('`(class="ikvalorised ikvalorised_varin BBVar[^>]+>)[^<]+</span>`',$tata,$out);

    error_log(print_r($out,true));

    echo '<textarea cols="180" rows="50">'.$p_text.'</textarea>';

    function mycutom($chaine)
    {
        if($chaine != '')
        {
            return "<br>".$chaine."</br>";
        }
        else
        {
            return $chaine;
        }
    }

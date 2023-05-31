<?php


function currencyToDecimal($string = null){

    if($string != null){

        $string = str_replace(' ', '', $string);
        $string = str_replace('.', '', $string);
        $string = str_replace(',', '.', $string);

    }
 

    return $string;
}


function slug($text, $divider = "-")
{
    // replace non letter or digits by divider
    $text = preg_replace('~[^\pL\d]+~u', $divider, $text);

    // transliterate
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

    // remove unwanted characters
    $text = preg_replace('~[^-\w]+~', '', $text);

    // trim
    $text = trim($text, $divider);

    // remove duplicate divider
    $text = preg_replace('~-+~', $divider, $text);

    // lowercase
    $text = strtolower($text);

    $text = str_replace('ã','a',$text);
    $text = str_replace('â','a',$text);
    $text = str_replace('ô','o',$text);
    $text = str_replace('í','i',$text);
    $text = str_replace('î','i',$text);

    if (empty($text)) {
        return 'n-a';
    }

    //$text =  session()->get('user')['id'] . '-' .$text;
    return $text;
}



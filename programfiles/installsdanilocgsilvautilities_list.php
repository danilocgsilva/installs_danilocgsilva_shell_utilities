<?php

function extract_obj_curl($url, &$ch) {
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
    $content = curl_exec($ch);
    $obj_returned = json_decode($content);
    return $obj_returned;
}

function check_if_programfiles_is_present($repository_name, &$ch) {
    $filelist = extract_obj_curl("https://api.github.com/repos/danilocgsilva/" . $repository_name . "/contents/", $ch);
    
}

header("Content-Type: text/plain");

$ch = curl_init();
$rep_list = extract_obj_curl('https://api.github.com/users/danilocgsilva/repos?per_page=10000', $ch);
foreach ($rep_list as $rep) {
    echo $rep->name . "\n";
}
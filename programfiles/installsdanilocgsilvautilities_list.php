<?php

/**
 * Automates the task of fetching the object from a curl web consult
 * @param {string} url - The url of consult
 * @param {curl} ch - The curl object
 * @return {array}
 */
function extract_obj_curl($url, &$ch) {
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
    $content = curl_exec($ch);
    $obj_returned = json_decode($content);
    return $obj_returned;
}

/**
 * Check if the github project have a folder called "programfiles"
 * @param {object} obs - The object representing the repository, fetched earlier
 * @return {bool}
 */
function check_program_file_in_object_array($obs) {
    $return_default = false;
    foreach($obs as $node) {
        if ($node->name == "programfiles" && $node->type == "dir") {
            $return_default = true;
            break;
        }
    }
    return $return_default;
}

/**
 * Fetches the data from url based on the repository name
 * @param {string} repository_name - The string
 * @return {bool}
 */
function check_if_programfiles_is_present($repository_name, &$ch) {
    $url_consult = "https://api.github.com/repos/danilocgsilva/" . $repository_name . "/contents/";
    $filelist = extract_obj_curl($url_consult, $ch);
    return check_program_file_in_object_array($filelist);
}

header("Content-Type: text/plain");

$ch = curl_init();

// Repository list
$rep_list = extract_obj_curl('https://api.github.com/users/danilocgsilva/repos?per_page=10000', $ch);

foreach ($rep_list as $rep) {
    echo $rep->name . "\n";

    $programfiles_present = check_if_programfiles_is_present($rep->name, $ch);

    if ($programfiles_present) {
        echo "program file is here!\n";
    } else {
        echo "program file missing\n";
    }
}
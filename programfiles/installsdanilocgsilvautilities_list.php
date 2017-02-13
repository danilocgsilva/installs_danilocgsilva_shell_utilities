<?php
require_once('installsdanilocgsilvautilities_list.configs.php');

/**
 * Automates the task of fetching the object from a curl web consult
 * @param {string} url - The url of consult
 * @param {curl} ch - The curl object
 * @return {array}
 */
function extract_obj_curl($url, &$ch) {
    GLOBAL $github_pass;
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
    curl_setopt($ch, CURLOPT_USERPWD, $github_pass);
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
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

/**
 * Verify values provided from GET. If something "strange", halts the application.
 * @return {string}
 */
function validates_input_from_get($value) {
    $first_entrance = htmlspecialchars($value);
    if (!preg_match('/[a-zA-Z0-9]/', $first_entrance)) {
        die();
    }
    return $first_entrance;
}

/**
 * Verify if the provided values are one of the expected. If not, halts the processing
 * @return {string}
 */
function verify_if_values_are_expected_op_and_return() {
    $operation = validates_input_from_get($_GET['op']);
    
    if (!($operation == 'fetch' || $operation == 'test')) {
        die();
    }

    return $operation;
}

/**
 * The recursive function to fetch files from the project folder found
 * @return {string}
 */
function prints_download_project_tree($object_array, $current_place, &$forging_string) {
    foreach ($object_array as $entry) {

        $entry_type = $entry->type;
        $entry_name = $entry_name;
        $forging_string .= "\n";

        switch ($entry_type) {

            case "file":

                $forging_string .= $current_place . $entry_name;

            case "dir":

                $content_url = "https://api.github.com/repos/danilocgsilva/installs_danilocgsilva_shell_utilities/contents/programfiles/" .  . "?ref=master";
                $folder_contents = extract_obj_curl();

        }

    }
}

/**
 * Functions is over. Now the processing
 */
header("Content-Type: text/plain");

$operation = verify_if_values_are_expected_op_and_return();
if ($operation == "test") {
    echo 'ok';
    exit();
}

$utility_name = validates_input_from_get($_GET['utility_name']);

$ch = curl_init();

// Repository list
$rep_list = extract_obj_curl('https://api.github.com/users/danilocgsilva/repos?per_page=10000', $ch);

foreach ($rep_list as $rep) {

    $project_name = $rep->name;

    echo $project_name . "\n";

    $programfiles_present = check_if_programfiles_is_present($project_name, $ch);

    if ($programfiles_present) {
        
        $url_to_extract = "https://api.github.com/repos/danilocgsilva/" . $project_name . "/contents/programfiles?ref=master";
        
        $pf_contents = extract_obj_curl($url_to_extract, $ch);
        foreach($pf_contents as $content) {
            $content_file_name = $content->name;
            if ($content_file_name == $utility_name) {
                echo "FOUND IT!!!!\n";
                return;
            }
        }
    } else {
        echo "program file missing\n";
    }
    echo "\n";
}

echo "I DID NOT FOUND NOTHING. SORRY...\n";
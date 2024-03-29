<?php

function myurlencode($string) {
    $entities = array('%21', '%2A', '%27', '%28', '%29', '%3B', '%3A', '%40', '%26', '%3D', '%2B', '%24', '%2C', '%2F', '%3F', '%25', '%23', '%5B', '%5D', '%2E');
    $replacements = array('!', '*', "'", "(", ")", ";", ":", "@", "&", "=", "+", "$", ",", "/", "?", "%", "#", "[", "]", ".");
    return str_replace($replacements, $entities, urlencode($string));
}

function myurldecode($string) {
    $entities = array('%21', '%2A', '%27', '%28', '%29', '%3B', '%3A', '%40', '%26', '%3D', '%2B', '%24', '%2C', '%2F', '%3F', '%25', '%23', '%5B', '%5D', '%2E');
    $replacements = array('!', '*', "'", "(", ")", ";", ":", "@", "&", "=", "+", "$", ",", "/", "?", "%", "#", "[", "]", ".");
    return str_replace($entities, $replacements, urldecode($string));
}

/**
 * 
 * @param string $date in format Y/m/d
 * @param string $time in format H:i:s or H:i
 * @return mixed UNIX TS or False
 */
function verify_date_time($date, $time = '') {
    return strtotime($time . ' ' . $date);
}

/**
 * 
 * @param string $date in format m/d/Y
 * @param string $time in format H:i:s
 * @param boolean $unix [optional] select return type. True for unix timestamp. False for bool
 * @return mixed
 */
function verify_date($date, $unix = TRUE) {
    //verify length
    if (strlen($date) !== 10)
        return false;

    $date = DateTime::createFromFormat('m/d/Y', $date);
    if ($unix)
        return $date->getTimestamp();

    return true;
}

/**
 * Input 10 digit int and returns formatted phone number like (303) 555-5555
 * @param int $unformatted_phone_number
 * @return string Formatted phone number
 */
function format_phone($unformatted_phone_number) {
    $ph = $unformatted_phone_number;
    if (strlen($unformatted_phone_number . '') == 10) {
        return '(' . substr($ph, 0, 3) . ') ' . substr($ph, 3, 3) . '-' . substr($ph, 6, 4);
    } else {
        return 'err';
    }
}

/**
 * Trims and sets the value of the object passed.
 * @param String $value
 */
function trim_value(&$value) {
    $value = trim($value);
}

/**
 * Gets the unique artists for this organization
 */
function get_artists() {
    // Get a reference to the controller object
    $CI = get_instance();
    // You may need to load the model if it hasn't been pre-loaded
    $CI->load->model('arrangement_model');

    $rows = $CI->arrangement_model->get_unique();
    $html = "";
    if (is_array($rows)) {
        foreach ($rows as $row) {
            $html .= '<div class="item" data-value="' . $row['artist'] . '">' . $row['artist'] . '</div>';
        }
        return $html;
    } else {
        return FALSE;
    }
}

/**
 * Gets the unique tags
 */
function get_tags() {
    // Get a reference to the controller object
    $CI = get_instance();
    // You may need to load the model if it hasn't been pre-loaded
    $CI->load->model('song_model');

    $all_songs = $CI->song_model->get();
    $tags = array();
    foreach ($all_songs as $song) {
        $song_tags = json_decode($song['tags'], TRUE);
        foreach ($song_tags as $tag) {
            array_push($tags, $tag);
        }
    }
    $unique_tags = array_unique($tags);
    sort($unique_tags);
    $html = "";
    foreach ($unique_tags as $tag) {
        $html .= '<div class="item" data-value="' . $tag . '">' . $tag . '</div>';
    }
    return $html;
}

/**
 * Helper function for pagination.
 * @param int $num_rows Totla number of rows
 * @param int $page Current page
 * @return array Array of indexes including the previous and next indexes.
 */
function pagination($num_rows, $page) {
    $pagination = array();
    $pagination['last_page'] = $last_page = ceil($num_rows / 10); //cast to int
    $pagination['first_page'] = $first_page = 1;
    $pagination['prev'] = ($page == 1 ? '' : $page - 1);
    $pagination['next'] = ($page == $last_page ? '' : $page + 1);
    //backwards
    $current = $page - 1;
    while ($current > $page - 3 && $current >= 1) {
        $pagination['pages'][$current] = $current;
        $current--;
    }
    $pagination['pages'][$page] = $page;
    //forwards
    $current = $page + 1;
    while ($current < $page + 3 && $current <= $last_page) {
        $pagination['pages'][$current] = $current;
        $current++;
    }
    sort($pagination['pages']);
    return $pagination;
}

function recaptcha_validation($g_recaptcha_response, $g_recaptcha_secret) {
    //set POST variables
    $url = 'https://www.google.com/recaptcha/api/siteverify';
    $remoteip = $_SERVER['REMOTE_ADDR'];
    //open connection
    $ch = curl_init();
    //set the url, number of POST vars, POST data
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "secret=$g_recaptcha_secret&response=$g_recaptcha_response&remoteip=$remoteip");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //execute post
    $result = curl_exec($ch);
    //close connection
    curl_close($ch);

    $json_response = json_decode($result, TRUE);
    if (!is_null($json_response)) {
        return $json_response['success'];
    } else {
        throw new Exception('reCAPTCHA response invalid.%Validation Error', 500);
    }
}

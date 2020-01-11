<?php

include_once("init.php");

$ch = curl_init();

$postData = array(
    'session_token' => $_GET['session_token'],
    'api_key' => $ini['api_key'],
);

curl_setopt($ch, CURLOPT_URL,"http://broskies.gtdu.org/api/");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));

$server_output = json_decode(curl_exec($ch));

curl_close($ch);

// Standard Access                  Admin Access
if ($server_output->level == 1 || $server_output->level == 2) {
    $_SESSION['token'] = $_GET['session_token'];
    $_SESSION['level'] = $server_output->level;
    $_SESSION['name'] = $server_output->name;
    $_SESSION['email'] = $server_output->email;
    header("Location: dashboard.php");
    die();
} else {
    http_response_code(500);
    exit("Invalid information provided");
}

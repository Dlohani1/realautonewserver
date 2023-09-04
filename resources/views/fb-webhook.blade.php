<?php

$challenge = $_REQUEST['hub_challenge'];
$verify_token = $_REQUEST['hub_verify_token'];
//error_log($verify_token);

//error_log('dd');
if ($verify_token === 'abc123') {
echo $challenge;
//error_log('b '.$challenge);
}

$input = json_decode(file_get_contents('php://input'), true);
error_log(print_r($input, true));
error_log('ab');


?>

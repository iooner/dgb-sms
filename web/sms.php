<?php
include('dgbsms.class.php');
$sms = new DGBSMS();

$pending = $sms->pending();
print_r($pending);

$messages = $sms->messages();
print_r($messages);
?>

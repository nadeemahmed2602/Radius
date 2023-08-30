<?php
date_default_timezone_set('Asia/Kolkata');
$logDateTime = date('Y-m-d H:i:s', time());
file_put_contents('logs/logs1.txt', "\n" .'*********:Payload Data suceess  ' . $logDateTime . '*********', FILE_APPEND);

?>
<?php
file_put_contents('test-cron-job.txt', "\n" .'*********:Payload Data  ' . gmdate('Y-m-d H:i:s') . ' ' . 'Cronjob Called Successfully!' . '*********', FILE_APPEND);
?>
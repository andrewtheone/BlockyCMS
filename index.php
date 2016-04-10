<?php


$start_time = microtime(TRUE);

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__."/app/bootstrap.php";

$end_time = microtime(TRUE);

echo $end_time - $start_time;
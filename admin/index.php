<?php

$host = $_SERVER['HTTP_HOST'];
if(!preg_match('/http:\/\//', $host)) {
	$host = 'http://'.$host ;
}
$host = rtrim($host, '/').'/index.php/AdminPublic-adminlogin';

header("Location:{$host}");

?>
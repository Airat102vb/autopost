<?php
$connect = mysqli_connect($hostname, $username, $password, $database) or trigger_error(mysqli_error(),E_USER_ERROR);
mysqli_select_db($connect, $database);
mysqli_query($connect,"SET NAMES utf8");

/////// Формат даты
$date_format_php='Y-m-d';
$date_null='1970-01-01 00:00:00';

?>

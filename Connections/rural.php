<?php
$hostname_rural = "localhost";
$database_rural = "aa_caraba_vacio";
$username_rural = "cosa";
$password_rural = "moratalla";
$rural = mysqli_connect($hostname_rural, $username_rural, $password_rural, $database_rural) or trigger_error(mysql_error(),E_USER_ERROR);
mysqli_set_charset($rural, 'utf8');
?>
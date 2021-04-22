<?php 
$data_bd = unserialize($_COOKIE["sql_info"], ["allowed_classes" => false]);
$servername = $data_bd['servername'];
$login = $data_bd['login'];
$pass = $data_bd['pass'];

$name_BD = $_COOKIE['DB_done'];

$mysqli = new mysqli($servername,$login,$pass,$name_BD);
	if ($mysqli->connect_error) {
		echo "Połączenie nie powiodło się: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
	}

?>
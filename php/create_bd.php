<?php 
function filter_val($val) {
return filter_var(trim($val), FILTER_SANITIZE_STRING);
}

if (isset($_POST['login_to_bd_but'])) {

	$servername = $_POST['name_MySQL'];
	$login = $_POST['loginSQL'];
	$pass = $_POST['passSQL'];
	// $login_md5 = md5("qwerty".$login."321");
	// $pass_md5 = md5("qwerty".$pass."321");
	$sql_info = [
		"servername"=>$servername,
		"login"=>$login,
		"pass"=>$pass
	];
	$mysqli = new mysqli($servername,$login,$pass);
	if ($mysqli->connect_error) {
		setcookie("MySQL_Error","Połączenie do MySQL nie powiodło się :(", time()+3600,'/');
		$error = "Połączenie nie powiodło się: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
		echo "Połączenie do MySQL nie powiodło się :(";
		die();
	}else {
		setcookie("sql_info", serialize($sql_info), time()+3600,'/');
		$mysqli->close();
		header('Location: ' . $_SERVER['HTTP_REFERER']);
	}
}
if (isset($_POST['crt_bd_but'])) {

	$error = "";
	$mysqli ="";

	$name_of_bd = filter_val($_POST['name_of_bd_MySQL']);
	$sql_info = [];
	if (isset($_COOKIE['sql_info'])) {
		$sql_info = unserialize($_COOKIE["sql_info"], ["allowed_classes" => false]);
	}else {
		die();
	}
	// var_dump($sql_info);
	$servername = $sql_info['servername'];
	$login=$sql_info['login'];
	$pass=$sql_info['pass'];
	// $login=md5("qwerty".$login_md5."321");
	// $pass=md5("qwerty".$pass_md5."321");
	$mysqli = new mysqli($servername,$login,$pass);
	if ($mysqli->connect_error) {
		$error = "Połączenie nie powiodło się: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
	}else {
		$mysqli->query("DROP DATABASE IF EXISTS ".$name_of_bd);
		if (!$mysqli->query("DROP DATABASE IF EXISTS ".$name_of_bd) ||
		    !$mysqli->query("CREATE DATABASE ".$name_of_bd)) {
		    $error = "Nie udało się stworzyć bazę danych: (" . $mysqli->errno . ") " . $mysqli->error;
		}
		if (!$mysqli->select_db($name_of_bd)) {
			$error = "Nie udało się połączyć z bazą danych: (" . $mysqli->errno . ") " . $mysqli->error;
		}
		$sql_users = "CREATE TABLE users (
		id int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
		name varchar(32) NOT NULL,
		email varchar(32) NOT NULL,
		password varchar(32) NOT NULL,
		data date NOT NULL,
		PRIMARY KEY (id)
		)ENGINE=InnoDB DEFAULT CHARSET=utf8;";

		$sql_orders = "CREATE TABLE orders (
		id int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
		ip varchar(32) NOT NULL,
		name_of_product varchar(32) NOT NULL,
		phone varchar(32) NOT NULL,
		data date NOT NULL,
		PRIMARY KEY (id)
		)ENGINE=InnoDB DEFAULT CHARSET=utf8;";

		if (!$mysqli->query($sql_users)||
			!$mysqli->query($sql_orders)) {
			$error = "Nie udało się stworzyć tabelę: (" . $mysqli->errno . ") " . $mysqli->error;
		}
		if ($error == "") {
			setcookie("MySQL_Error",$error, time()+3600,'/');
			header('Location: ' . $_SERVER['HTTP_REFERER']);
		}
	}

	// setcookie("MySQL_Error","Baza danych jest stworzona :)", time()+3600,'/');

	$mysqli->close();
	// unset($_COOKIE["sql_info"]);
	// setcookie("sql_info", null, -1, '/');
	setcookie("DB_done",$name_of_bd, time()+3600,'/');
	header('Location: ' . $_SERVER['HTTP_REFERER']);
}

?>
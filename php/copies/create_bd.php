<?php 
function filter_val($val) {
return filter_var(trim($val), FILTER_SANITIZE_STRING);
}

if (isset($_POST['login_to_bd_but'])) {

	$servername = $_POST['name_MySQL'];
	$login = $_POST['loginSQL'];
	$pass = $_POST['passSQL'];

	$login_md5 = md5("qwerty".$login."321");
	$pass_md5 = md5("qwerty".$pass."321");

	$sql_info = [
		"servername"=>$servername,
		"login"=>$login_md5,
		"pass"=>$pass_md5
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
	$servername = $sql_info['servername'];
	$login_md5=$sql_info['login'];
	$pass_md5=$sql_info['pass'];
	$login=md5("qwerty".$login_md5."321");
	$pass=md5("qwerty".$pass_md5."321");

	var_dump($login);

	$mysqli = new mysqli($servername,$login,$pass);
	if ($mysqli->connect_error) {
		$error = "Połączenie nie powiodło się: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
	}else {

		if (!$mysqli->query("DROP DATABASE IF EXISTS".$name_of_bd) ||
		    !$mysqli->query("CREATE DATABASE".$name_of_bd)) {
		    $error = "Nie udało się stworzyć bazę danych: (" . $mysqli->errno . ") " . $mysqli->error;
		}
		if (!$mysqli->select_db($name_of_bd)) {
			$error = "Nie udało się połączyć z bazą danych: (" . $mysqli->errno . ") " . $mysqli->error;
		}
		$sql_products = "CREATE TABLE products (
				id int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
				name varchar(32) NOT NULL,
				price varchar(32) NOT NULL,
				gram varchar(32) NOT NULL,
				img varchar(64) NOT NULL,
				PRIMARY KEY (id)
				)ENGINE=InnoDB DEFAULT CHARSET=utf8;";
		$sql_cart = "CREATE TABLE cart(
				id int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
				user_id varchar(10) NOT NULL,
				product_id varchar(10) NOT NULL,
				price varchar(16) NOT NULL,
				gram varchar(16) NOT NULL,
				ordered varchar(4),
				PRIMARY KEY (id)
				)ENGINE=InnoDB DEFAULT CHARSET=utf8;";
		$sql_orders = "CREATE TABLE orders(
				id int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
				user_id varchar(10) NOT NULL,
				product_id varchar(10) NOT NULL,
				price varchar(16) NOT NULL,
				gram varchar(16) NOT NULL,
				email varchar(32) NOT NULL,
				comment varchar(64) NOT NULL,
				data date,
				PRIMARY KEY (id)
				)ENGINE=InnoDB DEFAULT CHARSET=utf8;";
		$sql_user = "CREATE TABLE user(
				id int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
				adres varchar(32) NOT NULL,
				PRIMARY KEY (id)
				)ENGINE=InnoDB DEFAULT CHARSET=utf8;";

		if (!$mysqli->query($sql_products) ||
			!$mysqli->query($sql_cart) ||
			!$mysqli->query($sql_user) || 
			!$mysqli->query($sql_orders)
			) {
			$error = "Nie udało się stworzyć tabelę: (" . $mysqli->errno . ") " . $mysqli->error;
		}

	foreach ($post_info as $key => $value) {
			$sql = "INSERT INTO products (name,price,gram,img) VALUES (
			'".$value['name']."',
			'".$value['price']."',
			'".$value['gram']."',
			'".$value['img']."'
			)";

		if (!$mysqli->query($sql)){
			$error = "Nie udało się wkleić dane do tabeli: (" . $mysqli->errno . ") " . $mysqli->error;
		}
	}

		$sql2 = $sql = "INSERT INTO products (name,price,gram,img) VALUES ('Kawa BIO','105','1000','img/coffee-img/new/BIO.jpg')";
		if (!$mysqli->query($sql2)){
			$error = "Nie udało się wkleić dane do tabeli: (" . $mysqli->errno . ") " . $mysqli->error;
		}

		if (!$mysqli->query("INSERT INTO user (adres) VALUES ('".$_SERVER['REMOTE_ADDR']."')")){
			$error = "Nie udało się wkleić dane do tabeli: (" . $mysqli->errno . ") " . $mysqli->error;
		}
		

		if ($error == "") {
			setcookie('close_PUH', null, -1, '/');
			setcookie("close_PUH","Mode_online", time()+3600,'/');
			header('Location: ' . $_SERVER['HTTP_REFERER']);
		}
	}
	$mysqli->close();
}

?>
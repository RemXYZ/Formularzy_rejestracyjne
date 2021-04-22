<?php 
$mysqli = NULL;
if (isset($_COOKIE["sql_info"]) && isset($_COOKIE['DB_done'])) {
	require_once "bd.php";
}
if (!isset($mysqli)) {
header('Location: ' . $_SERVER['HTTP_REFERER']);
}

function filter_val($val) {
return filter_var(trim($val), FILTER_SANITIZE_STRING);
}

if (isset($_POST['do_log_in'])) {

// $name = filter_val($_POST['name']);
$pass = filter_val($_POST['pass']);
$email = $_POST['email'] ?? NULL;
$email_f = filter_var($email, FILTER_SANITIZE_EMAIL);
$pass_md5 =md5("qwerty".$pass."321");

$user = $mysqli->query("SELECT * FROM `users` WHERE `email` = '$email_f' AND `password` = '$pass_md5' ")->fetch_assoc();

if ($user == "") {
	setcookie("er_login", "Podany zły login lub hasło", time()+3600,'/');
	// $_SESSION['er_login'] = "Podany zły login lub hasło";
	// $_SESSION['mem_name_l'] = $name;
	setcookie("mem_email_log",$email, time()+3600,'/');
	setcookie("mem_pass_log",$pass, time()+3600,'/');

	$mysqli->close();
	header('Location: ' . $_SERVER['HTTP_REFERER']);
	// $_SESSION['mem_pass_l'] = $pass;
}else {
		unset($_COOKIE['mem_name_l']);
		setcookie("mem_name_l", null, -1, '/');
		unset($_COOKIE['mem_pass_l']);
		setcookie("mem_pass_l",null,-1,"/");

		setcookie("Cookie_user_id",$user["id"], time()+3600,'/');
		setcookie("Cookie_user_name",$user["name"], time()+3600,'/');

		$mysqli->close();
		header('Location: ' . $_SERVER['HTTP_REFERER']);
		// setcookie('Cookie_user_id',$user['id']);
		// setcookie('Cookie_user_name',$user['name']);
}

}
?>
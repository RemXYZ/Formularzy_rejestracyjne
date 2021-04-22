<?php 
$mysqli = NULL;
if (isset($_COOKIE["sql_info"]) && isset($_COOKIE['DB_done'])) {
	require_once "bd.php";
}

function filter_val($val) {
return filter_var(trim($val), FILTER_SANITIZE_STRING);
}
//lg = length
function lg ($val) {
	return mb_strlen($val);
}
$Do_signup = $_POST['do_sign_up'] ?? NULL;

if (isset($Do_signup)) {
$name = $_POST['name'] ?? NULL;
$email = $_POST['email'] ?? NULL;
$pass = $_POST['pass'] ?? NULL;

//d_n = do next!
$d_n = true;

if ($name !== NULL) {
$name = filter_val($name);

if (ctype_alnum($name) == false) {
	$d_n = false;
	setcookie("err_name", "Nazwa użytkownika może się składać tylko z liczb i liter", time()+3600,'/');
}

if (lg($name) < 3 || lg($name) > 32) {
	$d_n = false;
	setcookie("err_name", "Nazwa użytkownika musi posiadać od 3 do 32 znaków", time()+3600,'/');
	}
//CZESC SPRAWDZENIA Z DANYCH Z BAZY DANYCH
if (isset($mysqli)) {

	$user_info = $mysqli->query("SELECT * FROM users WHERE name = '$name' ")->fetch_assoc();
	if (isset($user_info)) {
		$d_n = false;
		setcookie("err_name", "Podany nickname jest zajęty", time()+3600,'/');
		// $_SESSION['er_name'] = "Podany nickname jest zajęty";
	}
}
//KONIEC NAME
}else {
$d_n = false;
}


if ($email !== NULL) {
	if (lg($email) < 3 || lg($email) > 64) {
		$d_n = false;
		setcookie("err_email", "Email musi posiadać od 3 do 64 znaków", time()+3600,'/');
		// $_SESSION['err_email'] = "Email musi posiadać od 3 do 32 znaków";
	}
	$email_f = filter_var($email, FILTER_SANITIZE_EMAIL);
	
	if ((filter_var($email_f, FILTER_VALIDATE_EMAIL)==false) || ($email_f != $email)) {
		$d_n = false;
		setcookie("err_email", "Email nie jest poprawny", time()+3600,'/');
		// $_SESSION['err_email'] = "Email nie jest poprawny";
	}
if (isset($mysqli)) {
	$user_info = mysqli_fetch_assoc(mysqli_query($mysqli,"SELECT * FROM users WHERE email = '$email' "));
	if (isset($user_info)) {
		$d_n = false;
		setcookie("err_email", "Podany email jest zajęty", time()+3600,'/');
		// $_SESSION['er_email'] = "Podany email jest zajęty";
	}
}

//KONIEC EMAIL
}else {
$d_n = false;
}


if ($pass !== NULL) {
$pass = filter_val($pass);
	if (lg($pass) < 8 || lg($pass) > 32) {
		$d_n = false;
		setcookie("err_pass", "Hasło musi posiadać od 8 do 32 znaków", time()+3600,'/');
	}
//KONIEC HASLO
}else {
$d_n = false;
}

if (!isset($_POST['accept'])) {
$d_n = false;
setcookie("err_accept", "Zaakceptuj warunki korzystania z usługi", time()+3600,'/');
}



if ($d_n) {
//JESLI NIE MA BLEDOW, TO ODSYLAM UZYTKOWNIKA DO STRONY GLOWNEJ BEZ BLEDU
setcookie("sign_up_is", 1, time()+3600,'/');

$memory = ["name"=>$name,"email"=>$email,"pass"=>$pass];
setcookie("memory", serialize($memory), time()+3600,'/');

if (isset($mysqli)) {
	$pass_md5 =md5("qwerty".$pass."321");
	$sql = "INSERT INTO `users` (`name`, `email`, `password`, `data`) VALUES ('$name', '$email', '$pass_md5', NOW())";
	$mysqli->query($sql);
	$mysqli->close();
}


header('Location: ' . $_SERVER['HTTP_REFERER']);
}else {
//JESLI SQ BLEDY, TO ODSYLAM UZYTKOWNIKA DO STRONY GLOWNEJ z BLEDEM

setcookie("sign_up_is", 0, time()+3600,'/');

$memory = ["name"=>$name,"email"=>$email,"pass"=>$pass];
setcookie("memory", serialize($memory), time()+3600,'/');
if (isset($mysqli)) {
	$mysqli->close();
}
header('Location: ' . $_SERVER['HTTP_REFERER']);
}

//SEND MESSAGE EMAIL
//SOURCE: https://www.youtube.com/watch?v=DC3Q-FEchnA&list=LL&index=1&ab_channel=ITDoctor
// $message = "Hello, it's me, your web site !";
// $to = "******@gmail.com";
// $from = "******@gmail.com";
// $subject = "Topic of message";

// $headers = "From:".$from."\r\n".
// "Reply-To:".$from."\r\n".
// "Content-type:text/plain charset=utf-8\r\n";
// 	mail($to,$subject,$message,$headers);

}
?>
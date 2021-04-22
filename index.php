<?php 

// var_dump($_COOKIE);
// $memory = [];
// setcookie("memory", serialize($memory), time()+3600,'/');
// unserialize($_COOKIE["memory"], ["allowed_classes" => false]);
if (isset($_POST['del_CK'])) {
if (isset($_SERVER['HTTP_COOKIE'])) {
    $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
    foreach($cookies as $cookie) {
        $parts = explode('=', $cookie);
        $name = trim($parts[0]);
        setcookie($name, '', time()-1000);
        setcookie($name, '', time()-1000, '/');
    }
}
unset($_POST['del_CK']);
header('Location: ' . $_SERVER['HTTP_REFERER']);
}

//LOGIN TO MYSQL ERRORS;
$MySQL_Error = "";
if (isset($_COOKIE["MySQL_Error"])) {
$MySQL_Error = $_COOKIE["MySQL_Error"];
unset($_COOKIE["MySQL_Error"]);
setcookie("MySQL_Error", null, -1, '/');
}

//SIGNUP/LOGIN PART ERRORS;
class Errors_msg {

public $errors_names = ["err_name",'err_email','err_pass','err_accept','general_err'];
// function __construct() {}
public function add_name_msg($new_error) {
	if ($new_error !== undefined) {
		array_push($errors_names,$new_error);
	}
}
public $v_err_msg = [];
public function show_err_msg_C($del) {
	$e_array = $this->errors_names;
	foreach ($e_array as $key => $value) {
			$C_name = $value;
			$var = $_COOKIE[$C_name]??NULL;
			if ($del == 1 && $var !== NULL) {
				unset($_COOKIE[$C_name]);
				setcookie($C_name, null, -1, '/');
			}
			if ($var !== NULL) {
				echo "<style>
				.error {
					display:block;
					color:red;
				}</style>";
			}
			$this->v_err_msg[$value] = $var;
	}
	return $this->v_err_msg;
}

//END OF CLASS
}
//END OF CLASS
$SG_errors = new Errors_msg ();
$v_errors = $SG_errors-> show_err_msg_C(1);


$memory = ["name"=>"","email"=>"","pass"=>""];
// var_dump($_COOKIE);
if (isset($_COOKIE['sign_up_is']) && !$_COOKIE['sign_up_is']) {
	$memory = unserialize($_COOKIE["memory"], ["allowed_classes" => false]);
	unset($_COOKIE["memory"]);
	setcookie("memory", null, -1, '/');
	unset($_COOKIE['sign_up_is']);
	setcookie("sign_up_is", null, -1, '/');
}

//LOGIN ERRORS 
if (isset($_COOKIE['er_login'])) {
	$er_log = $_COOKIE['er_login']; 
	unset($_COOKIE['er_login']);
	setcookie("er_login",null,-1,"/");}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Rem Karablin</title>

<link rel="stylesheet" href="css/style.css">

<script>
	
function show_code (wch) {
	let el = document.querySelector("#"+wch);
	let el_st = window.getComputedStyle(el,null).display;
	if (el_st == "none") {
		el.style.display = "block";
	}
	if (el_st == "block") {
		el.style.display = "none";
	}
}

</script>

<script src="js/script.js"></script>

</head>
<body>
<div class="wrapper">

<!-- 		<div class="code">
			<button class="code_but">&lt;/&gt;</button>
			<div class="code_css"></div>
			<div class="code_html"></div>
			<div class="code_js"></div>
		</div> -->

	<header class="Rcenter"><div class="row-header Rcenter"><h2>Przykłady formularzy</h2></div></header>
	<div class="content Rcenter">
<!-- <p style="font-family: arial; text-align: center;">/*Strona jest nadal tworzona. PHP w logowaniu i <br>zamówieniu towarów na razie nie działa,<br> więc poprosiłbym jeszcze troche czasu,<br> również będę robił obsługe MySQL*/</p>
<br> -->
<form action="index.php" method="POST">
<button name="del_CK" class="del_CK">Usunąć wszystkie cookie</button>
</form>

<h3>1.Tworzenie bazy danych</h3>

<div class="create_bd">
	<?php if (!isset($_COOKIE['sql_info'])): ?>
	<form action="php/create_bd.php" method="POST">
		<p class="crt_bd_title">Logowanie do phpmyadmin</p>
		<p class="crt_bd_text">Nazwa hosta</p>
		<input type="text" class="crt_bd_inp" name="name_MySQL" value="localhost">
		<p class="crt_bd_text">Login</p>
		<input type="text" class="crt_bd_inp" value="root" name="loginSQL">
		<p class="crt_bd_text" name="pass">Hasło</p>
		<input type="text" class="crt_bd_inp" name="passSQL">
		<button type="submit" class="crt_bd_but" name="login_to_bd_but">Dalej</button>
	</form>
	<?php else: if (!isset($_COOKIE['DB_done'])):?>

	<form action="php/create_bd.php" method="POST">
		<p class="crt_bd_title">Tworzenie bazy danych</p>
		<p class="crt_bd_text">Nazwa bazy danych</p>
		<input type="text" class="crt_bd_inp" name="name_of_bd_MySQL" value="rem_karablin">
		<button type="submit" class="crt_bd_but" name="crt_bd_but">Dalej</button>
		<p class="crt_bd_text_alrt">Uwaga! Przy kliknięciu na "dalej" program usunie bazę danych z nazwą która była wpisana w inpucie i stworze jej ponownie z tabelami:<br>
		"users";<br>
		"orders";</p>
	</form>
	<?php else: ?>
		<form action="index.php" method="POST">
			<p class="crt_bd_title">Baza danych jest stworzona</p>
			<p class="crt_bd_text">Nazwa bazy danych</p>
			<p class="crt_bd_text"><?=$_COOKIE['DB_done']??NULL ?></p><br>
			<button name="del_CK" class="del_CK_BD crt_bd_but">Usunąć wszystkie cookie</button>
		</form>
<?php endif;endif; ?>
	<p style="color:red;"><?=$MySQL_Error?></p>
</div>


<h3 style="margin-bottom: 20px;">2.Formularz rejestracyjny</h3>

<div class="code">
			<button class="code_but" onclick="show_code('c0')" title="show_code">&lt;/&gt;</button>
	<div class="just_code" id="c0">
		<div class="code_css code_text">
		<p>&lt;style&gt;</p>
		<button class="code_but" onclick="show_code('c0css')" title="show_code">close css</button>
		<hr>
<pre id="c0css">
.sign_up {
	padding: 60px 30px 50px;
	border: 1px solid #e6eaf2;
	border-radius: 20px;
	background: #f9fbff;
	box-shadow: 0 0 10px 2px rgba(0,0,0,0.1);
}
.sign_up_title {
	font-weight: 600;
	font-family: sans-serif;
	font-size: 26px;
	letter-spacing: -0.5px;
}
.SUinp {
	outline: none;
	margin-top: 10px;
	font-family: 26px;
	padding: 8px 180px 8px 5px;
	border-radius: 6px;
	border: 1px solid #393b44;
}
.SUinpCkB {
	margin-top: 10px;
}
.SU_text {
	font-family: sans-serif;

}
.SU_text a {
	text-decoration:none;
}
.SU_but {
	margin-top: 30px;
	padding: 5px 12px;
	font-family: arial;
	font-weight:600;
	font-size: 15px;
	color: white;

	border-radius: 10px;
	border: none;
	background: #258af7;

	cursor: pointer;
	outline:none;
}
.SU_but:hover {
	box-shadow: 0 0 12px 2px #a9d3f2;
}
.pass_restrictions {
	font-family: arial;
	font-size: 14px;
	color: #59acff;
}
.error {
	font-family: arial;
	margin-top: 5px;
}
<pre>
		</div>
		<div class="code_html code_text">
		<p>&lt;html&gt;</p>
		<button class="code_but" onclick="show_code('c0html')" title="show_code">close html</button>
		<hr>
<pre id="c0html">
&lt;div class="sign_up"&gt;
	&lt;p class='sign_up_title'&gt;Sign Up&lt;/p&gt;
	&lt;form action="service.php" autocomplete="off"&gt;
		&lt;input type="text" class="SUinp" placeholder="Nazwa użytkownika" name="name"&gt;
		&lt;br&gt;
		&lt;span class="name_msg error"&gt;&lt;/span&gt;
		&lt;input type="text" class="SUinp" placeholder="Email" name="email"&gt;&lt;br&gt;
		&lt;span class="email_msg error"&gt;&lt;/span&gt;
		&lt;input type="password" class="SUinp" placeholder="Hasło" name="pass"&gt;&lt;br&gt;
		&lt;p class="pass_restrictions"&gt;min. 8 znaków • wielka litera • mała litera • cyfra&lt;/p&gt;
		&lt;span class="pass_msg error"&gt;&lt;/span&gt;
		&lt;input type="checkbox" class="SUinpCkB" name="accept"&gt; 
		&lt;span class="SU_text"&gt;Akceptuję &lt;a href="#"&gt; 
		Warunki korzystania z usługi&lt;/a&gt;&lt;br&gt; i &lt;a href="#"&gt; 
		Politykę prywatności.&lt;/a&gt;&lt;/span&gt;
		&lt;span class="accept_msg error"&gt;&lt;/span&gt;
		&lt;br&gt;
		&lt;button type="submit" class="SU_but"&gt;Zarejestruj się&lt;/button&gt;
	&lt;/form&gt;
&lt;/div&gt;
&lt;script&gt;
const but1 = document.querySelector(".SU_but");
const Sreg = new Send_reg(but1);
but1.addEventListener("click",send_reg_form);
function send_reg_form () {
	Sreg.add_inps(event);
	Sreg.check_reg(event,but1,1);
}
&lt;/script&gt;
</pre>
		</div>
		<div class="code_js code_text">
		<p>&lt;script&gt;</p>
		<button class="code_but" onclick="show_code('c0js')" title="show_code">close html</button>
		<hr>
<pre id="c0js">
function setCSS (el,mix,arg2) {
	if (typeof mix == "object") {
		for (const [key, value] of Object.entries(mix)) {
			el.style[key] = value;
		}
	}else if (typeof mix == "string" && typeof arg2 == "string") {
		el.style[mix] = arg2;
	}
}
class Send_reg {
constructor(but) {
this.e = "";

if (but.tagName == "FORM") {
	this.myForm = but;
	console.log(this.myForm)
	for (let el of this.myForm.elements) {
		if (el.matches('button[type="submit"]')) {
			this.regBut = el;
    	}
  	}
}else {
	this.regBut = but;
	this.myForm = this.regBut.form;
}
this.inps = {
	"name":{},
	"email":{},
	"pass":{},
	"accept":{},
	"isW":false
};

this.add_inps();

}
//INPUT PART
add_inps (e,EL) {
if (e === undefined) {
	e = event;
}
for (let [key,v] of Object.entries(this.myForm.elements)){
	if (v.tagName == "INPUT" && !isNaN(key)) {
		//To po porostu sprawdza nazwe inputa i wpisuje element w juz przygotowany object, jesli znajdzie jakis nie istniejacy element, to wyswietli sie blad
		if (this.inps[v.name] !== undefined) {
			this.inps[v.name] = {"el":v,"v":v.value};
			this.inps.isW = true;
			if (v.changing === undefined) {
				v.changing = true;
				v.my_name = v.name;
				v.addEventListener("change",()=>{
				this.check_reg(event,v,0)});
			}
		}else {
			console.error("Input '"+v.name+"' has incorrect name, or the total number of inputs must be 4, add the given name to the object 'inps'");
			console.error(v);
			this.inps.isW = false;
		}
	}
}
}//END OF ADD INPS
//ALERT PART
//CHAKING PART
check_reg(e,$el,isBut) {
var stop_e = false;
const msgs = {
	"name":{
		"el":"",
		check:function(inp) {
			this.el.innerHTML = "";
			if (inp.value.length<=2) {
				this.el.innerHTML = "Proszę wpisać co najmniej 3 znaki.";
				var error = true;
			}
			if (error) {
				setCSS(this.el,"display","block");
				setCSS(this.el,"color","red");
				return true;
			}else {
				return false;
			}
		}
	},

	"email":{
		"el":"",
		check:function(inp) {
			this.el.innerHTML = "";
			const regex = /^\S+@\S+$/;
			if (inp.value == "" || !regex.test(String(inp.value))) {
				this.el.innerHTML = "Niepoprawny adres e-mail";
				setCSS(this.el,"display","block");
				setCSS(this.el,"color","red");
				return true;
			}

			return false;
		}
	},

	"pass":{
		"el":"",
		check:function(inp) {
			this.el.innerHTML = "";
			let regex1 = /[A-Z]/;
			let regex2 = /[0-9]/;
			let regex3 = /[a-z]/;
			let error = false;
			if (!regex3.test(inp.value)) {
				this.el.innerHTML = "Proszę wpisać co najmniej jedną małą literę.";
				error = true;
			}
			if (!regex1.test(inp.value)) {
				this.el.innerHTML = "Proszę wpisać co najmniej jedną wielką literę.";
				error = true;
			}
			if (!regex2.test(inp.value)) {
				this.el.innerHTML = "Proszę wpisać co najmniej jedną cyfrę.";
				error = true;
			}
			if (inp.value.length<=7) {
				this.el.innerHTML = "Proszę wpisać co najmniej 8 znaków.";
				error = true;
			}
			if (error) {
				setCSS(this.el,"display","block");
				setCSS(this.el,"color","red");
				return true;
			}else {
				return false;
			}
		}
	},

	"accept":{
		"el":"",
		check:function(inp) {
			this.el.innerHTML = "";
			if (!inp.checked) {
				this.el.innerHTML = "Zaakceptuj warunki korzystania z usługi";
				setCSS(this.el,"display","block");
				setCSS(this.el,"color","red");
				return true;
			}
			return false;
		}
	}
}

//ADDING INPUT TO THE OBJECT
for (let v of Object.values(this.myForm.querySelectorAll(".error"))) {
	let val = v.classList[0].replace("_msg","");
	//To po porostu sprawdza class spana i wpisuje element w juz przygotowany object, jesli znajdzie jakis nie istniejacy element, to wyswietli sie blad
	//NAZWA SPANA MUSI BYC TAKA SAMA JAK I INPUT
	if (msgs[val] !== undefined) {
		msgs[val].el = v;
	}else {
		console.error("Span '"+v.classList[0]+"' has incorrect class name, or the total number of inputs must be 4, add the given class to the object 'msgs'");
	}
}

//CHAKING ALL INPUTS
//isBut is 1 if i click button [type="submit"]
if (isBut == 1) {
	for (let [key,v] of Object.entries(msgs)) {
		var rtn_stop_e = v.check(this.inps[key].el);
		if (rtn_stop_e) {
			stop_e = true;
		}
	}
}
//CHECKING ONE INPUT
if (isBut == 0) {
	//my name is name from object this.inps
	stop_e = msgs[$el.my_name].check($el);
}
if (stop_e) {
	e.preventDefault();
}
}//END OF CHECK REG

//END OF CLASS
}
//END OF CLASS
</pre>
		</div>
	</div>
</div>

<div class="sign_up">
	<p class='sign_up_title'>Sign Up</p>
	<form action="php/sing_up.php" autocomplete="off" method="POST">
		<span style="display: block; margin-top: 5px" class="general_msg"><?=$v_errors["general_err"]?></span>
		<input type="text" class="SUinp" placeholder="Nazwa użytkownika" name="name" value="<?=$memory['name']?>">
		<br>
		<span class="name_msg error"><?=$v_errors["err_name"]?></span>
		<input type="text" class="SUinp" placeholder="Email" name="email" value="<?=$memory['email']?>"><br>
		<span class="email_msg error"><?=$v_errors["err_email"]?></span>
		<input type="password" class="SUinp" placeholder="Hasło" name="pass" value="<?=$memory['pass']?>"><br>
		<p class="pass_restrictions">min. 8 znaków • wielka litera • mała litera • cyfra</p>
		<span class="pass_msg error"><?=$v_errors["err_pass"]?></span>
		<input type="checkbox" class="SUinpCkB" name="accept"> <span class="SU_text">Akceptuję <a href="#"> Warunki korzystania z usługi</a><br> i <a href="#"> Politykę prywatności.</a></span>
		<span class="accept_msg error"><?php echo $v_errors["err_accept"]?></span>
		<br>
		<button type="submit" class="SU_but" name="do_sign_up">Zarejestruj się</button>
	</form>
</div>
<script>
const but1 = document.querySelector(".SU_but");
const Sreg = new Send_reg(but1);
but1.addEventListener("click",send_reg_form);
function send_reg_form () {
	Sreg.add_inps(event);
	Sreg.check_reg(event,but1,1);
}
</script>

<h3 style="margin-bottom: 20px; margin-top: 40px;">3.Formularz logowania</h3>
<?php if (isset($_COOKIE['Cookie_user_id'])) {
	echo "Dzień dobry: ".$_COOKIE['Cookie_user_name'];
} ?>
<div class="code">
	<button class="code_but" onclick="show_code('c1')" title="show_code">&lt;/&gt;</button>
	<div class="just_code" id="c1">
		<div class="code_css code_text">
		<p>&lt;style&gt;</p>
		<button class="code_but" onclick="show_code('c0css')" title="show_code">close css</button>
		<hr>
<pre id="c0css">
<pre>
		</div>
		<div class="code_html code_text">
		<p>&lt;html&gt;</p>
		<button class="code_but" onclick="show_code('c0html')" title="show_code">close html</button>
		<hr>
<pre id="c0html">
</pre>
		</div>
		<div class="code_js code_text">
		<p>&lt;script&gt;</p>
		<button class="code_but" onclick="show_code('c0js')" title="show_code">close html</button>
		<hr>
<pre id="c0js">
</pre>
		</div>
	</div>
</div>

<div class="sign_up">
	<p class='sign_up_title'>Log In</p>
	<form action="php/log_in.php" autocomplete="off" method="POST">
<!-- 		<input type="text" class="SUinp" placeholder="Nazwa użytkownika" name="name">
		<br>
		<span class="name_msg error"></span> -->
		<input type="text" class="SUinp" placeholder="Email" name="email" value="<?=$_COOKIE['mem_email_log']??NULL;?>"><br>
		<span class="email_msg error"></span>
		<input type="password" class="SUinp" placeholder="Hasło" name="pass" value="<?=$_COOKIE['mem_pass_log']??NULL;?>"><br>
		<p class="pass_restrictions">min. 8 znaków • wielka litera • mała litera • cyfra</p>
		<span class="pass_msg error"></span>
		<br>
		<button type="submit" name="do_log_in" class="LIN_but SU_but">Zaloguj się</button>
		<p class="log_err" style="color:red; margin-top: 10px;">
			<?=$er_log??NULL?>
		</p>
	</form>
</div>
<script>
const but2 = document.querySelector(".LIN_but");
const Slog = new Send_reg(but2);
but2.addEventListener("click",send_log_form);
function send_log_form () {
	Slog.add_inps(event);
	Slog.check_reg(event,but1,1);
}
</script>

<!-- <h3 style="margin-bottom: 20px; margin-top: 40px;">3.Formularz zamowienia towarów</h3>

<div class="code">
	<button class="code_but" onclick="show_code('c2')" title="show_code">&lt;/&gt;</button>
	<div class="just_code" id="c2">
		<div class="code_css code_text">
		<p>&lt;style&gt;</p>
		<button class="code_but" onclick="show_code('c0css')" title="show_code">close css</button>
		<hr>
<pre id="c0css">
<pre>
		</div>
		<div class="code_html code_text">
		<p>&lt;html&gt;</p>
		<button class="code_but" onclick="show_code('c0html')" title="show_code">close html</button>
		<hr>
<pre id="c0html">
</pre>
		</div>
		<div class="code_js code_text">
		<p>&lt;script&gt;</p>
		<button class="code_but" onclick="show_code('c0js')" title="show_code">close html</button>
		<hr>
<pre id="c0js">
</pre>
		</div>
	</div>
</div> -->

<!-- <div class="OrderProduct">
	<p class='OPr-title'>Zamówić towar</p>
	<form action="php/OrderProduct.php" autocomplete="off" method="POST">
<input list="products">
<datalist id="products">
<option value="RTX 3060">
<option value="GTX 1650">
<option value="Radeon RX 6700 XT">
<option value="Radeon PRO WX 3100">
<option value="Radeon RX 580">
</datalist>
	</form>
</div>

<script>
	
</script> -->

<!-- END OF CONTENT -->
	</div>	
<!-- END OF CONTENT -->		
</div>

</body>
</html>
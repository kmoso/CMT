<?php
	include("dbconfig.php");
	session_start();
	if (!isset($_SESSION['login_id'])) {
		header("Location: login.php");
	}
?>
<!DOCTYPE html>
<html>
<head>
<link href='http://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
<meta charset="UTF-8">
<title>Circuito Mexicano de Tenistas</title>
		<link rel="stylesheet" href="style.css">
</head>

<body>

<div class="logo"></div>
<div class="login-block">
    <h1>Login</h1>
    <form method="post" action="" name="loginform">
    <button type="submit">Crea usuario</button>
    </form>
<a href="sel_jug.php">Jugadores</a>
<a href="logout.php">Salir</a>
</div>

<?php
if($_SERVER["REQUEST_METHOD"] == "POST") {
	$va_options = [
	'cost' => 12,
	];
	$vc_Password = password_hash('NuevoCMT2018!', PASSWORD_BCRYPT, $va_options);  

	$vc_Sql="insert into cmt_usu_usuarios (usu_login, usu_password) ";
	$vc_Sql.="values ('Admin','".$vc_Password."') ";
	echo $vc_Sql;
}
?>


</body>

</html>
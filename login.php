<?php
	include("dbconfig.php");
	session_start();
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Acceso sistema CMT</title>
		<link rel="stylesheet" href="screenCMT.css">
	</head>
	<body>

		<header>
			<a class="exit" href="logout.php"></a>
		</header>

		<div class="login-block" align="center">
			<h1>Login</h1>
			<form method="post" action="" name="loginform">
				<input type="text" value="Escribe tu usuario" placeholder="Username" id="username" name="username" />
				<input type="password" value="Escribe tu contraseña" placeholder="Password" id="password" name="password" /><br>
				<button type="submit">Ingresar</button>
			</form>
		</div>
		
<?php
			if($_SERVER["REQUEST_METHOD"] == "POST") {
				// username and password received from loginform
				$vc_Username=mysqli_real_escape_string($vc_DbConfig,$_POST['username']);
				$vc_Password=mysqli_real_escape_string($vc_DbConfig,$_POST['password']);

				$vc_Sql="SELECT usu_id as id, usu_login as login, usu_password as passw FROM cmt_usu_usuarios WHERE usu_login='".$vc_Username."' ";
				//  echo $vc_Sql;
				
				$vr_ResultSet=mysqli_query($vc_DbConfig,$vc_Sql);
				$vR_RowPass=mysqli_fetch_array($vr_ResultSet,MYSQLI_ASSOC);

				if (!(password_verify($vc_Password, $vR_RowPass['passw']))) {
					$vn_Count=0;
					$vc_Error='<p align="center">Usuario o Contraseña no son v&aacutelidos</p>';
					printf("%s", $vc_Error);
				}
				else {
					$_SESSION['login_id'] = $vR_RowPass['id'];
					$_SESSION['login_user'] = $vR_RowPass['login'];
					echo '<script>window.location.assign("sel_ins.php")</script>';
//					header("location: sel_ins.php");
				}
			}
?>

	</body>
</html>
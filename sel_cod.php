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
		<meta charset="UTF-8">
		<title>Sistema CMT</title>
		<link rel="stylesheet" href="screenCMT.css">
	</head>
	<body>
		<div id="page">
<?php
	include("menu.php");
?>
			<form name="InsForm" onsubmit="validaForma()" method="POST" accept-charset="utf-8" enctype="multipart/form-data" >
<?php
			// Asignando valores
			$vc_Html = '';
			$vc_Html.='<table class="seleccion">';
			$vc_Html.='<tr class="encabezado"><td colspan="2">Mantenimiento de c&oacute;digos</td></tr>';
			$vc_Html.='</table>';
			$vc_Html.='<div class="main_grid">';
			$vc_Html.='<table class="grid">';
			$vc_Html.='<tr class="grid0">';
			$vc_Html.='<td align="center"><b>C&oacute;digo</b></td>';
			$vc_Html.='<td align="center"><b>Fch Vencimiento</b></td></tr>';

			// Información de Códigos
			$vc_Sql ="select cod_id as id, cod_codigo as nombre, cod_fch_vencimiento as vencimiento ";
			$vc_Sql.="from cmt_cod_codigos ";
			$vc_Sql.="where 1 = 1 ";
			$vc_Sql.="and cod_fch_aplicacion is null ";
			$vc_Sql.="and cod_fch_vencimiento >= sysdate()  ";
			$vc_Sql.="order by cod_fch_vencimiento, cod_id ";
//			echo $vc_Sql;
			$vn_i=1;
			$vr_ResultSet=mysqli_query($vc_DbConfig,$vc_Sql);
			if (!$vr_ResultSet) {
				$vc_Mensaje = '<font color="yellow">Error: '.mysqli_error($vc_DbConfig).' ejecutando '.$vc_Sql.'<br>Contacta al administrador del sitio<br></font>';
				printf("%s\n",$vc_Mensaje);
				exit(1);
			}
//			else echo $vc_Sql;

			$vR_RowIns=mysqli_fetch_array($vr_ResultSet,MYSQLI_ASSOC);
			$vc_Grid ='<tr class="grid'.$vn_i.'">';
			$vc_Grid.='<td align="center">'.$vR_RowIns['nombre'].'</td>';
			$vc_Grid.='<td align="center">'.$vR_RowIns['vencimiento'].'</td>';
			$vc_Grid.='</tr>';
			$vn_TotCodigos = 1;
			while($vR_RowIns = mysqli_fetch_assoc($vr_ResultSet))
			{
				if ( $vn_i == 1 ) $vn_i = 0;
				else $vn_i = 1;
				$vc_Grid.='<tr class="grid'.$vn_i.'">';
				$vc_Grid.='<td align="center">'.$vR_RowIns['nombre'].'</td>';
				$vc_Grid.='<td align="center">'.$vR_RowIns['vencimiento'].'</td>';
				$vc_Grid.='</tr>';
				$vn_TotCodigos++;
			}
			$vc_Grid.='<tr><td align="right"><b>Nueva imagen de fondo (1080x600):</b></td><td>';
			$vc_Grid.='<input type="file" name="tf_background_upload" accept=".png"></td></tr>';
			$vc_Grid.='<tr><td align="right"><b>Nueva imagen de fondo para celulares (430x240):</b></td>';
			$vc_Grid.='<td><input type="file" name="tf_background_mobile_upload" accept=".png"></td></tr>';
			$vc_Grid.='<tr><td align="right"><b>Nuevo logo página alterna (85x85):</b></td>';
			$vc_Grid.='<td><input type="file" name="tf_logo_upload" accept=".png"></td></tr>';
			$vc_Grid.='<tr><td class="button" colspan="2"><input type="submit" value="Generar/Actualizar"></td></tr>';
			$vc_Html.=$vc_Grid;
			$vc_Html.='</table>';
			$vc_Html.='</div>';
			$vc_Html.='<input type="hidden" name="tb_tot_codigos" id="tb_tot_codigos" value="'.$vn_TotCodigos.'">';

			printf("%s\n",$vc_Html);

			if($_SERVER["REQUEST_METHOD"] == "POST") {
				if (!isset($_POST['tb_tot_codigos'])) $vn_TotCodigos = 0;
				else $vn_TotCodigos=$_POST['tb_tot_codigos'];

				$va_options = [
				'cost' => 12,
				];

				// Subiendo comprobante
				$vc_TargetDir = "imgs/";
				$va_Acceptable = array(
								'image/png'
								);

				// Carga de archivo para display normales
				$vc_TargetFile = basename($_FILES['tf_background_upload']['name']);
				if ( $vc_TargetFile!=NULL ) {
					if ( !in_array($_FILES['tf_background_upload']['type'],$va_Acceptable) ) {
						echo "<strong>S&oacute;lo archivos png son v&aacute;lidos para subir como imagen de fondo</strong>";
						die();
					}
					$vc_NewFileName = 'EtapaActual.png';
					if ( !(move_uploaded_file($_FILES['tf_background_upload']['tmp_name'],$vc_TargetDir.$vc_NewFileName)) ) {
						echo "No se puede subir el archivo seleccionado ".$vc_TargetDir.$vc_TargetFile;
						die();
					}
				}

				// Carga de archivo para dispositivos móviles
				$vc_TargetFile = basename($_FILES['tf_background_mobile_upload']['name']);
				if ( $vc_TargetFile!=NULL ) {
					if ( !in_array($_FILES['tf_background_mobile_upload']['type'],$va_Acceptable) ) {
						echo "<strong>S&oacute;lo archivos png son v&aacute;lidos para subir como imagen de fondo para m&oacute;viles</strong>";
						die();
					}
					$vc_NewFileName = 'EtapaActual50.png';
					if ( !(move_uploaded_file($_FILES['tf_background_mobile_upload']['tmp_name'],$vc_TargetDir.$vc_NewFileName)) ) {
						echo "No se puede subir el archivo seleccionado ".$vc_TargetDir.$vc_TargetFile;
						die();
					}
				}

				// Carga de archivo para logo
				$vc_TargetFile = basename($_FILES['tf_logo_upload']['name']);
				if ( $vc_TargetFile!=NULL ) {
					if ( !in_array($_FILES['tf_logo_upload']['type'],$va_Acceptable) ) {
						echo "<strong>S&oacute;lo archivos png son v&aacute;lidos para subir como imagen de logo</strong>";
						die();
					}
					$vc_NewFileName = 'logo.png';
					if ( !(move_uploaded_file($_FILES['tf_logo_upload']['tmp_name'],$vc_TargetDir.$vc_NewFileName)) ) {
						echo "No se puede subir el archivo seleccionado ".$vc_TargetDir.$vc_TargetFile;
						die();
					}
				}

				$vc_Mensaje = '<div class="mensaje">';
				if ( $vc_TargetFile!=NULL ) $vc_Mensaje.='Im&aacute;genes actualizadas<br>';
				if ( $vn_TotCodigos > 9 ) {
					$vc_Sql="delete from cmt_cod_codigos ";
					$vc_Sql.="where 1 = 1 ";
					$vc_Sql.="and cod_fch_aplicacion is null ";
					$vc_Sql.="and cod_fch_vencimiento < sysdate() ";

					if (mysqli_query($vc_DbConfig, $vc_Sql)) $vc_Mensaje.='C&oacute;digos viejos y no utilizados se han eliminado<br>';
					else {
						$vc_Mensaje = '<font color="yellow">Error: '.mysqli_error($vc_DbConfig).' ejecutando '.$vc_Sql.'<br>Contacta al administrador del sitio<br></font>';
						printf("%s\n",$vc_Mensaje);
						exit(1);
					}					
					$vc_Mensaje.='Hay 10 o más códigos disponibles<br>';
					$vc_Mensaje.='</div>';
					printf("%s\n",$vc_Mensaje);
				}
				else {
					// Generando códigos
					function GenerateRandomString($length = 10){
					$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
					$charactersLength = strlen($characters);
					$randomString = '';
					for ($i = 0; $i < $length; $i++){
						$randomString .= $characters[rand(0, $charactersLength - 1)];
					}
					return $randomString;
					}
					
					for ( $i=0; $i<10; $i++ ) {
						$vc_Cadena = GenerateRandomString();
				//		$vc_Codigo = password_hash($vc_Cadena, PASSWORD_BCRYPT, $va_options);  

						$vc_Sql="insert into cmt_cod_codigos (cod_codigo, cod_fch_vencimiento) ";
						$vc_Sql.="values ('".$vc_Cadena."',date_add(sysdate(), INTERVAL 30 DAY) ); ";

						if (mysqli_query($vc_DbConfig, $vc_Sql)) {
							$vn_LastPk = mysqli_insert_id($vc_DbConfig);
							$vc_Mensaje.='C&oacute;digo '.$vn_LastPk.' insertado.<br>';
						}
						else {
							$vc_Mensaje = '<font color="yellow">Error: '.mysqli_error($vc_DbConfig).' ejecutando '.$vc_Sql.'<br>Contacta al administrador del sitio<br></font>';
							printf("%s\n",$vc_Mensaje);
							exit(1);
						}					
					}
					$vc_Mensaje.='</div>';
					printf("%s\n",$vc_Mensaje);
				}
			}
		
?>
			</form>
		</div>
	</body>
</html>
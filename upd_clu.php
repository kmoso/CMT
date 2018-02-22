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

					if($_SERVER['REQUEST_METHOD']=='GET')
					{
						$vn_Clu_Id = $_GET['clu_id'];
						// Se seleccionó un club existente
						if ( $vn_Clu_Id != -1 ) {
							// Información del club
							$vc_Sql ="select clu_id as id, clu_nombre as nombre, clu_orden as orden, ";
							$vc_Sql.="clu_activo as activo ";
							$vc_Sql.="from cmt_clu_clubes ";
							$vc_Sql.="where 1 = 1 ";
							$vc_Sql.="and clu_id = ".$vn_Clu_Id." ";
	//						echo $vc_Sql;
	//						exit(1);
							$vr_ResultSet=mysqli_query($vc_DbConfig,$vc_Sql);
							if (!$vr_ResultSet) {
								$vc_Mensaje = '<font color="yellow">Error: '.mysqli_error($vc_DbConfig).' ejecutando '.$vc_Sql.'<br>Contacta al administrador del sitio<br></font>';
								printf("%s\n",$vc_Mensaje);
								exit(1);
							}
		//					else echo $vc_Sql;
							$vR_RowClu=mysqli_fetch_array($vr_ResultSet,MYSQLI_ASSOC);
							$vc_Bd_Nombre=$vR_RowClu['nombre'];
							$vc_Bd_Orden=$vR_RowClu['orden'];
							$vc_Bd_Activo=$vR_RowClu['activo'];
						}
						// Se quiere agregar un club
						else {
							$vc_Bd_Nombre="Indica el nombre del club";
							$vc_Bd_Orden=0;
							$vc_Bd_Activo="N";
						}

						$vc_Html = '';
						$vc_Html.='<table class="seleccion">';
						$vc_Html.='<tr class="encabezado"><td colspan="2">Mantenimiento de clubes</td></tr>';
						$vc_Html.='</table>';
						$vc_Html.='<input type="hidden" name="tb_clu_id" id="tb_clu_id" value="'.$vn_Clu_Id.'">';
						$vc_Html.='<table class="edit_grid">';
						$vc_Html.='<tr class="grid0"><td align="right"><b>Club Id:</b></td><td>'.$vn_Clu_Id.'</td></tr>';
						$vc_Html.='<tr class="grid1"><td align="right"><b>Nombre:</b></td><td><input type="text" name="tb_nombre" size="30" value="'.$vc_Bd_Nombre.'"></td></tr>';
						$vc_Html.='<tr class="grid0"><td align="right"><b>Orden:</b></td><td><input type="text" name="tb_orden" size="4" value="'.$vc_Bd_Orden.'"></td></tr>';
						$vc_Html.='<tr class="grid1"><td align="right"><b>Activo?</td><td><input type="checkbox" name="cb_activo" value="'.$vc_Bd_Activo.'"';
						if ( $vc_Bd_Activo == "S" ) {
							$vc_Html.=' checked';
						}
						$vc_Html.='></td></tr>';
						$vc_Html.='<tr><td class="button" colspan="2"><input type="submit" value="Guardar"></td></tr>';
						$vc_Html.='</table>';

						printf("%s\n",$vc_Html);

					}
					
					if($_SERVER['REQUEST_METHOD']=='POST') {
						// Asignando valores
						$vn_Clu_Id=intval($_POST['tb_clu_id']);
						$vc_Nombre=$_POST['tb_nombre'];
						$vn_Orden=$_POST['tb_orden'];
						if ( isset($_POST['cb_activo']) ) {
							$vc_Activo="S";
						}
						else {
							$vc_Activo="N";
						}

						// Se seleccionó un club existente
						if ( $vn_Clu_Id != -1 ) {
							// Actualizando datos del club
							$vc_Sql ="update cmt_clu_clubes ";
							$vc_Sql.="set clu_nombre = '".$vc_Nombre."', ";
							$vc_Sql.="clu_orden = '".$vn_Orden."', ";
							$vc_Sql.="clu_activo = '".$vc_Activo."' ";
							$vc_Sql.="where clu_id = ".$vn_Clu_Id." ";
						}
						// Se insertará un nuevo club
						else {
							$vc_Sql ="insert into cmt_clu_clubes (clu_nombre, clu_orden, clu_activo) ";
							$vc_Sql.="values ('".$vc_Nombre."', ".$vn_Orden.", '".$vc_Activo."') ";
						}

						if (mysqli_query($vc_DbConfig, $vc_Sql)) {
							if ( $vn_Clu_Id == -1 ) {
								$vn_LastPk = mysqli_insert_id($vc_DbConfig);
								$vn_Clu_Id = $vn_LastPk;
								$vc_Mensaje ='Club '.$vn_Clu_Id.' insertado.';
							}
							else $vc_Mensaje ='<div class="mensaje">Datos del club '.$vn_Clu_Id.' actualizados.';
							$vc_Mensaje.='<br><a href="sel_clu.php">Regresar</a></div>';
							printf("%s\n",$vc_Mensaje);
						}
						else {
							$vc_Mensaje = '<font color="yellow">Error: '.mysqli_error($vc_DbConfig).' ejecutando '.$vc_Sql.'<br>Contacta al administrador del sitio<br></font>';
							printf("%s\n",$vc_Mensaje);
							exit(1);
						}
					} // End Post					
?>
			</form>
		</div>
	</body>
</html>
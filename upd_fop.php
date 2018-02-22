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
						$vn_Fop_Id = $_GET['fop_id'];
						// Se seleccionó una categoría existente
						if ( $vn_Fop_Id != -1 ) {
							// Información de la categoría
							$vc_Sql ="select fop_id as id, fop_nombre as nombre, fop_orden as orden, ";
							$vc_Sql.="fop_activo as activo ";
							$vc_Sql.="from cmt_fop_formas_de_pago ";
							$vc_Sql.="where 1 = 1 ";
							$vc_Sql.="and fop_id = ".$vn_Fop_Id." ";
	//						echo $vc_Sql;
	//						exit(1);
							$vr_ResultSet=mysqli_query($vc_DbConfig,$vc_Sql);
							if (!$vr_ResultSet) {
								$vc_Mensaje = '<font color="yellow">Error: '.mysqli_error($vc_DbConfig).' ejecutando '.$vc_Sql.'<br>Contacta al administrador del sitio<br></font>';
								printf("%s\n",$vc_Mensaje);
								exit(1);
							}
		//					else echo $vc_Sql;
							$vR_RowCat=mysqli_fetch_array($vr_ResultSet,MYSQLI_ASSOC);
							$vc_Bd_Nombre=$vR_RowCat['nombre'];
							$vc_Bd_Orden=$vR_RowCat['orden'];
							$vc_Bd_Activo=$vR_RowCat['activo'];
						}
						// Se quiere agregar una categoría
						else {
							$vc_Bd_Nombre="Indica el nombre de la forma de pago";
							$vc_Bd_Orden=0;
							$vc_Bd_Activo="N";
						}

						$vc_Html = '';
						$vc_Html.='<table class="seleccion">';
						$vc_Html.='<tr class="encabezado"><td colspan="2">Mantenimiento de formas de pago</td></tr>';
						$vc_Html.='</table>';
						$vc_Html.='<input type="hidden" name="tb_fop_id" id="tb_fop_id" value="'.$vn_Fop_Id.'">';
						$vc_Html.='<table class="edit_grid">';
						$vc_Html.='<tr class="grid0"><td align="right"><b>Categor&iacute;a Id:</b></td><td>'.$vn_Fop_Id.'</td></tr>';
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
						$vn_Fop_Id=intval($_POST['tb_fop_id']);
						$vc_Nombre=$_POST['tb_nombre'];
						$vn_Orden=$_POST['tb_orden'];
						if ( isset($_POST['cb_activo']) ) {
							$vc_Activo="S";
						}
						else {
							$vc_Activo="N";
						}

						// Se seleccionó una categoría existente
						if ( $vn_Fop_Id != -1 ) {
							// Actualizando datos de la categoría
							$vc_Sql ="update cmt_fop_formas_de_pago ";
							$vc_Sql.="set fop_nombre = '".$vc_Nombre."', ";
							$vc_Sql.="fop_orden = ".$vn_Orden.", ";
							$vc_Sql.="fop_activo = '".$vc_Activo."' ";
							$vc_Sql.="where fop_id = ".$vn_Fop_Id." ";
						}
						// Se insertará una nueva categoría
						else {
							$vc_Sql ="insert into cmt_fop_formas_de_pago (fop_nombre, fop_orden, fop_activo) ";
							$vc_Sql.="values ('".$vc_Nombre."', ".$vn_Orden.", '".$vc_Activo."') ";
						}

						if (mysqli_query($vc_DbConfig, $vc_Sql)) {
							if ( $vn_Fop_Id == -1 ) {
								$vn_LastPk = mysqli_insert_id($vc_DbConfig);
								$vn_Fop_Id = $vn_LastPk;
								$vc_Mensaje ='Forma de pago '.$vn_Fop_Id.' insertada.';
							}
							else $vc_Mensaje ='<div class="mensaje">Datos de la forma de pago '.$vn_Fop_Id.' actualizados.';
							$vc_Mensaje.='<br><a href="sel_fop.php">Regresar</a></div>';
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
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
						$vn_Cat_Id = $_GET['cat_id'];
						// Se seleccionó una categoría existente
						if ( $vn_Cat_Id != -1 ) {
							// Información de la categoría
							$vc_Sql ="select cat_id as id, cat_nombre as nombre, cat_orden as orden, ";
							$vc_Sql.="cat_activo as activo, cat_puntos_iniciales as pts_ini, cat_puntos_max as pts_max ";
							$vc_Sql.="from cmt_cat_categorias ";
							$vc_Sql.="where 1 = 1 ";
							$vc_Sql.="and cat_id = ".$vn_Cat_Id." ";
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
							$vc_Bd_PtsIni=$vR_RowCat['pts_ini'];
							$vc_Bd_PtsMax=$vR_RowCat['pts_max'];
						}
						// Se quiere agregar una categoría
						else {
							$vc_Bd_Nombre="Indica el nombre de la categor&iacute;a";
							$vc_Bd_Orden=0;
							$vc_Bd_Activo="N";
							$vc_Bd_PtsIni=50;
							$vc_Bd_PtsMax=99;
						}

						$vc_Html = '';
						$vc_Html.='<table class="seleccion">';
						$vc_Html.='<tr class="encabezado"><td colspan="2">Mantenimiento de categor&iacute;as</td></tr>';
						$vc_Html.='</table>';
						$vc_Html.='<input type="hidden" name="tb_cat_id" id="tb_cat_id" value="'.$vn_Cat_Id.'">';
						$vc_Html.='<table class="edit_grid">';
						$vc_Html.='<tr class="grid0"><td align="right"><b>Categor&iacute;a Id:</b></td><td>'.$vn_Cat_Id.'</td></tr>';
						$vc_Html.='<tr class="grid1"><td align="right"><b>Nombre:</b></td><td><input type="text" name="tb_nombre" size="30" value="'.$vc_Bd_Nombre.'"></td></tr>';
						$vc_Html.='<tr class="grid0"><td align="right"><b>Orden:</b></td><td><input type="text" name="tb_orden" size="4" value="'.$vc_Bd_Orden.'"></td></tr>';
						$vc_Html.='<tr class="grid1"><td align="right"><b>Activo?</td><td><input type="checkbox" name="cb_activo" value="'.$vc_Bd_Activo.'"';
						if ( $vc_Bd_Activo == "S" ) {
							$vc_Html.=' checked';
						}
						$vc_Html.='></td></tr>';
						$vc_Html.='<tr class="grid0"><td align="right"><b>Puntos Iniciales:</b></td><td><input type="text" name="tb_pts_ini" size="5" value="'.$vc_Bd_PtsIni.'"></td></tr>';
						$vc_Html.='<tr class="grid1"><td align="right"><b>Puntaje M&aacute;ximo:</b></td><td><input type="text" name="tb_pts_max" size="5" value="'.$vc_Bd_PtsMax.'"></td></tr>';
						$vc_Html.='<tr><td class="button" colspan="2"><input type="submit" value="Guardar"></td></tr>';
						$vc_Html.='</table>';

						printf("%s\n",$vc_Html);

					}
					
					if($_SERVER['REQUEST_METHOD']=='POST') {
						// Asignando valores
						$vn_Cat_Id=intval($_POST['tb_cat_id']);
						$vc_Nombre=$_POST['tb_nombre'];
						$vn_Orden=$_POST['tb_orden'];
						$vn_PtsIni=$_POST['tb_pts_ini'];
						$vn_PtsMax=$_POST['tb_pts_max'];
						if ( isset($_POST['cb_activo']) ) {
							$vc_Activo="S";
						}
						else {
							$vc_Activo="N";
						}

						// Se seleccionó una categoría existente
						if ( $vn_Cat_Id != -1 ) {
							// Actualizando datos de la categoría
							$vc_Sql ="update cmt_cat_categorias ";
							$vc_Sql.="set cat_nombre = '".$vc_Nombre."', ";
							$vc_Sql.="cat_orden = ".$vn_Orden.", ";
							$vc_Sql.="cat_activo = '".$vc_Activo."', ";
							$vc_Sql.="cat_puntos_iniciales = ".$vn_PtsIni.", ";
							$vc_Sql.="cat_puntos_max = '".$vn_PtsMax."' ";
							$vc_Sql.="where cat_id = ".$vn_Cat_Id." ";
						}
						// Se insertará una nueva categoría
						else {
							$vc_Sql ="insert into cmt_cat_categorias (cat_nombre, cat_orden, cat_activo, cat_puntos_iniciales, cat_puntos_max) ";
							$vc_Sql.="values ('".$vc_Nombre."', ".$vn_Orden.", '".$vc_Activo."', ".$vn_PtsIni.", ".$vn_PtsMax.") ";
						}

						if (mysqli_query($vc_DbConfig, $vc_Sql)) {
							if ( $vn_Cat_Id == -1 ) {
								$vn_LastPk = mysqli_insert_id($vc_DbConfig);
								$vn_Cat_Id = $vn_LastPk;
								$vc_Mensaje ='Categor&iacute;a '.$vn_Cat_Id.' insertada.';
							}
							else $vc_Mensaje ='<div class="mensaje">Datos de la categor&iacute;a '.$vn_Cat_Id.' actualizados.';
							$vc_Mensaje.='<br><a href="sel_cat.php">Regresar</a></div>';
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
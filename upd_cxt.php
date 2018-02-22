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
						$vn_Cxt_Id = $_GET['cxt_id'];

						// Asignación de parámetros
						// FECHA_MINIMA_TORNEOS
						$vc_Sql ="select par_valor as parametro ";
						$vc_Sql.="from cmt_par_parametros ";
						$vc_Sql.="where 1 = 1 ";
						$vc_Sql.="and par_nombre = 'FECHA_MINIMA_TORNEOS' ";
						$vr_ResultSet=mysqli_query($vc_DbConfig,$vc_Sql);
						if (!$vr_ResultSet) {
							$vc_Mensaje = '<font color="yellow">Error: '.mysqli_error($vc_DbConfig).' ejecutando '.vc_Sql.'<br>Contacta al administrador del sitio<br></font>';
							printf("%s\n",$vc_Mensaje);
							exit(1);
						}
	//					else echo $vc_Sql;
						$vR_Row=mysqli_fetch_array($vr_ResultSet,MYSQLI_ASSOC);
						$cc_FchMin=$vR_Row['parametro'];

						// FECHA_MAXIMA_TORNEOS
						$vc_Sql ="select par_valor as parametro ";
						$vc_Sql.="from cmt_par_parametros ";
						$vc_Sql.="where 1 = 1 ";
						$vc_Sql.="and par_nombre = 'FECHA_MAXIMA_TORNEOS' ";
						$vr_ResultSet=mysqli_query($vc_DbConfig,$vc_Sql);
						if (!$vr_ResultSet) {
							$vc_Mensaje = '<font color="yellow">Error: '.mysqli_error($vc_DbConfig).' ejecutando '.vc_Sql.'<br>Contacta al administrador del sitio<br></font>';
							printf("%s\n",$vc_Mensaje);
							exit(1);
						}
	//					else echo $vc_Sql;
						$vR_Row=mysqli_fetch_array($vr_ResultSet,MYSQLI_ASSOC);
						$cc_FchMax=$vR_Row['parametro'];

						// Se seleccionó una categoría por torneo existente
						if ( $vn_Cxt_Id != -1 ) {
							// Información del torneo
							$vc_Sql ="select cxt_id as id, tor_nombre as torneo, tor_fch_inicio as fch_inicio, ";
							$vc_Sql.="tor_activo as activo, cxt_fch_ini_inscripciones as fch_ini_inscr,";
							$vc_Sql.="cxt_fch_fin_inscripciones as fch_fin_inscr, cat_nombre as categoria, ";
							$vc_Sql.="cxt_tor_id as tor_id, cxt_cat_id as cat_id ";
							$vc_Sql.="from cmt_cxt_categorias_x_torneo, cmt_tor_torneos, cmt_cat_categorias ";
							$vc_Sql.="where 1 = 1 ";
							$vc_Sql.="and cxt_id = ".$vn_Cxt_Id." ";
							$vc_Sql.="and cxt_tor_id = tor_id ";
							$vc_Sql.="and cxt_cat_id = cat_id ";
							$vr_ResultSet=mysqli_query($vc_DbConfig,$vc_Sql);
							if (!$vr_ResultSet) {
								$vc_Mensaje = '<font color="yellow">Error: '.mysqli_error($vc_DbConfig).' ejecutando '.$vc_Sql.'<br>Contacta al administrador del sitio<br></font>';
								printf("%s\n",$vc_Mensaje);
								exit(1);
							}
		//					else echo $vc_Sql;
							$vR_RowCxt=mysqli_fetch_array($vr_ResultSet,MYSQLI_ASSOC);
							$vc_Bd_Fch_Ini_Inscr=$vR_RowCxt['fch_ini_inscr'];
							$vc_Bd_Fch_Fin_Inscr=$vR_RowCxt['fch_fin_inscr'];
							$vn_Cxt_Tor_Id=$vR_RowCxt['tor_id'];
							$vn_Cxt_Cat_Id=$vR_RowCxt['cat_id'];
						}
						// Se quiere agregar una categoría por torneo
						else {
							$vc_Bd_Fch_Ini_Inscr=$cc_FchMin;
							$vc_Bd_Fch_Fin_Inscr=$cc_FchMax;
							$vn_Cxt_Tor_Id=0;
							$vn_Cxt_Cat_Id=0;
						}

						// LOV Torneos
						$vc_Sql = "select tor_id as id, tor_nombre as descr ";
						$vc_Sql.="from cmt_tor_torneos ";
						$vc_Sql.="where 1 = 1 ";
						//$vc_Sql.="and tor_activo = 'S' ";
						$vc_Sql.="order by case ".$vn_Cxt_Tor_Id." when tor_id then 0 else 1 end, tor_fch_inicio, tor_id";
						$vr_ResultSet=mysqli_query($vc_DbConfig,$vc_Sql);
						if (!$vr_ResultSet) {
							$vc_Mensaje = '<font color="yellow">Error: '.mysqli_error($vc_DbConfig).' ejecutando '.$vc_Sql.'<br>Contacta al administrador del sitio<br></font>';
							printf("%s\n",$vc_Mensaje);
							exit(1);
						}
//						else echo $vc_Sql;
						$vR_RowTor=mysqli_fetch_array($vr_ResultSet,MYSQLI_ASSOC);
						$vc_Ddl_Torneos = '<tr class="grid1"><td align="right"><b>Torneo:</b></td><td><select id="ddl_tor_id" name="ddl_tor_id">';
						$vc_Ddl_Torneos.='<option value="'.$vR_RowTor['id'].'">'.$vR_RowTor['descr'].'</option>';
						while($vR_RowTor = mysqli_fetch_assoc($vr_ResultSet))
						{
						  $vc_Ddl_Torneos.='<option value="'.$vR_RowTor['id'].'">'.$vR_RowTor['descr'].'</option>';
						}
						$vc_Ddl_Torneos.='</select>';
						
						// LOV Categorías
						$vc_Sql = "select cat_id as id, cat_nombre as descr ";
						$vc_Sql.="from cmt_cat_categorias ";
						$vc_Sql.="where 1 = 1 ";
						$vc_Sql.="and cat_activo = 'S' ";
						$vc_Sql.="order by case ".$vn_Cxt_Cat_Id." when cat_id then 0 else 1 end, cat_orden, cat_id";
						$vr_ResultSet=mysqli_query($vc_DbConfig,$vc_Sql);
						if (!$vr_ResultSet) {
							$vc_Mensaje = '<font color="yellow">Error: '.mysqli_error($vc_DbConfig).' ejecutando '.$vc_Sql.'<br>Contacta al administrador del sitio<br></font>';
							printf("%s\n",$vc_Mensaje);
							exit(1);
						}
//						else echo $vc_Sql;
						$vR_RowCat=mysqli_fetch_array($vr_ResultSet,MYSQLI_ASSOC);
						$vc_Ddl_Categorias = '<tr class="grid1"><td align="right"><b>Categor&iacute;a:</b></td><td><select id="ddl_cat_id" name="ddl_cat_id">';
						$vc_Ddl_Categorias.='<option value="'.$vR_RowCat['id'].'">'.$vR_RowCat['descr'].'</option>';
						while($vR_RowCat = mysqli_fetch_assoc($vr_ResultSet))
						{
						  $vc_Ddl_Categorias.='<option value="'.$vR_RowCat['id'].'">'.$vR_RowCat['descr'].'</option>';
						}
						$vc_Ddl_Categorias.='</select>';

						$vc_Html = '';
						$vc_Html.='<input type="hidden" name="tb_cxt_id" id="tb_cxt_id" value="'.$vn_Cxt_Id.'">';
						$vc_Html.='<table class="seleccion">';
						$vc_Html.='<tr class="encabezado"><td colspan="2">Mantenimiento de categor&iacute;as por torneo</td></tr>';
						$vc_Html.='</table>';
						$vc_Html.='<table class="edit_grid">';
						$vc_Html.='<tr class="grid0"><td align="right"><b>Id:</b></td><td>'.$vn_Cxt_Id.'</td></tr>';
						$vc_Html.=$vc_Ddl_Torneos;
						$vc_Html.=$vc_Ddl_Categorias;
						$vc_Html.='<tr class="grid1"><td align="right"><b>Inicio de inscripciones:</td><td><input type="date" name="tb_fch_ini_inscr" min="2018-01-01" max="2018-12-31"  value="'.$vc_Bd_Fch_Ini_Inscr.'"></td></tr>';
						$vc_Html.='<tr class="grid1"><td align="right"><b>Cierre de inscripciones:</td><td><input type="date" name="tb_fch_fin_inscr" min="2018-01-01" max="2018-12-31"  value="'.$vc_Bd_Fch_Fin_Inscr.'"></td></tr>';
						$vc_Html.='<tr><td class="button" colspan="2"><input type="submit" value="Guardar"></td></tr>';
						$vc_Html.='</table>';

						printf("%s\n",$vc_Html);

					}
					
					if($_SERVER['REQUEST_METHOD']=='POST') {
						// Asignando valores
						$vn_Cxt_Id=intval($_POST['tb_cxt_id']);
						$vn_Tor_Id=intval($_POST['ddl_tor_id']);
						$vn_Cat_Id=intval($_POST['ddl_cat_id']);
						$vc_FchIniInscr=$_POST['tb_fch_ini_inscr'];
						$vc_FchFinInscr=$_POST['tb_fch_fin_inscr'];

						// Se seleccionó una categoría por torneo existente
						if ( $vn_Cxt_Id != -1 ) {
							// Actualizando datos de la categoría por torneo
							$vc_Sql ="update cmt_cxt_categorias_x_torneo ";
							$vc_Sql.="set cxt_tor_id = '".$vn_Tor_Id."', ";
							$vc_Sql.="cxt_cat_id = '".$vn_Cat_Id."', ";
							$vc_Sql.="cxt_fch_ini_inscripciones = '".$vc_FchIniInscr."', ";
							$vc_Sql.="cxt_fch_fin_inscripciones = '".$vc_FchFinInscr."' ";
							$vc_Sql.="where cxt_id = ".$vn_Cxt_Id." ";
						}
						// Se insertará una nueva categoría por torneo
						else {
							$vc_Sql ="insert into cmt_cxt_categorias_x_torneo ";
							$vc_Sql.="(cxt_tor_id, cxt_cat_id, cxt_fch_ini_inscripciones, cxt_fch_fin_inscripciones) ";
							$vc_Sql.="values (".$vn_Tor_Id.",".$vn_Cat_Id.",'".$vc_FchIniInscr."', '".$vc_FchFinInscr."') ";
						}

						if (mysqli_query($vc_DbConfig, $vc_Sql)) {
							if ( $vn_Cxt_Id == -1 ) {
								$vn_LastPk = mysqli_insert_id($vc_DbConfig);
								$vn_Cxt_Id = $vn_LastPk;
								$vc_Mensaje ='Id '.$vn_Cxt_Id.' insertado.';
							}
							else $vc_Mensaje ='<div class="mensaje">Datos del id '.$vn_Cxt_Id.' actualizados.';
							$vc_Mensaje.='<br><a href="sel_cxt.php">Regresar</a></div>';
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
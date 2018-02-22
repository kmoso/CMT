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

					$vc_Header = '<br>';
					$vc_HeaderMensaje = $vc_Header.'<font color="yellow">';

					if($_SERVER['REQUEST_METHOD']=='GET')
					{
						$vn_Tor_Id = $_GET['tor_id'];
						$cc_FchMin="2018-01-01";
						$cc_FchMax="2018-12-31";
						
						// Se seleccionó un torneo existente
						if ( $vn_Tor_Id != -1 ) {
							// Información del torneo
							$vc_Sql ="select tor_id as id, tor_nombre as torneo, tor_fch_inicio as fch_inicio, ";
							$vc_Sql.="tor_activo as activo, ";
							$vc_Sql.="clu_id, tor_orden as orden ";
							$vc_Sql.="from cmt_tor_torneos, cmt_clu_clubes ";
							$vc_Sql.="where 1 = 1 ";
							$vc_Sql.="and tor_id = ".$vn_Tor_Id." ";
							$vc_Sql.="and tor_clu_id = clu_id ";
	//						echo $vc_Sql;
	//						exit(1);
							$vr_ResultSet=mysqli_query($vc_DbConfig,$vc_Sql);
							if (!$vr_ResultSet) {
								$vc_Mensaje = $vc_HeaderMensaje.'Error: '.mysqli_error($vc_DbConfig).' ejecutando '.$vc_Sql.'<br>Contacta al administrador del sitio<br></font>';
								printf("%s\n",$vc_Mensaje);
								exit(1);
							}
		//					else echo $vc_Sql;
							$vR_RowTor=mysqli_fetch_array($vr_ResultSet,MYSQLI_ASSOC);
							$vn_Bd_Clu_Id=$vR_RowTor['clu_id'];
							$vc_Bd_Torneo=$vR_RowTor['torneo'];
							$vc_Bd_Fch_Inicio=$vR_RowTor['fch_inicio'];
							$vc_Bd_Activo=$vR_RowTor['activo'];
							$vn_Bd_Orden=$vR_RowTor['orden'];
						}
						// Se quiere agregar un torneo
						else {
							$vn_Bd_Clu_Id = -1;
							$vc_Bd_Torneo="Indica el nombre del torneo";
							$vc_Bd_Fch_Inicio=$cc_FchMax;
							$vc_Bd_Activo="N";
							$vn_Bd_Orden=99;
						}
						
						// LOV Clubes
						$vc_Sql = "select clu_id as id, clu_nombre as descr ";
						$vc_Sql.="from cmt_clu_clubes ";
						$vc_Sql.="where 1 = 1 ";
						$vc_Sql.="and clu_activo = 'S' ";
						$vc_Sql.="order by case clu_id when ".$vn_Bd_Clu_Id." then 0 else clu_orden end, clu_id";
//						echo $vc_Sql;
//						exit(1);
						$vr_ResultSet=mysqli_query($vc_DbConfig,$vc_Sql);
						if (!$vr_ResultSet) {
							$vc_Mensaje = $vc_HeaderMensaje.'Error: '.mysqli_error($vc_DbConfig).' ejecutando '.$vc_Sql.'<br>Contacta al administrador del sitio<br></font>';
							printf("%s\n",$vc_Mensaje);
							exit(1);
						}
	//					else echo $vc_Sql;
						$vR_RowClu=mysqli_fetch_array($vr_ResultSet,MYSQLI_ASSOC);
						$vc_Ddl_Clubes = '<tr class="grid1"><td align="right"><b>Club:</b></td><td><select id="ddl_cat_id" name="ddl_clu_id">';
						$vc_Ddl_Clubes.='<option value="'.$vR_RowClu['id'].'">'.$vR_RowClu['descr'].'</option>';
						while($vR_RowClu = mysqli_fetch_assoc($vr_ResultSet))
						{
						  $vc_Ddl_Clubes.='<option value="'.$vR_RowClu['id'].'">'.$vR_RowClu['descr'].'</option>';
						}
						$vc_Ddl_Clubes.='</select>';
						
						$vc_Html = '';
						$vc_Html.='<input type="hidden" name="tb_tor_id" id="tb_tor_id" value="'.$vn_Tor_Id.'">';
						$vc_Html.='<table class="seleccion">';
						$vc_Html.='<tr class="encabezado"><td colspan="2">Mantenimiento de torneos</td></tr>';
						$vc_Html.='</table>';
						$vc_Html.='<table class="edit_grid">';
						$vc_Html.='<tr class="grid0"><td align="right"><b>Torneo Id:</b></td><td>'.$vn_Tor_Id.'</td></tr>';
						$vc_Html.=$vc_Ddl_Clubes.'</td></tr>';
						$vc_Html.='<tr class="grid0"><td align="right"><b>Nombre:</b></td><td><input type="text" name="tb_nombre" size="30" value="'.$vc_Bd_Torneo.'"></td></tr>';
						$vc_Html.='<tr class="grid1"><td align="right"><b>Fecha de inicio:</td><td><input type="date" name="tb_fch_inicio" min="'.$cc_FchMin.'" max="'.$cc_FchMax.'" value="'.$vc_Bd_Fch_Inicio.'"></td></tr>';
						$vc_Html.='<tr class="grid1"><td align="right"><b>Activo?</td><td><input type="checkbox" name="cb_activo" value="'.$vc_Bd_Activo.'"';
						if ( $vc_Bd_Activo == "S" ) {
							$vc_Html.=' checked';
						}
						$vc_Html.='></td></tr><tr class="grid0"><td align="right"><b>Orden:</b></td><td><input type="text" name="tb_orden" size="2" value="'.$vn_Bd_Orden.'"></td></tr>';
						$vc_Html.='<tr><td class="button" colspan="2"><input type="submit" value="Guardar"></td></tr>';
						$vc_Html.='</table>';

						printf("%s\n",$vc_Html);

					}
					
					if($_SERVER['REQUEST_METHOD']=='POST') {
						// Asignando valores
						$vn_Tor_Id=intval($_POST['tb_tor_id']);
						$vn_Clu_Id=intval($_POST['ddl_clu_id']);
						$vn_Orden=intval($_POST['tb_orden']);
						$vc_Nombre=$_POST['tb_nombre'];
						$vc_FchInicio=$_POST['tb_fch_inicio'];
						if ( isset($_POST['cb_activo']) ) {
							$vc_Activo="S";
						}
						else {
							$vc_Activo="N";
						}

						// Se seleccionó un torneo existente
						if ( $vn_Tor_Id != -1 ) {
							// Actualizando datos del torneo
							$vc_Sql ="update cmt_tor_torneos ";
							$vc_Sql.="set tor_clu_id = '".$vn_Clu_Id."', ";
							$vc_Sql.="tor_nombre = '".$vc_Nombre."', ";
							$vc_Sql.="tor_fch_inicio = '".$vc_FchInicio."', ";
							$vc_Sql.="tor_activo = '".$vc_Activo."', ";
							$vc_Sql.="tor_orden = ".$vn_Orden." ";
							$vc_Sql.="where tor_id = ".$vn_Tor_Id." ";
						}
						// Se insertará un nuevo torneo
						else {
							$vc_Sql ="insert into cmt_tor_torneos (tor_clu_id, tor_nombre, tor_fch_inicio, tor_activo, tor_orden) ";
							$vc_Sql.="values (".$vn_Clu_Id.", '".$vc_Nombre."', '".$vc_FchInicio."', '".$vc_Activo."', ".$vn_Orden.") ";
						}
						
						if (mysqli_query($vc_DbConfig, $vc_Sql)) {
							if ( $vn_Tor_Id == -1 ) {
								$vn_LastPk = mysqli_insert_id($vc_DbConfig);
								$vn_Tor_Id = $vn_LastPk;
								$vc_Mensaje ='Torneo '.$vn_Tor_Id.' insertado.';
							}
							else $vc_Mensaje ='<div class="mensaje">Datos del torneo '.$vn_Tor_Id.' actualizados.';
							$vc_Mensaje.='<br><a href="sel_tor.php">Regresar</a></div>';
							printf("%s\n",$vc_Mensaje);
						}
						else {
							$vc_Mensaje = $vc_HeaderMensaje.'Error: '.mysqli_error($vc_DbConfig).' ejecutando '.$vc_Sql.'<br>Contacta al administrador del sitio<br></font>';
							printf("%s\n",$vc_Mensaje);
							exit(1);
						}
					} // End Post					
?>
			</form>
		</div>
	</body>
</html>
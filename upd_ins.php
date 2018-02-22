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
						$vn_Ins_Id = $_GET['ins_id'];
						// Información de la inscripción solicitada
						$vc_Sql ="select ins_id as id, jug_nombre as jugador, tor_nombre as torneo, tor_id as tor_id, ";
						$vc_Sql.="cat_nombre as categoria, ins_fch_inscripcion as fch_inscr, ins_pagado as pagado, ";
						$vc_Sql.="ins_costo as costo, ins_inscrito as inscrito, ins_comprobante as comprobante, ";
						$vc_Sql.="ins_comentarios as comentarios, cat_id, jug_en_lista_negra as blacklist ";
						$vc_Sql.="from cmt_ins_inscripciones, cmt_jug_jugadores, cmt_tor_torneos, cmt_cat_categorias ";
						$vc_Sql.="where 1 = 1 ";
						$vc_Sql.="and ins_id = ".$vn_Ins_Id." ";
						$vc_Sql.="and ins_jug_id = jug_id ";
						$vc_Sql.="and ins_tor_id = tor_id ";
						$vc_Sql.="and ins_cat_id = cat_id ";
						$vr_ResultSet=mysqli_query($vc_DbConfig,$vc_Sql);
						$vR_RowIns=mysqli_fetch_array($vr_ResultSet,MYSQLI_ASSOC);
						
						//echo $vc_Sql;

						// LOV Torneos
						$vc_Sql = "select tor_id as id, tor_nombre as descr ";
						$vc_Sql.="from cmt_tor_torneos ";
						$vc_Sql.="where 1 = 1 ";
						$vc_Sql.="and tor_activo = 'S' ";
						$vc_Sql.="order by case tor_id when ".$vR_RowIns['tor_id']." then 0 else tor_id end ";
						$vr_ResultSet=mysqli_query($vc_DbConfig,$vc_Sql);
						$vR_RowTor=mysqli_fetch_array($vr_ResultSet,MYSQLI_ASSOC);
						$vc_Ddl_Torneos = '<tr class="grid1"><td align="right"><b>Torneo:</b></td><td><select id="ddl_tor_id" name="ddl_tor_id">';
						$vc_Ddl_Torneos.='<option value="'.$vR_RowTor['id'].'">'.$vR_RowTor['descr'].'</option>';
						while($vR_RowTor = mysqli_fetch_assoc($vr_ResultSet))
						{
						  $vc_Ddl_Torneos.='<option value="'.$vR_RowTor['id'].'">'.$vR_RowTor['descr'].'</option>';
						}
						$vc_Ddl_Torneos.='</select>';

						//echo $vc_Sql;

						// LOV Categorías
						$vc_Sql = "select cat_id as id, cat_nombre as descr ";
						$vc_Sql.="from cmt_cat_categorias ";
						$vc_Sql.="where 1 = 1 ";
						$vc_Sql.="order by case cat_id when ".$vR_RowIns['cat_id']." then 0 else cat_orden end, cat_id";
						$vr_ResultSet=mysqli_query($vc_DbConfig,$vc_Sql);
						$vR_RowCat=mysqli_fetch_array($vr_ResultSet,MYSQLI_ASSOC);
						$vc_Ddl_Categorias = '<tr class="grid0"><td align="right"><b>Categor&iacute;a:</b></td><td><select id="ddl_cat_id" name="ddl_cat_id">';
						$vc_Ddl_Categorias.='<option value="'.$vR_RowCat['id'].'">'.$vR_RowCat['descr'].'</option>';
						while($vR_RowCat = mysqli_fetch_assoc($vr_ResultSet))
						{
						  $vc_Ddl_Categorias.='<option value="'.$vR_RowCat['id'].'">'.$vR_RowCat['descr'].'</option>';
						}
						$vc_Ddl_Categorias.='</select>';

						//echo $vc_Sql;
						
						$vc_Html = '';
						$vc_Html.='<input type="hidden" name="tb_ins_id" id="tb_ins_id" value="'.$vn_Ins_Id.'">';
						$vc_Html.='<table class="seleccion">';
						$vc_Html.='<tr class="encabezado"><td colspan="2">Mantenimiento de inscripciones</td></tr>';
						$vc_Html.='</table>';
						$vc_Html.='<table class="edit_grid">';
						$vc_Html.='<tr class="grid0"><td align="right"><b>Inscripci&oacute;n:</b></td><td>'.$vn_Ins_Id.'</td></tr>';
//						$vc_Html.='<tr class="grid1"><td align="right"><b>Torneo:</td><td>'.$vR_RowIns['torneo'].'</td></tr>';
						$vc_Html.=$vc_Ddl_Torneos.'</td></tr>';
						$vc_Html.=$vc_Ddl_Categorias.'</td></tr>';
						$vc_Html.='<tr class="grid1"><td align="right"><b>Jugador:</td><td>'.$vR_RowIns['jugador'].'</td></tr>';
						$vc_Html.='<tr class="grid0"><td align="right"><b>Fch Inscripci&oacuten:</td><td>'.$vR_RowIns['fch_inscr'].'</td></tr>';
						$vc_Html.='<tr class="grid1"><td align="right"><b>Pagado?</td><td><input type="checkbox" name="cb_pagado" value="'.$vR_RowIns['pagado'].'"';
						if ( $vR_RowIns['pagado'] == "S" ) {
							$vc_Html.=' checked';
						}
						$vc_Html.='></td></tr>';
						$vc_Html.='<tr class="grid0"><td align="right"><b>Black List?</td><td>'.$vR_RowIns['blacklist'].'</td></tr>';
						$vc_Html.='<tr class="grid1"><td align="right"><b>Costo:</td><td><input type="text" name="tb_costo" size="8" value="'.$vR_RowIns['costo'].'"></td></tr>';
						$vc_Html.='<tr class="grid0"><td align="right"><b>Inscrito?</td><td><input type="checkbox" name="cb_inscrito" value="'.$vR_RowIns['inscrito'].'"';
						if ( $vR_RowIns['inscrito'] == "S" ) {
							$vc_Html.=' checked';
						}
						$vc_Html.='></td></tr>';
						$vc_Html.='<tr class="grid1"><td align="right"><b>Comprobante:</td><td>';
						if ( $vR_RowIns['comprobante'] != NULL ) {
							$vc_Html.='<img src="uploads/'.$vR_RowIns['comprobante'].'" heigth="400" width="400"/>';
						}						
						$vc_Html.='</td></tr>';
						$vc_Html.='<tr class="grid0"><td align="right"><b>Nuevo comprobante:</b></td><td>';
						$vc_Html.='<input type="file" name="comprobante_upload" accept="image/*"></td></tr>';
						$vc_Html.='<tr class="grid1"><td align="right"><b>Comentarios:</b></td><td>';
						$vc_Html.='<textarea rows="12" cols="80" name="ta_comentarios">'.$vR_RowIns['comentarios'].'</textarea></td></tr>';
						$vc_Html.='<tr><td class="button" colspan="2"><input type="submit" value="Guardar"></td></tr>';
						$vc_Html.='</table>';

						printf("%s\n",$vc_Html);

					}
					
					if($_SERVER['REQUEST_METHOD']=='POST') {
						// Asignando valores
						$vn_Ins_Id=intval($_POST['tb_ins_id']);
						$vn_Tor_Id=intval($_POST['ddl_tor_id']);
						$vn_Cat_Id=intval($_POST['ddl_cat_id']);
						if ( isset($_POST['cb_pagado']) ) {
							$vc_Pagado="S";
						}
						else {
							$vc_Pagado="N";
						}
						$vn_Costo=$_POST['tb_costo'];
						if ( isset($_POST['cb_inscrito']) ) {
							$vc_Inscrito="S";
						}
						else {
							$vc_Inscrito="N";
						}
						$vc_Comentarios=$_POST['ta_comentarios'];

						// Subiendo comprobante
						$vc_TargetDir = "uploads/";
						$va_Acceptable = array(
						'image/jpeg',
						'image/jpg',
						'image/gif',
						'image/png'
						);
						$vc_TargetFile = basename($_FILES['comprobante_upload']['name']);
						if ( $vc_TargetFile!=NULL ) {
							$vc_vi_ComprobanteFileType = pathinfo($vc_TargetDir.$vc_TargetFile,PATHINFO_EXTENSION);
							if ( !in_array($_FILES['comprobante_upload']['type'],$va_Acceptable) ) {
								echo "<strong>S&oacute;lo archivos jpeg, jpg, gif o png son v&aacute;lidos para subir como comprobante</strong>";
								die();
							}
							$vc_NewFileName = round(microtime(true)).'.'.$vc_vi_ComprobanteFileType;
							if ( !(move_uploaded_file($_FILES['comprobante_upload']['tmp_name'],$vc_TargetDir.$vc_NewFileName)) ) {
								echo "<strong>No se puede subir el archivo seleccionado ".$vc_TargetDir.$vc_TargetFile."</strong>";
								die();
							}
							$vc_Comprobante=$vc_NewFileName;
						}
						else {
							$vc_Comprobante='';
						}						
						// Actualizando inscripción
						$vc_Sql ="update cmt_ins_inscripciones ";
						$vc_Sql.="set ins_cat_id = ".$vn_Cat_Id.", ";
						$vc_Sql.="ins_tor_id = ".$vn_Tor_Id.", ";
						$vc_Sql.="ins_pagado = '".$vc_Pagado."', ";
						$vc_Sql.="ins_costo = ".$vn_Costo.", ";
						$vc_Sql.="ins_inscrito ='".$vc_Inscrito."', ";
						$vc_Sql.="ins_comentarios ='".$vc_Comentarios."' ";
						if ( $vc_Comprobante != '' ) $vc_Sql.=", ins_comprobante ='".$vc_Comprobante."' ";
						$vc_Sql.="where ins_id = ".$vn_Ins_Id." ";
						//echo $vc_Sql;
						if ( !(mysqli_query($vc_DbConfig, $vc_Sql)) )
						{
							echo "Error: ".$vc_Sql.", contacta al administrador del sitio<br>".mysqli_error($vc_DbConfig);
						}
						else {
							$vc_Mensaje ='<div class="mensaje">Inscripci&oacuten '.$vn_Ins_Id.' actualizada.<br><a href="sel_ins.php">Regresar</a></div>';
							printf("%s\n",$vc_Mensaje);
						}
					} // End Post					
?>
			</form>
		</div>
	</body>
</html>
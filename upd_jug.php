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
						$vn_Jug_Id = $_GET['jug_id'];
						$cc_FchMin="1917-01-01";
						$cc_FchMax="2012-01-01";

						// Se seleccionó un jugador existente
						if ( $vn_Jug_Id != -1 ) {
							// Información del jugador solicitado
							$vc_Sql ="select jug_id as id, jug_nombre as jugador, clu_nombre as club, ";
							$vc_Sql.="cat_nombre as categoria, jug_email as email, jug_telefono as telefono, ";
							$vc_Sql.="ifnull(jug_fch_nacimiento,'2012-01-02') as fch_nacimiento, jug_rama as rama, ";
							$vc_Sql.="jug_activo as activo, jug_en_lista_negra as blacklist, jug_puntos as puntos, ";
							$vc_Sql.="jug_comentarios as comentarios, cat_id, clu_id ";
							$vc_Sql.="from cmt_jug_jugadores, cmt_clu_clubes, cmt_cat_categorias ";
							$vc_Sql.="where 1 = 1 ";
							$vc_Sql.="and jug_id = ".$vn_Jug_Id." ";
							$vc_Sql.="and jug_clu_id = clu_id ";
							$vc_Sql.="and jug_cat_id = cat_id ";
//							echo $vc_Sql;
//							exit(1);
							$vr_ResultSet=mysqli_query($vc_DbConfig,$vc_Sql);
							if (!$vr_ResultSet) {
								$vc_Mensaje = '<font color="yellow">Error: '.mysqli_error($vc_DbConfig).' ejecutando '.$vc_Sql.'<br>Contacta al administrador del sitio<br></font>';
								printf("%s\n",$vc_Mensaje);
								exit(1);
							}
		//					else echo $vc_Sql;
							$vR_RowJug=mysqli_fetch_array($vr_ResultSet,MYSQLI_ASSOC);
							$vc_Bd_Nombre=$vR_RowJug['jugador'];
							$vn_Bd_Clu_Id=$vR_RowJug['clu_id'];
							$vn_Bd_Cat_Id=$vR_RowJug['cat_id'];
							$vc_Bd_Email=$vR_RowJug['email'];
							$vc_Bd_Telefono=$vR_RowJug['telefono'];
							$vc_Bd_Fch_Nacimiento=$vR_RowJug['fch_nacimiento'];
							$vc_Bd_Rama=$vR_RowJug['rama'];
							$vc_Bd_Activo=$vR_RowJug['activo'];
							$vn_Bd_Puntos=$vR_RowJug['puntos'];
							$vc_Bd_Comentarios=$vR_RowJug['comentarios'];
							$vc_Bd_Blacklist=$vR_RowJug['blacklist'];
						}
						// Se quiere agregar un torneo
						else {
							$vc_Bd_Nombre="Indica el nombre del jugador";
							$vn_Bd_Clu_Id = -1;
							$vn_Bd_Cat_Id = -1;
							$vc_Bd_Email="Jugador@cmt.com.mx";
							$vc_Bd_Telefono="Indica el tel&eacute;fono";
							$vc_Bd_Fch_Nacimiento=$cc_FchMax;
							$vc_Bd_Rama="Varonil";
							$vc_Bd_Activo="N";
							$vc_Bd_Comentarios="";
							$vn_Bd_Puntos= 0;
							$vc_Bd_Blacklist="N";
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
						$vR_RowClu=mysqli_fetch_array($vr_ResultSet,MYSQLI_ASSOC);
						$vc_Ddl_Clubes = '<tr class="grid1"><td align="right"><b>Club:</b></td><td><select id="ddl_clu_id" name="ddl_clu_id">';
						$vc_Ddl_Clubes.='<option value="'.$vR_RowClu['id'].'">'.$vR_RowClu['descr'].'</option>';
						while($vR_RowClu = mysqli_fetch_assoc($vr_ResultSet))
						{
						  $vc_Ddl_Clubes.='<option value="'.$vR_RowClu['id'].'">'.$vR_RowClu['descr'].'</option>';
						}
						$vc_Ddl_Clubes.='</select>';

						// LOV Categorías
						$vc_Sql = "select cat_id as id, cat_nombre as descr ";
						$vc_Sql.="from cmt_cat_categorias ";
						$vc_Sql.="where 1 = 1 ";
						$vc_Sql.="order by case cat_id when ".$vn_Bd_Cat_Id." then 0 else cat_orden end, cat_id";
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
						$vc_Html.='<input type="hidden" name="tb_jug_id" id="tb_jug_id" value="'.$vn_Jug_Id.'">';
						$vc_Html.='<table class="seleccion">';
						$vc_Html.='<tr class="encabezado"><td colspan="2">Mantenimiento de jugadores</td></tr>';
						$vc_Html.='</table>';
						$vc_Html.='<table class="edit_grid">';
						$vc_Html.='<tr class="grid0"><td align="right"><b>Jugador Id:</b></td><td>'.$vn_Jug_Id.'</td></tr>';
						$vc_Html.='<tr class="grid1"><td align="right"><b>Nombre:</b></td><td><input type="text" name="tb_nombre" size="30" value="'.$vc_Bd_Nombre.'"></td></tr>';
						$vc_Html.='<tr class="grid0"><td align="right"><b>Email:</td><td><input type="text" name="tb_email" size="45" value="'.$vc_Bd_Email.'"></td></tr>';
						$vc_Html.=$vc_Ddl_Clubes.'</td></tr>';
						$vc_Html.=$vc_Ddl_Categorias.'</td></tr>';
						$vc_Html.='<tr class="grid0"><td align="right"><b>Tel&eacute;fono:</td><td><input type="text" name="tb_telefono" size="20" value="'.$vc_Bd_Telefono.'"></td></tr>';
						$vc_Html.='<tr class="grid1"><td align="right"><b>Fecha de nacimiento:</td><td><input type="date" name="tb_fch_nacimiento" min="1917-01-01" max="2012-01-01"  value="'.$vc_Bd_Fch_Nacimiento.'"></td></tr>';
						$vc_Html.='<tr class="grid0"><td align="right"><b>Rama:</td><td><select name="tb_rama">';
						if ($vc_Bd_Rama == 'Varonil') {
							$vc_Html.='		<option value="Varonil" selected>Varonil</option>';
							$vc_Html.='		<option value="Femenil">Femenil</option>';
						}
						else {
							$vc_Html.='		<option value="Varonil">Varonil</option>';
							$vc_Html.='		<option value="Femenil" selected>Femenil</option>';
						}
						$vc_Html.='</select></td></tr>';								
						$vc_Html.='<tr class="grid1"><td align="right"><b>Activo?</td><td><input type="checkbox" name="cb_activo" value="'.$vc_Bd_Activo.'"';
						if ( $vc_Bd_Activo == "S" ) {
							$vc_Html.=' checked';
						}
						$vc_Html.='></td></tr>';
						$vc_Html.='<tr class="grid0"><td align="right"><b>En Black List?</td><td><input type="checkbox" name="cb_blacklist" value="'.$vc_Bd_Blacklist.'"';
						if ( $vc_Bd_Blacklist == "S" ) {
							$vc_Html.=' checked';
						}
						$vc_Html.='></td></tr>';
						$vc_Html.='<tr class="grid1"><td align="right"><b>Puntos:</td><td><input type="text" name="tb_puntos" size="5" value="'.$vn_Bd_Puntos.'"></td></tr>';
						$vc_Html.='<tr class="grid0"><td align="right"><b>Comentarios:</td><td><textarea name="ta_comentarios" rows="10" cols="50">'.$vc_Bd_Comentarios.'</textarea></td></tr>';
						$vc_Html.='<tr><td class="button" colspan="2"><input type="submit" value="Guardar"></td></tr>';
						$vc_Html.='</table>';

						printf("%s\n",$vc_Html);

					}
					
					if($_SERVER['REQUEST_METHOD']=='POST') {
						// Asignando valores
						$vn_Jug_Id=intval($_POST['tb_jug_id']);
						$vn_Clu_Id=intval($_POST['ddl_clu_id']);
						$vn_Cat_Id=intval($_POST['ddl_cat_id']);
						$vc_Nombre=$_POST['tb_nombre'];
						$vc_Email=$_POST['tb_email'];
						$vc_Telefono=$_POST['tb_telefono'];
						$vc_FchNacimiento=$_POST['tb_fch_nacimiento'];
						$vc_Rama=$_POST['tb_rama'];
						$vc_Comentarios=$_POST['ta_comentarios'];
						$vn_Puntos=$_POST['tb_puntos'];
						if ( isset($_POST['cb_activo']) ) {
							$vc_Activo="S";
						}
						else {
							$vc_Activo="N";
						}
						if ( isset($_POST['cb_blacklist']) ) {
							$vc_Blacklist="S";
						}
						else {
							$vc_Blacklist="N";
						}

						// Se seleccionó un jugador existente
						if ( $vn_Jug_Id != -1 ) {
							// Verificando si se ajustaron los puntos para agregar un comentario automático
							$vc_Sql = "select coalesce(jug_puntos,0) as puntos ";
							$vc_Sql.="from cmt_jug_jugadores ";
							$vc_Sql.="where 1 = 1 ";
							$vc_Sql.="and jug_id = ".$vn_Jug_Id." ";
							$vr_ResultSet=mysqli_query($vc_DbConfig,$vc_Sql);
							if (!$vr_ResultSet) {
								$vc_Mensaje = '<font color="yellow">Error: '.mysqli_error($vc_DbConfig).' ejecutando '.$vc_Sql.'<br>Contacta al administrador del sitio<br></font>';
								printf("%s\n",$vc_Mensaje);
//								exit(1);
							}
		//					else echo $vc_Sql;
							$vR_RowPts=mysqli_fetch_array($vr_ResultSet,MYSQLI_ASSOC);
							$vn_Bd_Puntos=$vR_RowPts['puntos'];
							if ( $vn_Bd_Puntos != $vn_Puntos ) {
								$vc_Comentarios.=' Puntos anteriores: '.$vn_Bd_Puntos;
							}
							// Actualizando datos del jugador
							$vc_Sql ="update cmt_jug_jugadores ";
							$vc_Sql.="set jug_cat_id = ".$vn_Cat_Id.", ";
							$vc_Sql.="jug_clu_id = '".$vn_Clu_Id."', ";
							$vc_Sql.="jug_nombre = '".$vc_Nombre."', ";
							$vc_Sql.="jug_email = '".$vc_Email."', ";
							$vc_Sql.="jug_telefono = '".$vc_Telefono."', ";
							$vc_Sql.="jug_fch_nacimiento = '".$vc_FchNacimiento."', ";
							$vc_Sql.="jug_rama = '".$vc_Rama."', ";
							$vc_Sql.="jug_activo = '".$vc_Activo."', ";
							$vc_Sql.="jug_comentarios = '".$vc_Comentarios."', ";
							$vc_Sql.="jug_puntos = ".$vn_Puntos.", ";
							$vc_Sql.="jug_en_lista_negra = '".$vc_Blacklist."' ";
							$vc_Sql.="where jug_id = ".$vn_Jug_Id." ";
						}
						// Se insertará un nuevo jugador
						else {
							$vc_Sql ="insert into cmt_jug_jugadores (jug_cat_id, jug_clu_id, jug_nombre, ";
							$vc_Sql.="jug_email, jug_telefono, jug_fch_nacimiento, jug_rama, jug_activo, ";
							$vc_Sql.="jug_en_lista_negra, jug_puntos, jug_comentarios) ";
							$vc_Sql.="values (".$vn_Cat_Id.", ".$vn_Clu_Id.", '".$vc_Nombre."', '".$vc_Email;
							$vc_Sql.="', '".$vc_Telefono."', '".$vc_FchNacimiento."', '".$vc_Rama;
							$vc_Sql.="', '".$vc_Activo."', '".$vc_Blacklist."', ".$vn_Puntos.", '".$vc_Comentarios."') ";
						}

						if (mysqli_query($vc_DbConfig, $vc_Sql)) {
							if ( $vn_Jug_Id == -1 ) {
								$vn_LastPk = mysqli_insert_id($vc_DbConfig);
								$vn_Jug_Id = $vn_LastPk;
								$vc_Mensaje ='Jugador '.$vn_Jug_Id.' insertado.';
							}
							else $vc_Mensaje ='<div class="mensaje">Datos del jugador '.$vn_Jug_Id.' actualizados.';
							$vc_Mensaje.='<br><a href="sel_jug.php">Regresar</a></div>';
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
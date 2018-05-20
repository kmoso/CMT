<?php
	include("dbconfig.php");
	session_start();
	if (!isset($_SESSION['gn_Jug_Id'])) {
		header("Location: val_email.php");
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Circuito Mexicano de Tenistas</title>
		<link rel="stylesheet" href="screenCMT.css">
		<script type="text/javascript">
			function validaForma() {
			  var vc_Mensaje = "";

			  if  ( InsForm.tb_nombre.value == "" )
				  vc_Mensaje = "Indique su nombre\n";
			  
			  if  ( InsForm.tb_telefono.value == "" )
				  vc_Mensaje += "Indique su número de teléfono\n";

			  if  ( InsForm.ddl_fop_id.value != "1" )
				if  ( InsForm.tf_comprobante_upload.value == "" )
					vc_Mensaje += "Suba su comprobante de pago\n";

			  if ( !(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(InsForm.tb_email.value)) )
				  vc_Mensaje += "Ingrese un email válido para verificar\n";

			  if ( vc_Mensaje == "" ) {
				  vc_Mensaje = "Registro en proceso, da click para continuar";
				  document.getElementById("tb_datos_completos").value = "S";
			  }
			  else {
				  document.getElementById("tb_datos_completos").value = "N";
			  }

			  alert(vc_Mensaje);
		  }
		</script>
	</head>
	<header>
		<a class="exit" href="logout.php"></a><br>
	</header>
	<body>
		<div id="page">
			<form name="InsForm" onsubmit="validaForma()" method="POST" accept-charset="utf-8" enctype="multipart/form-data">
<?php

				// 20170516 Por alguna razón esta parte falla en ocasiones
				if (!isset($_SESSION['gc_DatosCompletos'])) {
					header("Location: val_email.php");
				}

				// Asignando valores de la página de validación de correo
				if (isset($_SESSION['gc_DatosCompletos'])) $vc_DatosCompletos=$_SESSION['gc_DatosCompletos'];
				else die();
				$vn_Jug_Id=$_SESSION['gn_Jug_Id'];
				$vn_Clu_Id=$_SESSION['gn_Clu_Id'];
				$vn_Cat_Id=$_SESSION['gn_Cat_Id'];
				$vn_Fop_Id=2;
				$vc_Nombre=$_SESSION['gc_Nombre'];
				$vc_Email=$_SESSION['gc_Email'];
				$vn_Puntos=$_SESSION['gc_Puntos'];
				$vc_Telefono=$_SESSION['gc_Telefono'];
				$vc_Fch_Nacimiento=$_SESSION['gc_Fch_Nacimiento'];
				$vc_Rama=$_SESSION['gc_Rama'];


				$vc_Html = '';
				$vc_Html.='<br><br><br><br><input type="hidden" name="tb_datos_completos" id="tb_datos_completos" value="'.$vc_DatosCompletos.'">';
				$vc_Html.='<input type="hidden" name="tb_puntos" id="tb_puntos" value="'.$vn_Puntos.'">';
				$vc_Html.='<input type="hidden" name="tb_jug_id" id="tb_jug_id" value="'.$vn_Jug_Id.'">';
				$vc_Html.='<table class="inscripcion">';
				$vc_Html.='<tr><td align="center">Formato para inscripci&oacute;n</td></tr>';
				$vc_Html.='</table>';
				$vc_Html.='<table class="edit_grid">';
				$vc_Html.='<tr class="grid0"><td align="right"><b>Nombre:</b></td><td><input type="text" name="tb_nombre" size="30" value="'.$vc_Nombre.'"></td>';
				$vc_Html.='<td align="right"><b>Email:</td><td><input type="text" name="tb_email" size="30" value="'.$vc_Email.'"></td></tr>';
				$vc_Html.='<tr class="grid1"><td align="right"><b>Tel&eacute;fono:</td><td><input type="text" name="tb_telefono" size="20" value="'.$vc_Telefono.'"></td>';
				$vc_Html.='<td align="right"><b>Fecha de nacimiento:</td><td><input type="date" name="tb_fch_nacimiento" min="1917-01-01" max="2012-01-01"  value="'.$vc_Fch_Nacimiento.'"></td></tr>';
				$vc_Html.='<tr class="grid0"><td align="right"><b>Rama:</td><td><select name="tb_rama">';
				if ($vc_Rama == 'Varonil') {
					$vc_Html.='		<option value="Varonil" selected>Varonil</option>';
					$vc_Html.='		<option value="Femenil">Femenil</option>';
				}
				else {
					$vc_Html.='		<option value="Varonil">Varonil</option>';
					$vc_Html.='		<option value="Femenil" selected>Femenil</option>';
				}
				$vc_Html.='</select></td>';	
						
				// LOV Clubes
				$vc_Sql = "select clu_id as id, clu_nombre as descr ";
				$vc_Sql.="from cmt_clu_clubes ";
				$vc_Sql.="where 1 = 1 ";
				$vc_Sql.="order by case clu_id when ".$vn_Clu_Id." then 0 else clu_orden end, clu_id";
				$vr_ResultSet=mysqli_query($vc_DbConfig,$vc_Sql);
				if (!$vr_ResultSet) {
					$vc_Mensaje = '<font color="yellow">Error: '.mysqli_error($vc_DbConfig).' ejecutando '.$vc_Sql.' Contacta al administrador del sitio<br></font>';
					printf("%s\n",$vc_Mensaje);
					exit(1);
				}
//				else echo $vc_Sql;
				$vR_Row=mysqli_fetch_array($vr_ResultSet,MYSQLI_ASSOC);
				$vc_Ddl_Clubes = '<td align="right"><b>Club:</b></td><td><select id="ddl_clu_id" name="ddl_clu_id">';
				$vc_Ddl_Clubes.='<option value="'.$vR_Row['id'].'">'.$vR_Row['descr'].'</option>';
				while($vR_Row = mysqli_fetch_assoc($vr_ResultSet))
				{
				  $vc_Ddl_Clubes.='<option value="'.$vR_Row['id'].'">'.$vR_Row['descr'].'</option>';
				}
				$vc_Ddl_Clubes.='</select>';
				$vc_Html.=$vc_Ddl_Clubes.'</td></tr>';

				// LOV Formas de pago
				$vc_Sql = "select fop_id as id, fop_nombre as descr ";
				$vc_Sql.="from cmt_fop_formas_de_pago ";
				$vc_Sql.="where 1 = 1 ";
				$vc_Sql.="and fop_activo = 'S' ";
				$vc_Sql.="order by case fop_id when ".$vn_Fop_Id." then 0 else fop_orden end, fop_id";
				$vr_ResultSet=mysqli_query($vc_DbConfig,$vc_Sql);
				if (!$vr_ResultSet) {
					$vc_Mensaje = '<font color="yellow">Error: '.mysqli_error($vc_DbConfig).' ejecutando '.$vc_Sql.' Contacta al administrador del sitio<br></font>';
					printf("%s\n",$vc_Mensaje);
					exit(1);
				}
//				else echo $vc_Sql;
				$vR_Row=mysqli_fetch_array($vr_ResultSet,MYSQLI_ASSOC);
				$vc_Ddl_Formas = '<tr class="grid1"><td align="right"><b>Forma de pago:</b></td><td><select id="ddl_fop_id" name="ddl_fop_id">';
				$vc_Ddl_Formas.='<option value="'.$vR_Row['id'].'">'.$vR_Row['descr'].'</option>';
				while($vR_Row = mysqli_fetch_assoc($vr_ResultSet))
				{
				  $vc_Ddl_Formas.='<option value="'.$vR_Row['id'].'">'.$vR_Row['descr'].'</option>';
				}
				$vc_Ddl_Formas.='</select>';
				$vc_Html.=$vc_Ddl_Formas.'</td>';

				$vc_Html.='<td align="right"><b>C&oacute;digo de Promoci&oacute;n:</b></td><td><input type="text" name="tb_codigo" size="10" value="Ninguna"></td></tr>';

				// LOV Torneos y Categorías
				$vc_Sql = "select concat(tor_id,'.',cat_id) as id, ";
				$vc_Sql.="concat(tor_nombre,' en ',clu_nombre,' categoría ',cat_nombre) as descr, cat_puntos_max ";
				$vc_Sql.="from cmt_tor_torneos, cmt_clu_clubes, cmt_cat_categorias, cmt_cxt_categorias_x_torneo ";
				$vc_Sql.="where 1 = 1 ";
				$vc_Sql.="and tor_activo = 'S' ";
				$vc_Sql.="and tor_clu_id = clu_id ";
				$vc_Sql.="and cxt_tor_id = tor_id ";
				$vc_Sql.="and cxt_cat_id = cat_id ";
				$vc_Sql.="and cat_puntos_max >= ".$vn_Puntos." ";
				if ( $vn_Jug_Id > 0 ) {
					$vc_Sql.="and not exists (";
					$vc_Sql.="                select 1 ";
					$vc_Sql.="                from   cmt_ins_inscripciones ";
					$vc_Sql.="                where  1 = 1 ";
					$vc_Sql.="                and    ins_jug_id = ".$vn_Jug_Id." ";
					$vc_Sql.="                and    ins_tor_id = cxt_tor_id ";
					$vc_Sql.="                and    ins_cat_id = cxt_cat_id ";
					$vc_Sql.="               ) ";
				}
				$vc_Sql.="and sysdate() between cxt_fch_ini_inscripciones and date_add(cxt_fch_fin_inscripciones, interval 1 day) ";
				$vc_Sql.="order by tor_orden, cxt_fch_ini_inscripciones, tor_fch_inicio, cat_puntos_max, case cat_id when ".$vn_Cat_Id." then 0 else cat_orden end, cat_id, tor_id";
				$vr_ResultSet=mysqli_query($vc_DbConfig,$vc_Sql);
				if (!$vr_ResultSet) {
					$vc_Mensaje = '<font color="yellow">Error: '.mysqli_error($vc_DbConfig).' ejecutando '.$vc_Sql.' Contacta al administrador del sitio<br></font>';
					printf("%s\n",$vc_Mensaje);
					exit(1);
				}
//				else echo $vc_Sql;
				$vR_Row=mysqli_fetch_array($vr_ResultSet,MYSQLI_ASSOC);
				$vc_Ddl_Categorias = '<tr class="grid0"><td align="right"><b>Torneo y categor&iacute;a:</b></td><td><select id="ddl_tyc_id" name="ddl_tyc_id">';
				$vc_Ddl_Categorias.='<option value="'.$vR_Row['id'].'">'.$vR_Row['descr'].'</option>';
				while($vR_Row = mysqli_fetch_assoc($vr_ResultSet))
				{
				  $vc_Ddl_Categorias.='<option value="'.$vR_Row['id'].'">'.$vR_Row['descr'].'</option>';
				}
				$vc_Ddl_Categorias.='</select>';
				$vc_Html.=$vc_Ddl_Categorias.'</td></tr>';
					
				$vc_Html.='<tr class="grid1"><td align="right"><b>Comprobante:</b></td><td>';
				$vc_Html.='<input type="file" name="tf_comprobante_upload" accept="image/*"></td></tr>';
				$vc_Html.='<tr class="grid0"><td align="right"><b>Comentarios / Sugerencias / Peticiones</b></td><td>';
				$vc_Html.='<textarea rows="10" cols="60" name="ta_comentarios"></textarea></td></tr>';
				$vc_Html.='<tr><td class="button" colspan="2"><input type="submit" value="Inscribirse"></td></tr>';
				$vc_Html.='</table>';

				$vc_Html.='<table class="avisos">';
				$vc_Html.='<tr>';
				$vc_Html.='<td width="33%"><a href="/docs/ReglamentoCircuitoMexicanoDeTenis.pdf" download>Lee nuestro reglamento</a></td>';
				$vc_Html.='<td width="33%"><a href="/docs/PeticionesDeHorario.pdf" download>Peticiones de horario</a></td>';
				$vc_Html.='<td width="33%"><b>*Todos los datos son obligatorios</b></td></tr>';
				$vc_Html.='</table>';

				printf("%s\n",$vc_Html);

				if($_SERVER['REQUEST_METHOD']=='POST') {
					$vc_Header = '<br>';
					$vc_HeaderMensaje = $vc_Header.'<font color="yellow">';
					// Asignando valores
					$vc_DatosCompletos=$_POST['tb_datos_completos'];
					$vn_Jug_Id=intval($_POST['tb_jug_id']);
					$vn_Clu_Id=intval($_POST['ddl_clu_id']);
					$vc_Tyc_Id=$_POST['ddl_tyc_id'];
					$vn_Fop_Id=intval($_POST['ddl_fop_id']);
					$vc_Nombre=$_POST['tb_nombre'];
					$vc_Email=$_POST['tb_email'];
					$vn_Puntos=$_POST['tb_puntos'];
					$vc_Telefono=$_POST['tb_telefono'];
					$vc_Fch_Nacimiento=$_POST['tb_fch_nacimiento'];
					$vc_Rama=$_POST['tb_rama'];
					$vc_Comentarios=$_POST['ta_comentarios'];
					$vc_Codigo=$_POST['tb_codigo'];
					$vn_Tor_Id=substr($vc_Tyc_Id,0,strpos($vc_Tyc_Id,'.'));
					$vn_Cat_Id=substr($vc_Tyc_Id,strpos($vc_Tyc_Id,'.')+1,strlen($vc_Tyc_Id)-strpos($vc_Tyc_Id,'.'));
					//printf("Tyc %s-Tor %s-Cat %s.\n",$vc_Tyc_Id,$vn_Tor_Id,$vn_Cat_Id);
					//printf("Inserta? %s\n",$vc_DatosCompletos);
					if ( $vc_DatosCompletos=="S" ) {
						$vn_Cod_Id=0;
						// Inicia 20170903 Por petición de Michel ya no se solicitará código si se trata de pagos en efectivo
/* 						if ( $vn_Fop_Id == 1 ) {
							// Verificando que el código sea válido
							$vc_Sql="select coalesce(max(cod_id),0) as id ";
							$vc_Sql.="from cmt_cod_codigos ";
							$vc_Sql.="where cod_codigo = '".$vc_Codigo."' ";
							$vc_Sql.="and cod_ins_id is null ";
							$vc_Sql.="and cod_fch_aplicacion is null ";
							$vc_Sql.="and cod_fch_vencimiento >= sysdate() ";
							$vr_ResultSetCodigo=mysqli_query($vc_DbConfig,$vc_Sql);
							if (!$vr_ResultSetCodigo) {
								$vc_Mensaje = '<font color="yellow">Error: '.mysqli_error($vc_DbConfig).' ejecutando '.$vc_Sql.' Contacta al administrador del sitio<br></font>';
								printf("%s\n",$vc_Mensaje);
								exit(1);
							}
 */		
		//					else echo $vc_Sql;
/* 							$vR_RowCodigo=mysqli_fetch_array($vr_ResultSetCodigo,MYSQLI_BOTH);
							$vn_Cod_Id=$vR_RowCodigo['id'];
							if ( $vn_Cod_Id == 0 ) {
								$vc_Mensaje='<div class="mensaje">El c&oacute;digo de promoci&oacute;n ingresado no es v&aacute;lido</div>';
								printf("%s",$vc_Mensaje);
								die();
							}
						}
 */						// Termina 20170903 
						if ( $vn_Jug_Id == 0 ) {

							// Validando que el correo electrónico no exista en la base de datos
							$vc_Sql="select coalesce(max(jug_id),0) as id, max(jug_nombre) as jugador ";
							$vc_Sql.="from cmt_jug_jugadores ";
							$vc_Sql.="where jug_email = '".$vc_Email."' ";
							$vr_ResultSetEmail=mysqli_query($vc_DbConfig,$vc_Sql);
							if (!$vr_ResultSetEmail) {
								$vc_Mensaje = $vc_HeaderMensaje.'Error: '.mysqli_error($vc_DbConfig).' ejecutando '.$vc_Sql.' Contacta al administrador del sitio<br></font>';
								printf("%s\n",$vc_Mensaje);
								exit(1);
							}
							else {
								$vR_RowEmail=mysqli_fetch_array($vr_ResultSetEmail,MYSQLI_BOTH);
								$vn_Jug_Id=$vR_RowEmail['id'];
								$vc_Jugador=$vR_RowEmail['jugador'];
								if ( $vn_Jug_Id > 0 ) {
									$vc_Mensaje = $vc_HeaderMensaje.'Error: El email ingresado corresponde al jugador ';
									printf("%s %d - %s. Por favor c&aacute;mbielo</font>\n",$vc_Mensaje,$vn_Jug_Id,$vc_Jugador);
									die();
								}
							}

							// Insertando Jugador
							$vc_Sql="insert into cmt_jug_jugadores (jug_id, ";
							$vc_Sql.="jug_clu_id, jug_cat_id, jug_nombre,";
							$vc_Sql.="jug_email, jug_telefono, jug_fch_nacimiento, ";
							$vc_Sql.="jug_rama) ";
							$vc_Sql.="values ($vn_Jug_Id, $vn_Clu_Id, $vn_Cat_Id, '$vc_Nombre',";
							$vc_Sql.="'$vc_Email', '$vc_Telefono', str_to_date('$vc_Fch_Nacimiento','%Y-%m-%d'), ";
							$vc_Sql.="'$vc_Rama') ";

							if (mysqli_query($vc_DbConfig, $vc_Sql))
							{
								$vn_LastPk = mysqli_insert_id($vc_DbConfig);
								$vn_Jug_Id = $vn_LastPk;
							}
							else
							{
								$vn_Jug_Id = 0;
								$vc_Mensaje = $vc_HeaderMensaje.'Error: '.mysqli_error($vc_DbConfig).' ejecutando '.$vc_Sql.' Contacta al administrador del sitio<br></font>';
								printf("%s\n",$vc_Mensaje);
								exit(1);
							}
						}
						else {
							// Actualizando datos del Jugador
							$vc_Sql="update cmt_jug_jugadores ";
							$vc_Sql.="set jug_clu_id = ".$vn_Clu_Id.", ";
//							$vc_Sql.="jug_cat_id = ".$vn_Cat_Id.", "; 
							$vc_Sql.="jug_email = '".$vc_Email."', ";
							$vc_Sql.="jug_nombre = '".$vc_Nombre."', ";
							$vc_Sql.="jug_telefono = '".$vc_Telefono."', ";
							$vc_Sql.="jug_fch_nacimiento = str_to_date('".$vc_Fch_Nacimiento."','%Y-%m-%d'), ";
							$vc_Sql.="jug_rama = '".$vc_Rama."' ";
							$vc_Sql.="where jug_id = ".$vn_Jug_Id." ";

							if ( !(mysqli_query($vc_DbConfig, $vc_Sql)) )
							{
								$vn_Jug_Id = 0;
								$vc_Mensaje = $vc_HeaderMensaje.'Error: '.mysqli_error($vc_DbConfig).' ejecutando '.$vc_Sql.' Contacta al administrador del sitio<br></font>';
								printf("%s\n",$vc_Mensaje);
								exit(1);
							}
						}
						if ( 0 < $vn_Jug_Id ) {
							// Obteniendo nombres de torneo y categoría para incluirlos en correo de confirmación
							$vc_Sql="select tor_nombre, cat_nombre ";
							$vc_Sql.="from cmt_cxt_categorias_x_torneo, cmt_tor_torneos, cmt_cat_categorias ";
							$vc_Sql.="where tor_id = ".$vn_Tor_Id." ";
							$vc_Sql.="and cat_id = ".$vn_Cat_Id." ";
							$vc_Sql.="and cxt_tor_id = tor_id ";
							$vc_Sql.="and cxt_cat_id = cat_id ";
							//printf("%s\n",$vc_Sql);
									
							$vr_ResultSetDatosEmail=mysqli_query($vc_DbConfig,$vc_Sql);
							if (!$vr_ResultSetDatosEmail) {
								$vc_Mensaje = $vc_HeaderMensaje.'Error: '.mysqli_error($vc_DbConfig).' ejecutando '.$vc_Sql.' Contacta al administrador del sitio<br></font>';
								printf("%s\n",$vc_Mensaje);
								exit(1);
							}
		//					else echo $vc_Sql;
							$vR_RowDatosEmail=mysqli_fetch_array($vr_ResultSetDatosEmail,MYSQLI_BOTH);
							$vc_NombreTorneo=$vR_RowDatosEmail['tor_nombre'];
							$vc_NombreCategoria=$vR_RowDatosEmail['cat_nombre'];

							// Verificando que el jugador no esté ya inscrito en el mismo torneo y categorías
							$vc_Sql="select ins_id ";
							$vc_Sql.="from cmt_ins_inscripciones ";
							$vc_Sql.="where ins_jug_id = ".$vn_Jug_Id." ";
							$vc_Sql.="and ins_tor_id = ".$vn_Tor_Id." ";
							$vc_Sql.="and ins_cat_id = ".$vn_Cat_Id." ";
							//printf("%s\n",$vc_Sql);
									
							$vr_ResultSetInscr=mysqli_query($vc_DbConfig,$vc_Sql);
							if (!$vr_ResultSetInscr) {
								$vc_Mensaje = $vc_HeaderMensaje.'Error: '.mysqli_error($vc_DbConfig).' ejecutando '.$vc_Sql.' Contacta al administrador del sitio<br></font>';
								printf("%s\n",$vc_Mensaje);
								exit(1);
							}
		//					else echo $vc_Sql;
							$vR_RowInscr=mysqli_fetch_array($vr_ResultSetInscr,MYSQLI_BOTH);
							$vn_Ins_Id=$vR_RowInscr['ins_id'];
							if (0 < $vn_Ins_Id) {
								$vc_Mensaje = $vc_Header.'<div class="mensaje">'.$vc_Nombre.', ya estabas inscrit@ en el torneo '.$vc_NombreTorneo.' categor&iacute;a '.$vc_NombreCategoria;
								$vc_Mensaje.='<br>Tu n&uacute;mero de inscripci&oacute;n es el '.$vn_Ins_Id.'.</div>';
								printf("%s",$vc_Mensaje);
							}
							else {
								// Subiendo comprobante
								$vc_TargetDir = "uploads/";
								$va_Acceptable = array(
								'application/pdf',
								'image/jpeg',
								'image/jpg',
								'image/gif',
								'image/png'
								);
								$vc_TargetFile = basename($_FILES['tf_comprobante_upload']['name']);
								if ( $vc_TargetFile!=NULL ) {
									$vc_vi_ComprobanteFileType = pathinfo($vc_TargetDir.$vc_TargetFile,PATHINFO_EXTENSION);
									if ( !in_array($_FILES['tf_comprobante_upload']['type'],$va_Acceptable) ) {
										$vc_Mensaje=$vc_Header.'<div class="mensaje">S&oacute;lo archivos jpeg, jpg, gif o png son v&aacute;lidos para subir como comprobante (< 2MB)</div>';
										printf("%s",$vc_Mensaje);
										die();
									}
									$vc_NewFileName = round(microtime(true)).'.'.$vc_vi_ComprobanteFileType;
									if ( !(move_uploaded_file($_FILES['tf_comprobante_upload']['tmp_name'],$vc_TargetDir.$vc_NewFileName)) ) {
										$vc_Mensaje=$vc_Header.'<div class="mensaje">No se puede subir el archivo seleccionado ".$vc_TargetDir.$vc_TargetFile</div>';
										printf("%s",$vc_Mensaje);
										die();
									}
									$vc_Comprobante=$vc_NewFileName;
								}
								else {
									$vc_Comprobante='';
									if ( $vn_Fop_Id != 1 ) {
										$vc_Mensaje=$vc_Header.'<div class="mensaje">Es requerido un comprobante para poder completar la inscripci&oacute;n</div>';
										printf("%s",$vc_Mensaje);
										die();
									}
								}						
								// Insertando Inscripción
								$vc_Sql="insert into cmt_ins_inscripciones ( ";
								$vc_Sql.="ins_id, ins_jug_id, ins_tor_id, ";
								$vc_Sql.="ins_cat_id, ins_comprobante, ins_comentarios) ";
								$vc_Sql.="values (0,".$vn_Jug_Id.", ".$vn_Tor_Id.", ";
								$vc_Sql.=$vn_Cat_Id.", '".$vc_Comprobante."', '".$vc_Comentarios."') ";

								if (mysqli_query($vc_DbConfig, $vc_Sql)) {
									$vn_LastPk = mysqli_insert_id($vc_DbConfig);
									$vn_Ins_Id = $vn_LastPk;

									$vc_Sql="update cmt_cod_codigos ";
									$vc_Sql.="set cod_ins_id = ".$vn_Ins_Id.", ";
									$vc_Sql.="cod_fch_aplicacion = sysdate() ";
									$vc_Sql.="where cod_id = ".$vn_Cod_Id." ";

									if (mysqli_query($vc_DbConfig, $vc_Sql)) {
										$vc_Mensaje = $vc_Header.'<div class="mensaje">'.$vc_Nombre.', tu n&uacute;mero de inscripci&oacute;n es '.$vn_Ins_Id.'.<br>';
										$vc_Mensaje.='Cons&eacute;rvala para identificar tu pago.<br>';
										$vc_Alert = $vc_Nombre.', tu número de inscripción es '.$vn_Ins_Id.', consérvala para identificar tu pago. Da click para continuar.';
//										printf("%s",$vc_Mensaje);
										$vc_To = $vc_Email;
										$vc_EmailSubject = "Inscripcion CMT al torneo ".$vc_NombreTorneo.", categoria ".$vc_NombreCategoria;
										$vc_EmailBody = '<html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head><body><div><p>';
										$vc_EmailBody.= "Hola ".$vc_Nombre."<br>Tu inscripción ".$vn_Ins_Id." fue confirmada<br>";
										$vc_EmailBody.="<br><b>Suerte en el torneo!</b><br><br>";
										$vc_EmailBody.='Consulta nuestro <a href="http://www.cmt.com.mx/docs/ReglamentoCircuitoMexicanoDeTenis.pdf">reglamento vigente</a><br>';
										$vc_EmailBody.= 'y las <a href="http://www.cmt.com.mx/docs/PeticionesDeHorario.pdf">condiciones generales para peticiones de horario.</a><br></p>';
										$vc_EmailBody.="--<br>";
										$vc_EmailBody.="Organización,<br>";
										$vc_EmailBody.="Circuito Mexicano de Tenis<br>";
										$vc_EmailBody.="Tel. 55 2909 5388";
										$vc_EmailBody.= '</div></body></html>';
										$vc_EmailHeaders = "From: atencion.jugadores@cmt.com.mx\r\n";
										$vc_EmailHeaders.= "Bcc: atencion.jugadores@cmt.com.mx\r\n";
										$vc_EmailHeaders.= "X-Mailer: php\r\n";
										$vc_EmailHeaders.= "Content-Type: text/html; charset=UTF-8\r\n";
										if (mail($vc_To, $vc_EmailSubject, $vc_EmailBody, $vc_EmailHeaders))
											$vc_Mensaje.= 'Correo de confirmaci&oacute;n enviado.';
										else
											$vc_Mensaje.= 'No se pudo mandar confirmaci&oacute;n a tu correo pero has quedado inscrito.';
										$vc_Mensaje.="</div>";
										printf("%s",$vc_Mensaje);
										echo "<script type='text/javascript'>alert('".$vc_Alert."');</script>";
									}
									else
									{
										$vc_Mensaje = $vc_HeaderMensaje.'Error: '.mysqli_error($vc_DbConfig).' ejecutando '.$vc_Sql.' Contacta al administrador del sitio<br></font>';
										printf("%s\n",$vc_Mensaje);
										exit(1);
									}
								}
								else
								{
									$vc_Mensaje = $vc_HeaderMensaje.'Error: '.mysqli_error($vc_DbConfig).' ejecutando '.$vc_Sql.' Contacta al administrador del sitio<br></font>';
									printf("%s\n",$vc_Mensaje);
									exit(1);
								}
							}
						}
					} // Inserta por que los valores ingresados son correctos
					else {
						$vc_Mensaje=$vc_Header.'<div class="mensaje">Complete la informaci&oacute;n antes de oprimir el bot&oacute;n Inscribirse</div>';
						printf("%s",$vc_Mensaje);
					}
				} // End Post
?>
			</form>
		</div>
	</body>
</html>
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
						$vn_Doc_Id = $_GET['doc_id'];
						// Se seleccionó un documento existente
						if ( $vn_Doc_Id != -1 ) {
							// Información del club
							$vc_Sql ="select doc_id as id, doc_nombre_archivo as nombre, doc_nombre_display as display, doc_orden as orden, ";
							$vc_Sql.="doc_activo as activo ";
							$vc_Sql.="from cmt_doc_documentos ";
							$vc_Sql.="where 1 = 1 ";
							$vc_Sql.="and doc_id = ".$vn_Doc_Id." ";
							$vr_ResultSet=mysqli_query($vc_DbConfig,$vc_Sql);
							if (!$vr_ResultSet) {
								$vc_Mensaje = '<font color="yellow">Error: '.mysqli_error($vc_DbConfig).' ejecutando '.$vc_Sql.'<br>Contacta al administrador del sitio<br></font>';
								printf("%s\n",$vc_Mensaje);
								exit(1);
							}
		//					else echo $vc_Sql;
							$vR_RowDoc=mysqli_fetch_array($vr_ResultSet,MYSQLI_ASSOC);
							$vc_Bd_Nombre=$vR_RowDoc['nombre'];
							$vc_Bd_Display=$vR_RowDoc['display'];
							$vc_Bd_Orden=$vR_RowDoc['orden'];
							$vc_Bd_Activo=$vR_RowDoc['activo'];
						}
						// Se quiere agregar un documento
						else {
							$vc_Bd_Nombre="Indica el nombre del archivo";
							$vc_Bd_Display="Indica el nombre del documento";
							$vc_Bd_Orden=0;
							$vc_Bd_Activo="N";
						}

						$vc_Html = '';
						$vc_Html.='<input type="hidden" name="tb_doc_id" id="tb_doc_id" value="'.$vn_Doc_Id.'">';
						$vc_Html.='<table class="seleccion">';
						$vc_Html.='<tr class="encabezado"><td colspan="2">Mantenimiento de categor&iacute;as por torneo</td></tr>';
						$vc_Html.='</table>';
						$vc_Html.='<table class="edit_grid">';
						$vc_Html.='<tr class="grid0"><td align="right"><b>Documento Id:</b></td><td>'.$vn_Doc_Id.'</td></tr>';
						$vc_Html.='<tr class="grid1"><td align="right"><b>Archivo actual:</b></td><td>'.$vc_Bd_Nombre.'</td></tr>';
						$vc_Html.='<tr class="grid0"><td align="right"><b>Seleccione un archivo:</b></td><td>';
						$vc_Html.='<input type="file" name="tf_documento_upload" accept=".pdf"></td></tr>';
						$vc_Html.='<tr class="grid1"><td align="right"><b>Nombre:</b></td><td><input type="text" name="tb_display" size="30" value="'.$vc_Bd_Display.'"></td></tr>';
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
						$vn_Doc_Id=intval($_POST['tb_doc_id']);
						$vc_Nombre="NoDefinido.txt";
						$vc_Display=$_POST['tb_display'];
						$vn_Orden=$_POST['tb_orden'];
						if ( isset($_POST['cb_activo']) ) {
							$vc_Activo="S";
						}
						else {
							$vc_Activo="N";
						}

						// Subiendo nuevo documento
						$vc_TargetDir = "docs/";
						$vc_TargetFile = basename($_FILES['tf_documento_upload']['name']);
						$vc_Nombre = $vc_TargetFile;
						if ( $vc_TargetFile!=NULL ) {
							if ( !(move_uploaded_file($_FILES['tf_documento_upload']['tmp_name'],$vc_TargetDir.$vc_Nombre)) ) {
								echo '<font color="yellow"><b>No se puede subir el archivo seleccionado '.$vc_TargetDir.$vc_TargetFile.', tal vez es muy grande (>2MB)</b></font>';
								die();
							}
						}
						
						// Se seleccionó un documento existente
						if ( $vn_Doc_Id != -1 ) {
							// Actualizando datos del club
							$vc_Sql ="update cmt_doc_documentos ";
							$vc_Sql.="set doc_nombre_archivo = '".$vc_Nombre."', ";
							$vc_Sql.="doc_nombre_display = '".$vc_Display."', ";
							$vc_Sql.="doc_orden = '".$vn_Orden."', ";
							$vc_Sql.="doc_activo = '".$vc_Activo."' ";
							$vc_Sql.="where doc_id = ".$vn_Doc_Id." ";
						}
						// Se insertará un nuevo documento
						else {
							$vc_Sql ="insert into cmt_doc_documentos (doc_nombre_archivo, doc_nombre_display, doc_orden, doc_activo) ";
							$vc_Sql.="values ('".$vc_Nombre."', '".$vc_Display."', ".$vn_Orden.", '".$vc_Activo."') ";
						}

						if (mysqli_query($vc_DbConfig, $vc_Sql)) {
							if ( $vn_Doc_Id == -1 ) {
								$vn_LastPk = mysqli_insert_id($vc_DbConfig);
								$vn_Doc_Id = $vn_LastPk;
								$vc_Mensaje ='Documento '.$vn_Doc_Id.' insertado.';
							}
							else $vc_Mensaje ='<div class="mensaje">Datos del documento '.$vn_Doc_Id.' actualizados.';
							$vc_Mensaje.='<br><a href="sel_doc.php">Regresar</a></div>';
							printf("%s\n",$vc_Mensaje);
						}
						else {
							$vc_Mensaje = '<font color="yellow"><b>Error: '.mysqli_error($vc_DbConfig).' ejecutando '.$vc_Sql.'<br>Contacta al administrador del sitio<br></font></b>';
							printf("%s\n",$vc_Mensaje);
							exit(1);
						}
					} // End Post					
?>
			</form>
		</div>
	</body>
</html>
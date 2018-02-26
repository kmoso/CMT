<?php	session_start();?><!DOCTYPE html><html>	<head>		<meta charset="UTF-8">		<title>Sistema CMT</title>		<link rel="stylesheet" href="screenCMT.css">	</head>	<body>		<div id="page">			<form name="InsForm" onsubmit="validaForma()" method="POST" accept-charset="utf-8" enctype="multipart/form-data" ><?php
					if($_SERVER['REQUEST_METHOD']=='GET')
					{						$vc_Html = '';						$vc_Html.='<table class="seleccion">';						$vc_Html.='<tr class="encabezado"><td colspan="2">Mantenimiento de inscripciones</td></tr>';						$vc_Html.='</table>';						$vc_Html.='<table class="edit_grid">';						$vc_Html.='<tr class="grid1"><td align="right"><b>Comprobante:</td><td>';						$vc_Html.='</td></tr>';						$vc_Html.='<tr class="grid0"><td align="right"><b>Nuevo comprobante:</b></td><td>';						$vc_Html.='<input type="file" name="comprobante_upload" accept="image/*"></td></tr>';						$vc_Html.='<tr><td class="button" colspan="2"><input type="submit" value="Guardar"></td></tr>';						$vc_Html.='</table>';						printf("%s\n",$vc_Html);					}
					if($_SERVER['REQUEST_METHOD']=='POST') {
						// Subiendo comprobante
						$vc_TargetDir = "uploads/";
						$va_Acceptable = array(						'image/jpeg',						'image/jpg',						'image/gif',						'image/png'						);
						$vc_TargetFile = basename($_FILES['comprobante_upload']['name']);
						if ( $vc_TargetFile!=NULL ) {							$vc_vi_ComprobanteFileType = pathinfo($vc_TargetDir.$vc_TargetFile,PATHINFO_EXTENSION);
							if ( !in_array($_FILES['comprobante_upload']['type'],$va_Acceptable) ) {								echo "<strong>S&oacute;lo archivos jpeg, jpg, gif o png son v&aacute;lidos para subir como comprobante</strong>";								die();							}							$vc_NewFileName = round(microtime(true)).'.'.$vc_vi_ComprobanteFileType;							if ( !(move_uploaded_file($_FILES['comprobante_upload']['tmp_name'],$vc_TargetDir.$vc_NewFileName)) ) {								echo "<strong>No se puede subir el archivo seleccionado ".$vc_TargetDir.$vc_TargetFile."</strong>";								die();							}
							$vc_Comprobante=$vc_NewFileName;
						}						else {							$vc_Comprobante='';						}											} // End Post					?>			</form>		</div>	</body></html>
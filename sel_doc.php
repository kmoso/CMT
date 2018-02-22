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
		<script type="text/javascript">
		  myId = function (element) {
			  document.getElementById("th_clicked_button").value = element.id;
			  return true;
		  }
		</script>
		<div id="page">
<?php
	include("menu.php");
?>
			<form name="InsForm" onsubmit="validaForma()" method="POST" accept-charset="utf-8" enctype="multipart/form-data" >
<?php
					if (!isset($_POST['th_clicked_button'])) {
						$vc_Clicked="Undefined";
					}
					else {
						$vc_Clicked=$_POST['th_clicked_button'];
					}
					if (!isset($_POST['ddl_doc_id'])) {
						$vn_Doc_Id=0;
					}
					else {
						$vn_Doc_Id=intval($_POST['ddl_doc_id']);
					}

					$vc_Html = '';
					$vc_Html.='<input id="th_clicked_button" type="hidden" name="th_clicked_button"/>';
					$vc_Html.='<table class="seleccion">';
					$vc_Html.='<tr class="encabezado"><td colspan="2">Mantenimiento de documentos</td></tr>';

					// LOV Documentos
					$vc_Sql = "select doc_id as id, doc_nombre_display as descr, doc_orden ";
					$vc_Sql.="from cmt_doc_documentos ";
					$vc_Sql.="where 1 = 1 ";
					$vc_Sql.="union ";
					$vc_Sql.="select 0, 'Todos', 99 ";
					$vc_Sql.="order by case id when ".$vn_Doc_Id." then 0 else 1 end, doc_orden";
					$vr_ResultSet=mysqli_query($vc_DbConfig,$vc_Sql);
					if (!$vr_ResultSet) {
						$vc_Mensaje = '<font color="yellow">Error: '.mysqli_error($vc_DbConfig).' ejecutando '.$vc_Sql.'<br>Contacta al administrador del sitio<br></font>';
						printf("%s\n",$vc_Mensaje);
						exit(1);
					}
//					else echo $vc_Sql;
					$vR_Row=mysqli_fetch_array($vr_ResultSet,MYSQLI_ASSOC);
					$vc_Ddl_Documentos = '<tr><td align="right"><b>Documento:</b></td><td><select id="ddl_doc_id" name="ddl_doc_id">';
					$vc_Ddl_Documentos.='<option value="'.$vR_Row['id'].'">'.$vR_Row['descr'].'</option>';
					while($vR_Row = mysqli_fetch_assoc($vr_ResultSet))
					{
					  $vc_Ddl_Documentos.='<option value="'.$vR_Row['id'].'">'.$vR_Row['descr'].'</option>';
					}
					$vc_Ddl_Documentos.='</select>';
					$vc_Html.=$vc_Ddl_Documentos.'</td></tr>';
					$vc_Html.='<tr><td></td><td><input id="Consultar" type="submit" value="Consultar" onclick="return myId(this);"><br><br></td></tr>';
					$vc_Html.='</table>';

					printf("%s\n",$vc_Html);

					if($_SERVER['REQUEST_METHOD']=='POST') {
						// Asignando valores
						$vn_Doc_Id=intval($_POST['ddl_doc_id']);
						$vc_Clicked=$_POST['th_clicked_button'];
						
						if ( $vc_Clicked == "Consultar" ) {
							$vc_Html = '';
							$vc_Html.='<div class="main_grid">';
							$vc_Html.='<table class="grid">';
							$vc_Html.='<tr class="grid0">';
							$vc_Html.='<td><b>#</b></td>';
							$vc_Html.='<td><b>Archivo</b></td>';
							$vc_Html.='<td><b>Nombre</b></td>';
							$vc_Html.='<td><b>Orden</b></td>';
							$vc_Html.='<td><b>Activo</b></td>';
							$vc_Html.='<td><b>Fch Ins</b></td>';
							$vc_Html.='<td><b>Fch Upd</b></td></tr>';

							// Información de Clubes
							$vc_SqlTmp="select min(doc_id) as min_doc_id, max(doc_id) as max_doc_id ";
							$vc_Sql ="select doc_id as id, doc_nombre_archivo as nombre, doc_nombre_display as display, doc_orden as orden, ";
							$vc_Sql.="doc_activo as activo, doc_fch_ins as fch_ins, doc_fch_upd as fch_upd ";
							$vc_FromWhere="from cmt_doc_documentos ";
							$vc_FromWhere.="where 1 = 1 ";
							if ( 0 < $vn_Doc_Id ) {
								$vc_FromWhere.="and doc_id = ".$vn_Doc_Id." ";
							}
							$vc_Sql.=$vc_FromWhere;
							$vc_Sql.="order by doc_orden, doc_nombre_display ";
							$vc_SqlTmp.=$vc_FromWhere;
//							echo $vc_SqlTmp;
							
							// Obtiene documentos min/max para actualización masiva
							$vr_ResultSetTmp=mysqli_query($vc_DbConfig,$vc_SqlTmp);
							if (!$vr_ResultSetTmp) {
								$vc_Mensaje = '<font color="yellow">Error: '.mysqli_error($vc_DbConfig).' ejecutando '.$vc_SqlTmp.'<br>Contacta al administrador del sitio<br></font>';
								printf("%s\n",$vc_Mensaje);
								exit(1);
							}
							$vR_MaxMinDocIds=mysqli_fetch_array($vr_ResultSetTmp,MYSQLI_ASSOC);
							$_SESSION['gn_Min_Doc_Id']=$vR_MaxMinDocIds['min_doc_id'];
							$_SESSION['gn_Max_Doc_Id']=$vR_MaxMinDocIds['max_doc_id'];

							// Obtiene documentos seleccionados
							$vn_i=1;
							$vr_ResultSet=mysqli_query($vc_DbConfig,$vc_Sql);
							if (!$vr_ResultSet) {
								$vc_Mensaje = '<font color="yellow">Error: '.mysqli_error($vc_DbConfig).' ejecutando '.$vc_Sql.'<br>Contacta al administrador del sitio<br></font>';
								printf("%s\n",$vc_Mensaje);
								exit(1);
							}
		//					else echo $vc_Sql;
							// Agrega registro para insertar nuevo documento
							$vc_Html.='<tr class="grid0"><td><a href="upd_doc.php?doc_id=-1">0</a></td>';
							$vc_Html.='<td>Por definir</td><td>Por definir</td>';
							$vc_Html.='<td>Por definir</td><td>Por definir</td>';
							$vc_Html.='<td>Por definir</td><td>Por definir</td></tr>';
							$vR_RowIns=mysqli_fetch_array($vr_ResultSet,MYSQLI_ASSOC);
							$vc_Grid ='<tr class="grid'.$vn_i.'"><td><a href="upd_doc.php?doc_id='.$vR_RowIns['id'].'">'.$vR_RowIns['id'].'</a></td>';
							$vc_Grid.='<td>'.$vR_RowIns['nombre'].'</td>';
							$vc_Grid.='<td>'.$vR_RowIns['display'].'</td>';
							$vc_Grid.='<td><input type="text" name="tb_orden_'.$vR_RowIns['id'].'" size="2" value="'.$vR_RowIns['orden'].'"></td>';
							$vc_Grid.='<td><input type="checkbox" name="cb_activo_'.$vR_RowIns['id'].'" value="'.$vR_RowIns['activo'].'"';
							if ( $vR_RowIns['activo'] == "S" ) {
								$vc_Grid.=' checked';
							}
							$vc_Grid.='></td>';
							$vc_Grid.='<td>'.$vR_RowIns['fch_ins'].'</td>';
							$vc_Grid.='<td>'.$vR_RowIns['fch_upd'];
							$vc_Grid.='</td></tr>';
							while($vR_RowIns = mysqli_fetch_assoc($vr_ResultSet))
							{
								if ( $vn_i == 1 ) $vn_i = 0;
								else $vn_i = 1;
									$vc_Grid.='<tr class="grid'.$vn_i.'"><td><a href="upd_doc.php?doc_id='.$vR_RowIns['id'].'">'.$vR_RowIns['id'].'</a></td>';
									$vc_Grid.='<td>'.$vR_RowIns['nombre'].'</td>';
									$vc_Grid.='<td>'.$vR_RowIns['display'].'</td>';
									$vc_Grid.='<td><input type="text" name="tb_orden_'.$vR_RowIns['id'].'" size="2" value="'.$vR_RowIns['orden'].'"></td>';
									$vc_Grid.='<td><input type="checkbox" name="cb_activo_'.$vR_RowIns['id'].'" value="'.$vR_RowIns['activo'].'"';
									if ( $vR_RowIns['activo'] == "S" ) {
										$vc_Grid.=' checked';
									}
									$vc_Grid.='></td>';
									$vc_Grid.='<td>'.$vR_RowIns['fch_ins'].'</td>';
									$vc_Grid.='<td>'.$vR_RowIns['fch_upd'];
									$vc_Grid.='</td></tr>';
							}
							$vc_Html.=$vc_Grid.'</tr>';
							$vc_Html.='<tr><td></td><td class="button" colspan="2"><input id="Actualizar" type="submit" value="Actualizar" onclick="return myId(this);"></td></tr>';
							$vc_Html.='</table>';
							$vc_Html.='</div>';

							printf("%s\n",$vc_Html);
						} // Termina cuando es una consulta de documentos
						else {
							$vn_Min_Doc_Id = intval($_SESSION['gn_Min_Doc_Id']);
							$vn_Max_Doc_Id = intval($_SESSION['gn_Max_Doc_Id']) + 1;
//							printf("%d and %d<br>",$vn_Min_Doc_Id,$vn_Max_Doc_Id);

							$vc_Html = '';
							$vn_Tot_Doc = 0;

							for ( $i = $vn_Min_Doc_Id; $i < $vn_Max_Doc_Id; $i++ ) {
								if (isset($_POST['tb_orden_'.$i])) {
									$vn_Orden=intval($_POST['tb_orden_'.$i]);
									if ( isset($_POST['cb_activo_'.$i]) ) {
										$vc_Activo="S";
									}
									else {
										$vc_Activo="N";
									}
									$vc_Sql ="update cmt_doc_documentos set doc_activo = '".$vc_Activo."', ";
									$vc_Sql.="doc_orden = ".$vn_Orden." ";
									$vc_Sql.="where doc_id = ".$i." ";
									$vc_Sql.="and (doc_orden <> ".$vn_Orden." or doc_activo <> '".$vc_Activo."') ";
									/*
									printf("%s<br>",$vc_Sql);
 									exit(1);
									*/
									if ( !(mysqli_query($vc_DbConfig, $vc_Sql)) )
									{
										$vc_Mensaje = '<font color="yellow">Error: '.mysqli_error($vc_DbConfig).' ejecutando '.$vc_Sql.'<br>Contacta al administrador del sitio<br></font>';
										printf("%s\n",$vc_Mensaje);
										exit(1);
									}
									else {
										$vn_Tot_Doc++;
									}
								} // End Update documentos
								else {
									$vc_Mensaje = '<font color="yellow">Error al evaluar actualización masiva<br>Contacta al administrador del sitio<br></font>';
									printf("%s\n",$vc_Mensaje);
									exit(1);
								}
							} // End For
							$vc_Mensaje ='<div class="mensaje">'.$vn_Tot_Doc.' documentos actualizados.</div>';
							printf("%s",$vc_Mensaje);
						} // Termina actualización masiva
					} // End Post
?>
			</form>
		</div>
	</body>
</html>
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
					if (!isset($_POST['ddl_tor_id'])) {
						$vn_Tor_Id=0;
					}
					else {
						$vn_Tor_Id=intval($_POST['ddl_tor_id']);
					}

					if (!isset($_POST['ddl_cat_id'])) {
						$vn_Cat_Id=0;
					}
					else {
						$vn_Cat_Id=intval($_POST['ddl_cat_id']);
					}

					if (!isset($_POST['ddl_pagado'])) {
						$vc_Pagado="0";
					}
					else {
						$vc_Pagado=$_POST['ddl_pagado'];
					}

					if (!isset($_POST['ddl_inscrito'])) {
						$vc_Inscrito="0";
					}
					else {
						$vc_Inscrito=$_POST['ddl_inscrito'];
					}

					if (!isset($_POST['ddl_muestra_comprobantes'])) {
						$vc_MuestraComprobantes="N";
					}
					else {
						$vc_MuestraComprobantes=$_POST['ddl_muestra_comprobantes'];
					}

					if (!isset($_POST['ddl_genera_lista_preliminar'])) {
						$vc_GeneraListaPreliminar="N";
					}
					else {
						$vc_GeneraListaPreliminar=$_POST['ddl_genera_lista_preliminar'];
					}

					$vc_Html = '';
					$vc_Html.='<input id="th_clicked_button" type="hidden" name="th_clicked_button"/>';
					$vc_Html.='<table class="seleccion">';
					$vc_Html.='<tr class="encabezado"><td colspan="2">Mantenimiento de inscripciones</td></tr>';

					// LOV Torneos
					$vc_Sql = "select tor_id as id, concat(tor_nombre,' en ',clu_nombre) as descr ";
					$vc_Sql.="from cmt_tor_torneos, cmt_clu_clubes ";
					$vc_Sql.="where 1 = 1 ";
					$vc_Sql.="and tor_clu_id = clu_id ";
					$vc_Sql.="and tor_activo = 'S' ";
					$vc_Sql.="order by case tor_id when ".$vn_Tor_Id." then 0 else 1 end, ";
					$vc_Sql.="abs(datediff(sysdate(),tor_fch_inicio)) ";
					$vr_ResultSet=mysqli_query($vc_DbConfig,$vc_Sql);
					if (!$vr_ResultSet) {
						$vc_Mensaje = '<font color="yellow">Error: '.mysqli_error($vc_DbConfig).' ejecutando '.$vc_Sql.'<br>Contacta al administrador del sitio<br></font>';
						printf("%s\n",$vc_Mensaje);
						exit(1);
					}
//					else echo $vc_Sql;
					$vR_Row=mysqli_fetch_array($vr_ResultSet,MYSQLI_ASSOC);
					$vc_Ddl_Torneos = '<tr><td align="right"><b>Torneo:</b></td><td><select id="ddl_tor_id" name="ddl_tor_id">';
					$vc_Ddl_Torneos.='<option value="'.$vR_Row['id'].'">'.$vR_Row['descr'].'</option>';
					while($vR_Row = mysqli_fetch_assoc($vr_ResultSet))
					{
					  $vc_Ddl_Torneos.='<option value="'.$vR_Row['id'].'">'.$vR_Row['descr'].'</option>';
					}
					$vc_Ddl_Torneos.='</select>';
					$vc_Html.=$vc_Ddl_Torneos.'</td></tr>';

					// LOV Categorías
					$vc_Sql = "select cat_id as id, cat_nombre as descr, cat_orden as orden ";
					$vc_Sql.="from cmt_cat_categorias ";
					$vc_Sql.="where 1 = 1 ";
					$vc_Sql.="union ";
					$vc_Sql.="select 0, 'Todas', 99 ";
					$vc_Sql.="order by case id when ".$vn_Cat_Id." then 0 else 1 end, orden, id";
					$vr_ResultSet=mysqli_query($vc_DbConfig,$vc_Sql);
					if (!$vr_ResultSet) {
						$vc_Mensaje = '<font color="yellow">Error: '.mysqli_error($vc_DbConfig).' ejecutando '.$vc_Sql.'<br>Contacta al administrador del sitio<br></font>';
						printf("%s\n",$vc_Mensaje);
						exit(1);
					}
//					else echo $vc_Sql;
					$vR_Row=mysqli_fetch_array($vr_ResultSet,MYSQLI_ASSOC);
					$vc_Ddl_Categorias = '<tr><td align="right"><b>Categor&iacute;a:</b></td><td><select id="ddl_cat_id" name="ddl_cat_id">';
					$vc_Ddl_Categorias.='<option value="'.$vR_Row['id'].'">'.$vR_Row['descr'].'</option>';
					while($vR_Row = mysqli_fetch_assoc($vr_ResultSet))
					{
					  $vc_Ddl_Categorias.='<option value="'.$vR_Row['id'].'">'.$vR_Row['descr'].'</option>';
					}
					$vc_Ddl_Categorias.='</select>';
					$vc_Html.=$vc_Ddl_Categorias.'</td></tr>';
					
					// LOV Pagado
					$vc_Ddl_Pagado = '<tr><td align="right"><b>Pagado?</b></td><td><select id="ddl_pagado" name="ddl_pagado">';
					$vc_Ddl_Pagado.='<option value="0">Todos</option>';
					$vc_Ddl_Pagado.='<option value="N">N</option>';
					$vc_Ddl_Pagado.='<option value="S">S</option>';
					$vc_Ddl_Pagado.='</select>';
					$vc_Html.=$vc_Ddl_Pagado.'</td></tr>';

					// LOV Inscrito
					$vc_Ddl_Inscrito = '<tr><td align="right"><b>Inscrito?</b></td><td><select id="ddl_inscrito" name="ddl_inscrito">';
					$vc_Ddl_Inscrito.='<option value="0">Todos</option>';
					$vc_Ddl_Inscrito.='<option value="N">N</option>';
					$vc_Ddl_Inscrito.='<option value="S">S</option>';
					$vc_Ddl_Inscrito.='</select>';
					$vc_Html.=$vc_Ddl_Inscrito.'</td></tr>';

					// LOV Muestra comprobante?
					$vc_Ddl_MuestraComprobante = '<tr><td align="right"><b>Muestra comprobante?</b></td><td><select id="ddl_muestra_comprobantes" name="ddl_muestra_comprobantes">';
					$vc_Ddl_MuestraComprobante.='<option value="N">N</option>';
					$vc_Ddl_MuestraComprobante.='<option value="S">S</option>';
					$vc_Ddl_MuestraComprobante.='</select>';
					$vc_Html.=$vc_Ddl_MuestraComprobante.'</td></tr>';					
					
					// LOV Genera lista preliminar?
					$vc_Ddl_GeneraListaPreliminar = '<tr><td align="right"><b>Genera lista preliminar?</b></td><td><select id="ddl_genera_lista_preliminar" name="ddl_genera_lista_preliminar">';
					$vc_Ddl_GeneraListaPreliminar.='<option value="N">N</option>';
					$vc_Ddl_GeneraListaPreliminar.='<option value="S">S</option>';
					$vc_Ddl_GeneraListaPreliminar.='</select>';
					$vc_Html.=$vc_Ddl_GeneraListaPreliminar.'</td></tr>';					

					$vc_Html.='<tr><td></td><td><input id="Consultar" type="submit" value="Consultar" onclick="return myId(this);"><br><br></td></tr>';					
					$vc_Html.='</table>';

					printf("%s\n",$vc_Html);
					//printf("Clicked: %s\n",$vc_Clicked);

					if($_SERVER['REQUEST_METHOD']=='POST') {
						// Asignando valores
						$vn_Tor_Id=intval($_POST['ddl_tor_id']);
						$vn_Cat_Id=intval($_POST['ddl_cat_id']);
						$vc_Clicked=$_POST['th_clicked_button'];

						if ( $vc_Clicked == "Consultar" ) {
							// Conteo de inscripciones
							$vc_SqlTmp="select count(1) as tot_inscr, min(ins_id) as min_ins_id, max(ins_id) as max_ins_id ";
							$vc_Sql="from cmt_ins_inscripciones, cmt_jug_jugadores, cmt_tor_torneos, ";
							$vc_Sql.="cmt_cat_categorias, cmt_clu_clubes ";
							$vc_Sql.="where 1 = 1 ";
							$vc_Sql.="and ins_jug_id = jug_id ";
							$vc_Sql.="and jug_clu_id = clu_id ";
							$vc_Sql.="and ins_tor_id = tor_id ";
							$vc_Sql.="and ins_cat_id = cat_id ";
							if ( 0 < $vn_Tor_Id ) {
								$vc_Sql.="and ins_tor_id = ".$vn_Tor_Id." ";
							}
							if ( 0 < $vn_Cat_Id ) {
								$vc_Sql.="and ins_cat_id = ".$vn_Cat_Id." ";
							}
							if ( $vc_Pagado != "0" ) {
								$vc_Sql.="and ins_pagado = '".$vc_Pagado."' ";
							}
							if ( $vc_Inscrito != "0" ) {
								$vc_Sql.="and ins_inscrito = '".$vc_Inscrito."' ";
							}
							$vc_SqlTmp.=$vc_Sql;
							$vr_ResultSet=mysqli_query($vc_DbConfig,$vc_SqlTmp);
							if (!$vr_ResultSet) {
								$vc_Mensaje = '<font color="yellow">Error: '.mysqli_error($vc_DbConfig).' ejecutando '.$vc_SqlTmp.' Contacta al administrador del sitio<br></font>';
								printf("%s\n",$vc_Mensaje);
								exit(1);
							}
//							else printf("Kmoso 0: %s\n",$vc_SqlTmp);
							$vR_Row=mysqli_fetch_array($vr_ResultSet,MYSQLI_ASSOC);
							$vc_HtmlTmp='<b>'.$vR_Row['tot_inscr'].' inscripciones</b>';
							$vc_Html = '';
							$vc_Html.=$vc_HtmlTmp;
							$_SESSION['gn_Min_Ins_Id']=$vR_Row['min_ins_id'];
							$_SESSION['gn_Max_Ins_Id']=$vR_Row['max_ins_id'];
							
							if ($vR_Row['tot_inscr'] == 0) {
								$vc_Html.= '';
							}
							else {							
								$vc_Html.='<div class="main_grid">';
								$vc_Html.='<table class="grid">';
								$vc_Html.='<tr class="grid0">';
								$vc_Html.='<td><b>#</b></td><td><b>Torneo</b></td><td><b>Categor&iacute;a</b></td>';
								$vc_Html.='<td><b>Puntos</b></td>';
								$vc_Html.='<td><b>Club</b></td><td><b>Jugador</b></td>';
								$vc_Html.='<td><b>Email</b></td><td><b>Tel&eacute;fono</b></td>';
								$vc_Html.='<td><b>Fch Inscripci&oacute;n</b></td><td><b>Pagado?</b></td>';
								$vc_Html.='<td><b>Costo</b></td><td><b>Inscrito?</b></td><td><b>Black List?</b></td><td><b>Comentarios</b></td>';
								if ( $vc_MuestraComprobantes == "S" ) $vc_Html.='<td><b>Comprobante</b></td>';
								$vc_Html.='</tr>';

								// Información de Inscripciones
								$vc_SqlTmp="select ins_id as id, clu_nombre as club, jug_nombre as jugador, tor_nombre as torneo, ";
								$vc_SqlTmp.="cat_nombre as categoria, ins_fch_inscripcion as fch_inscr, ins_pagado as pagado, ";
								$vc_SqlTmp.="ins_costo as costo, ins_inscrito as inscrito, ins_comprobante as comprobante, ";
								$vc_SqlTmp.="ins_comentarios as comentarios, jug_email as email, jug_telefono as telefono, ";
								$vc_SqlTmp.="jug_en_lista_negra as blacklist, jug_puntos as puntos ";
								$vc_Sql=$vc_SqlTmp.$vc_Sql;
								$vc_Sql.="order by tor_fch_inicio, cat_orden, jug_nombre, ins_id ";
								$vn_i=1;
								$vr_ResultSet=mysqli_query($vc_DbConfig,$vc_Sql);
								if (!$vr_ResultSet) {
									$vc_Mensaje = '<font color="yellow">Error: '.mysqli_error($vc_DbConfig).' ejecutando '.$vc_Sql.'<br>Contacta al administrador del sitio<br></font>';
									printf("%s\n",$vc_Mensaje);
									exit(1);
								}
			//					else echo $vc_Sql;
								$vR_RowIns=mysqli_fetch_array($vr_ResultSet,MYSQLI_ASSOC);
								
								// Información para mostrar en la lista preliminar
								$vc_ListaPreliminar='<html><title>Lista preliminar</title><body>';
								$vc_ListaPreliminar.='<table border="1" align="center">';
								$vc_ListaPreliminar.='<tr><td colspan="6" align="center"><img src="../imgs/backgrounds.png"></td></tr>';
								$vc_ListaPreliminar.='<tr><td><b># Ins</b></td><td><b>Torneo</b></td>';
								$vc_ListaPreliminar.='<td><b>Categor&iacute;a</b></td>';
								$vc_ListaPreliminar.='<td><b>Club</b></td><td><b>Jugador</b></td>';
								$vc_ListaPreliminar.='<td><b>Puntos</b></td></tr>';
								$vc_ListaPreliminar.='<tr><td>'.$vR_RowIns['id'].'</td><td>'.$vR_RowIns['torneo'];
								$vc_ListaPreliminar.='</td><td>'.$vR_RowIns['categoria'];
								$vc_ListaPreliminar.='</td><td>'.$vR_RowIns['club'];
								$vc_ListaPreliminar.='</td><td>'.$vR_RowIns['jugador'];
								$vc_ListaPreliminar.='</td><td>'.$vR_RowIns['puntos'].'</td></tr>';
								
								$vc_Grid ='<tr class="grid'.$vn_i.'"><td><a href="upd_ins.php?ins_id='.$vR_RowIns['id'].'">'.$vR_RowIns['id'].'</a></td>';
								$vc_Grid.='<td>'.$vR_RowIns['torneo'].'</td>';
								$vc_Grid.='<td>'.$vR_RowIns['categoria'].'</td><td>'.$vR_RowIns['puntos'].'</td>';
								$vc_Grid.='<td>'.$vR_RowIns['club'].'</td>';
								$vc_Grid.='<td>'.$vR_RowIns['jugador'].'</td>';
								$vc_Grid.='<td>'.$vR_RowIns['email'].'</td><td>'.$vR_RowIns['telefono'].'</td>';
								$vc_Grid.='<td>'.$vR_RowIns['fch_inscr'].'</td><td>'.$vR_RowIns['pagado'].'</td>';
								$vc_Grid.='<td>'.$vR_RowIns['costo'].'</td>';
								$vc_Grid.='<td><input type="checkbox" name="cb_inscrito_'.$vR_RowIns['id'].'" value="'.$vR_RowIns['inscrito'].'"';
								if ( $vR_RowIns['inscrito'] == "S" ) {
									$vc_Grid.=' checked';
								}
								$vc_Grid.='></td>';
								$vc_Grid.='<td>'.$vR_RowIns['blacklist'].'</td>';
								$vc_Grid.='<td>'.$vR_RowIns['comentarios'].'</td>';
								if ( $vc_MuestraComprobantes == "S" ) {
									if ( $vR_RowIns['comprobante'] != NULL ) {
										$vc_Grid.='<td><img src="uploads/'.$vR_RowIns['comprobante'].'" heigth="200" width="200"/></td>';
									}
								}
								$vc_Grid.='</tr>';
								while($vR_RowIns = mysqli_fetch_assoc($vr_ResultSet))
								{
									if ( $vn_i == 1 ) $vn_i = 0;
									else $vn_i = 1;

									// Información para mostrar en la lista preliminar
									$vc_ListaPreliminar.='<tr><td>'.$vR_RowIns['id'].'</td><td>'.$vR_RowIns['torneo'];
									$vc_ListaPreliminar.='</td><td>'.$vR_RowIns['categoria'];
									$vc_ListaPreliminar.='</td><td>'.$vR_RowIns['club'];
									$vc_ListaPreliminar.='</td><td>'.$vR_RowIns['jugador'];
									$vc_ListaPreliminar.='</td><td>'.$vR_RowIns['puntos'].'</td></tr>';

									$vc_Grid.='<tr class="grid'.$vn_i.'"><td><a href="upd_ins.php?ins_id='.$vR_RowIns['id'].'">'.$vR_RowIns['id'].'</a></td><td>'.$vR_RowIns['torneo'].'</td>';
									$vc_Grid.='<td>'.$vR_RowIns['categoria'].'</td><td>'.$vR_RowIns['puntos'].'</td>';
									$vc_Grid.='<td>'.$vR_RowIns['club'].'</td>';
									$vc_Grid.='<td>'.$vR_RowIns['jugador'].'</td>';
									$vc_Grid.='<td>'.$vR_RowIns['email'].'</td><td>'.$vR_RowIns['telefono'].'</td>';
									$vc_Grid.='<td>'.$vR_RowIns['fch_inscr'].'</td><td>'.$vR_RowIns['pagado'].'</td>';
									$vc_Grid.='<td>'.$vR_RowIns['costo'].'</td>';
									$vc_Grid.='<td><input type="checkbox" name="cb_inscrito_'.$vR_RowIns['id'].'" value="'.$vR_RowIns['inscrito'].'"';
									if ( $vR_RowIns['inscrito'] == "S" ) {
										$vc_Grid.=' checked';
									}
									$vc_Grid.='></td>';
									$vc_Grid.='<td>'.$vR_RowIns['blacklist'].'</td>';
									$vc_Grid.='<td>'.$vR_RowIns['comentarios'].'</td>';
									if ( $vc_MuestraComprobantes == "S" ) {
										if ( $vR_RowIns['comprobante'] != NULL ) {
											$vc_Grid.='<td><img src="uploads/'.$vR_RowIns['comprobante'].'" heigth="200" width="200"/></td>';
										}
									}
									$vc_Grid.='</tr>';
								}
								$vc_Html.=$vc_Grid.'</tr>';

								$vc_Html.='<tr><td colspan="14" align="center"><input id="Actualizar" type="submit" value="Actualiza Inscritos" onclick="return myId(this);"></td></tr>';
								$vc_Html.='</table>';
								$vc_Html.='</div>';
							} // Termina código para desplegar más de 0 inscripciones
							// Genera lista preliminar
							if ( $vc_GeneraListaPreliminar == "S" ) {
								$vf_ListaPreliminar = fopen("docs/ListaPreliminar.html", "w") or die("No se puede generar la lista preliminar!");
								$vc_ListaPreliminar.="</table></body></html>";
								fwrite($vf_ListaPreliminar, $vc_ListaPreliminar);
								fclose($vf_ListaPreliminar);
							}
							printf("%s\n",$vc_Html);
						} // Termina código cuando se oprimió el botón Consultar
						else {
							// Para actualización masiva
							$vn_Min_Ins_Id = intval($_SESSION['gn_Min_Ins_Id']);
							$vn_Max_Ins_Id = intval($_SESSION['gn_Max_Ins_Id']) + 1;
//							printf("%d and %d<br>",$vn_Min_Ins_Id,$vn_Max_Ins_Id);

							$vc_Html = '';
							$vn_Tot_Ins = 0;

							for ( $i = $vn_Min_Ins_Id; $i < $vn_Max_Ins_Id; $i++ ) {
								if (isset($_POST['cb_inscrito_'.$i])) {
									$vc_Sql = "update cmt_ins_inscripciones set ins_inscrito = 'N' ";
									$vc_Sql.="where ins_id = ".$i." ";
									$vc_Sql.="and ins_tor_id = ".$vn_Tor_Id." ";
									$vc_Sql.="and ins_cat_id = ".$vn_Cat_Id." ";
									$vc_Sql.="and ins_inscrito = 'S'";
//									printf("%s<br>",$vc_Sql);
									if ( !(mysqli_query($vc_DbConfig, $vc_Sql)) )
									{
										$vc_Mensaje = '<font color="yellow">Error: '.mysqli_error($vc_DbConfig).' ejecutando '.$vc_Sql.'<br>Contacta al administrador del sitio<br></font>';
										printf("%s\n",$vc_Mensaje);
										exit(1);
									}
									else {
										$vn_Tot_Ins++;
									}
								} // End Update inscripciones
							} // End For

							for ( $i = $vn_Min_Ins_Id; $i < $vn_Max_Ins_Id; $i++ ) {
								if (isset($_POST['cb_inscrito_'.$i])) {
									$vc_Sql = "update cmt_ins_inscripciones set ins_inscrito = 'S' ";
									$vc_Sql.="where ins_id = ".$i." ";
									$vc_Sql.="and ins_tor_id = ".$vn_Tor_Id." ";
									$vc_Sql.="and ins_cat_id = ".$vn_Cat_Id." ";
									$vc_Sql.="and ins_inscrito = 'N'";
//									printf("%s<br>",$vc_Sql);
									if ( !(mysqli_query($vc_DbConfig, $vc_Sql)) )
									{
										$vc_Mensaje = '<font color="yellow">Error: '.mysqli_error($vc_DbConfig).' ejecutando '.$vc_Sql.'<br>Contacta al administrador del sitio<br></font>';
										printf("%s\n",$vc_Mensaje);
										exit(1);
									}
									else {
										$vn_Tot_Ins++;
									}
								} // End Update inscripciones
							} // End For
							$vc_Mensaje ='<div class="mensaje">'.$vn_Tot_Ins.' inscripciones confirmadas.</div>';
							printf("%s",$vc_Mensaje);
						}
					} // End Post
?>
			</form>
		</div>
	</body>
</html>
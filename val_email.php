<?php
	include("dbconfig.php");
	session_start();
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Circuito Mexicano de Tenistas</title>
		<link rel="stylesheet" href="screenCMT.css">
	</head>
	<header>
		<a class="exit" href="logout.php"></a><br>
	</header>
	<body>
		<script type="text/javascript">
		  myId = function (element) {
			  document.getElementById("th_clicked_button").value = element.id;
			  return true;
		  }
		</script>
		<div id="page">
			<form name="InsForm" method="POST" accept-charset="utf-8" >
<?php
				$vc_Etiqueta = "Siguiente";
				$vc_CallInscripcion = "N";
				$vc_NuevoJugador = "N";
				if (!isset($_POST['ddl_jug_cmt'])) $vn_Jug_Id=0;
				else $vn_Jug_Id=intval($_POST['ddl_jug_cmt']);
				if (!isset($_POST['tb_inicial_jugador'])) $vc_InicialJugador="%";
				else {
					$vc_InicialJugador=$_POST['tb_inicial_jugador'];
					if ( $vc_InicialJugador == '%' ) $vc_InicialJugador = '%%';
				}
				if (!isset($_POST['tb_contador'])) $vn_Contador=0;
				else $vn_Contador=intval($_POST['tb_contador'])+1;
				if (isset($_POST['cb_inscribe'])) $vc_Inscribe="S";
				else $vc_Inscribe="N";

				$vc_Html = '';
				$vc_Html.='<br><br><input type="hidden" name="tb_contador" id="tb_contador" value="'.$vn_Contador.'">';
				$vc_Html.='<table class="seleccion">';
				$vc_Html.='<tr class="encabezado"><td colspan="2">Validaci&oacute;n de informaci&oacute;n</td></tr>';
				$vc_Html.='<tr><td align="right"><b>¿Ya has jugado torneos del circuito?:</b></td><td>';
				$vc_Html.='<select id="ddl_jug_cmt" name="ddl_jug_cmt">';
				$vc_Html.='<option value="1">S&iacute;</option>';
				$vc_Html.='<option value="0">No</option>';
				$vc_Html.='</select>';
				$vc_Html.='</td></tr>';
				if ( $vn_Contador == 0 ) $vc_Html.='<tr><td></td><td><input type="submit" value="'.$vc_Etiqueta.'"></td></tr>';

				if($_SERVER['REQUEST_METHOD']=='POST') {
					if ( $vc_InicialJugador == '%%' ) {
						$vc_CallInscripcion = "S";
						$vc_NuevoJugador = "S";
					}
					if ( $vn_Jug_Id == 1 ) {
						$vc_Etiqueta = "Consultar";
						$vc_Html.='<tr><td align="right"><b>Indica tu nombre ó correo:</b></td><td><input type="text" name="tb_inicial_jugador" value="'.$vc_InicialJugador.'"></tr>';
						$vc_Html.='<tr><td></td><td><input type="submit" name="pb_consultar" value="'.$vc_Etiqueta.'"></td></tr>';
						$vc_Html.='<tr><td align="center" colspan="2"><b><font size="2" color="yellow">Usa % como comod&iacute;n. E.g. %ricard%guerre% para encontrar a Ricardo Guerrero</font></b></td></tr>';
						if ( $vn_Contador > 1 ) {
							if ( $vc_InicialJugador == '%' ) {
								$vc_CallInscripcion = "S";
								$vc_InicialJugador = '%%';
								$vc_NuevoJugador = "S";
							}
							if ( $vc_Inscribe == "N" ) {
								if ( $vc_NuevoJugador == "N" ) {
									$vc_Etiqueta = "Inscribir";
									$vc_Html.='<table class="medium_grid">';
									$vc_Html.='<tr class="grid0">';
									$vc_Html.='<td><b>#</b></td>';
									$vc_Html.='<td><b>Club</b></td>';
									$vc_Html.='<td><b>Categor&iacute;a Registrada</b></td>';
									$vc_Html.='<td><b>Jugador</b></td>';
									$vc_Html.='<td><b>Puntos</b></td>';
									$vc_Html.='<td><b>Inscribir?</b></td>';

									// Información de Jugadores
									$vc_Sql ="select jug_id, jug_nombre, clu_nombre as club, ";
									$vc_Sql.="jug_clu_id, jug_cat_id, jug_email, jug_telefono, jug_fch_nacimiento, jug_rama, ";
									$vc_Sql.="cat_nombre as categoria, jug_puntos ";
									$vc_Sql.="from cmt_jug_jugadores, cmt_clu_clubes, cmt_cat_categorias ";
									$vc_Sql.="where 1 = 1 ";
									$vc_Sql.="and jug_activo = 'S' ";
									$vc_Sql.="and jug_clu_id = clu_id ";
									$vc_Sql.="and jug_cat_id = cat_id ";

									if ( $vc_InicialJugador != '%' ) {
										$vc_Sql.="and (upper(jug_nombre) like upper('%".$vc_InicialJugador."%') ";
										$vc_Sql.="or upper(jug_email) like upper('%".$vc_InicialJugador."%')) ";
									}
									$vc_Sql.="order by jug_nombre, jug_puntos desc ";
									$vn_i=1;
									$vr_ResultSet=mysqli_query($vc_DbConfig,$vc_Sql);
									if (!$vr_ResultSet) {
										$vc_Mensaje = '<font color="yellow">Error: '.mysqli_error($vc_DbConfig).' ejecutando '.$vc_Sql.'<br>Contacta al administrador del sitio<br></font>';
										printf("%s\n",$vc_Mensaje);
										exit(1);
									}
				//					else echo $vc_Sql;
									$vR_RowIns=mysqli_fetch_array($vr_ResultSet,MYSQLI_ASSOC);
									$vc_Grid ='<tr class="grid'.$vn_i.'"><td>'.$vR_RowIns['jug_id'].'</td>';
									$vc_Grid.='<td>'.$vR_RowIns['club'].'</td>';
									$vc_Grid.='<td>'.$vR_RowIns['categoria'].'</td><td>'.$vR_RowIns['jug_nombre'].'</td>';
									$vc_Grid.='<td>'.$vR_RowIns['jug_puntos'].'</td>';
									$vc_Grid.='<td><input type="checkbox" name="cb_inscribe" value="N"></td>';
									$vc_Grid.='<td class="button"><input type="submit" value="'.$vc_Etiqueta.'"></td></tr>';
									$vc_Html.=$vc_Grid;
									$vc_Html.='</table>';

									// Asignando valores existentes en la base de datos
									$_SESSION['gc_DatosCompletos']='S';
									$_SESSION['gn_Jug_Id']=$vR_RowIns['jug_id'];
									$_SESSION['gn_Clu_Id']=$vR_RowIns['jug_clu_id'];
									$_SESSION['gn_Cat_Id']=$vR_RowIns['jug_cat_id'];
									$_SESSION['gc_Nombre']=$vR_RowIns['jug_nombre'];
									$_SESSION['gc_Email']=$vR_RowIns['jug_email'];
									$_SESSION['gc_Puntos']=$vR_RowIns['jug_puntos'];
									$_SESSION['gc_Telefono']=$vR_RowIns['jug_telefono'];
									$_SESSION['gc_Fch_Nacimiento']=$vR_RowIns['jug_fch_nacimiento'];
									$_SESSION['gc_Rama']=$vR_RowIns['jug_rama'];
								} // Termina cuando se encontró el nombre del jugador
//								printf("%s, %s y %s\n",$vc_InicialJugador, $vc_Inscribe, $vc_NuevoJugador);
							} // Termina cuando aún no se selecciona el checkbox para mandar la inscripción
							else {
								$vc_CallInscripcion = "S";
								$vc_NuevoJugador = "N";
							}
						}
//						else $vc_CallInscripcion = "S";
					}
					else {
						$vc_CallInscripcion = "S";
						$vc_NuevoJugador = "S";
					}
//					printf("Contador %d, Iniciales %s, Call %s, Inscribe existente? %s y Nuevo? %s\n",$vn_Contador, $vc_InicialJugador, $vc_CallInscripcion, $vc_Inscribe, $vc_NuevoJugador);
					if ( $vc_CallInscripcion == "S" ) {
						if ( $vc_NuevoJugador == "S" ) {
							// Asignando valores default
							$_SESSION['gc_DatosCompletos']='N';
							$_SESSION['gn_Jug_Id']=0;
							$_SESSION['gn_Clu_Id']=1;
							$_SESSION['gn_Cat_Id']=1;
							$_SESSION['gc_Nombre']='';
							$_SESSION['gc_Email']='@';
							$_SESSION['gc_Puntos']='0';
							$_SESSION['gc_Telefono']='';
							$_SESSION['gc_Fch_Nacimiento']='2012-01-02';
							$_SESSION['gc_Rama']='Varonil';
						}
						echo '<script>window.location.assign("ins_ins.php")</script>';
					}
				}
//				else {
//				}

			$vc_Html.='</table>';
			printf("%s\n",$vc_Html);

?>
			</form>
		</div>
	</body>
</html>
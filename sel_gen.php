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

					$vc_Header = '<br>';
					$vc_HeaderMensaje = $vc_Header.'<font color="yellow">';
					$vc_Html = '';
					$vc_Html.='<table class="seleccion">';
					$vc_Html.='<tr class="encabezado"><td colspan="2">Generaci&oacute;n de cuadros</td></tr>';

					// LOV Torneos
					$vc_Sql = "select tor_id as id, tor_nombre as descr ";
					$vc_Sql.="from cmt_tor_torneos ";
					$vc_Sql.="where 1 = 1 ";
					$vc_Sql.="and tor_activo = 'S' ";
					$vc_Sql.="order by case id when ".$vn_Tor_Id." then 0 else 1 end, tor_activo desc, tor_orden, id";
					$vr_ResultSet=mysqli_query($vc_DbConfig,$vc_Sql);
					if (!$vr_ResultSet) {
						$vc_Mensaje = $vc_HeaderMensaje.'Error: '.mysqli_error($vc_DbConfig).' ejecutando '.$vc_Sql.'<br>Contacta al administrador del sitio<br></font>';
						printf("%s\n",$vc_Mensaje);
						exit(1);
					}
//					else echo $vc_Sql;
					$vR_Row=mysqli_fetch_array($vr_ResultSet,MYSQLI_ASSOC);
					$vc_Ddl_Torneos = '<tr><td align="right"><b>Torneo:</b></td><td><select id="ddl_tor_id" name="ddl_tor_id">';
					$vc_Ddl_Torneos.='<option value="'.$vR_Row['id'].'">'.$vR_Row['descr'].'</option>';
					$vn_Tor_Id = $vR_Row['id'];
					while($vR_Row = mysqli_fetch_assoc($vr_ResultSet))
					{
					  $vc_Ddl_Torneos.='<option value="'.$vR_Row['id'].'">'.$vR_Row['descr'].'</option>';
					}
					$vc_Ddl_Torneos.='</select>';
					$vc_Html.=$vc_Ddl_Torneos.'</td></tr>';

					// LOV Categorías
					$vc_Sql = "select cat_id as id, cat_nombre as descr, cat_orden, count(1) as tot_inscritos ";
					$vc_Sql.= "from cmt_cat_categorias, cmt_ins_inscripciones ";
					$vc_Sql.= "WHERE 1 = 1 ";
//					$vc_Sql.= "AND ins_tor_id = ".$vn_Tor_Id." ";
					$vc_Sql.= "AND ins_cat_id = cat_id ";
					$vc_Sql.= "AND ins_inscrito = 'S' ";
					$vc_Sql.= "GROUP BY cat_id, cat_nombre, cat_orden ";
/*
					$vc_Sql.="where 1 = 1 ";
					$vc_Sql.="and exists (";
					$vc_Sql.="            select 1 ";
					$vc_Sql.="            from   cmt_cxt_categorias_x_torneo, cmt_tor_torneos ";
					$vc_Sql.="            where  1 = 1 ";
					$vc_Sql.="            and    tor_activo = 'S' ";
					$vc_Sql.="            and    cxt_tor_id = tor_id ";
					$vc_Sql.="            and    cxt_cat_id = cat_id ";
					$vc_Sql.="           ) ";
*/
					$vc_Sql.="order by case id when ".$vn_Cat_Id." then 0 else 1 end, cat_orden, id";
					$vr_ResultSet=mysqli_query($vc_DbConfig,$vc_Sql);
					if (!$vr_ResultSet) {
						$vc_Mensaje = $vc_HeaderMensaje.'Error: '.mysqli_error($vc_DbConfig).' ejecutando '.$vc_Sql.'<br>Contacta al administrador del sitio<br></font>';
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

					$vc_Html.='<tr><td></td><td><input type="submit" value="Generar"><br><br></td></tr>';
					
					$vc_Html.='</table>';

					printf("%s\n",$vc_Html);

					if($_SERVER['REQUEST_METHOD']=='POST') {
						// Asignando valores
						$vn_Tor_Id=intval($_POST['ddl_tor_id']);
						$vn_Cat_Id=intval($_POST['ddl_cat_id']);
						
						$vc_Html = '';
						$vc_Html.='<div class="main_grid">';
						$vc_Html.='<table class="grid">';
						$vc_Html.='<tr class="grid0">';
						$vc_Html.='<td><b>Mensajes y alertas</b></td>';
						$vc_Html.='</tr>';

						// Información de Jugadores
						$vc_Sql ="CALL cmt_prc_genera_draw_V4(";
						$vc_Sql.=$vn_Tor_Id.", ".$vn_Cat_Id.") ";
						$vr_ResultSet=mysqli_query($vc_DbConfig,$vc_Sql);
						if (!$vr_ResultSet) {
							$vc_Mensaje = $vc_HeaderMensaje.'Error: '.mysqli_error($vc_DbConfig).' ejecutando '.$vc_Sql.'<br>Contacta al administrador del sitio<br></font>';
							printf("%s\n",$vc_Mensaje);
							exit(1);
						}
//						else echo $vc_Sql;
						$vc_Mensaje = 'Cuadro generado para torneo '.$vn_Tor_Id.', categor&iacute;a '.$vn_Cat_Id;
						$vc_Html.='<tr class="grid1"><td>'.$vc_Mensaje.'</td></tr>';
						
						// Jugadores inscritos pero no incluidos en draw
                        $vc_Sql = "SELECT ins_jug_id as id, jug_nombre as nombre ";
						$vc_Sql.= "FROM cmt_ins_inscripciones, cmt_jug_jugadores, cmt_par_parametros tor, cmt_par_parametros cat ";
						$vc_Sql.= "WHERE 1 = 1 ";
						$vc_Sql.= "AND jug_activo = 'S' ";
						$vc_Sql.= "AND ins_jug_id = jug_id ";
						$vc_Sql.= "AND ins_tor_id = tor.par_valor ";
						$vc_Sql.= "AND tor.par_nombre = 'TOR_ID_INSCRITOS' ";
						$vc_Sql.= "AND ins_cat_id = cat.par_valor ";
						$vc_Sql.= "AND cat.par_nombre = 'CAT_ID_GENERACION_DRAW' ";
						$vc_Sql.= "AND ins_inscrito = 'S' ";
 						$vc_Sql.= "AND NOT EXISTS( ";
						$vc_Sql.= "	SELECT 1 ";
						$vc_Sql.= "	FROM cmt_tmp_draws ";
						$vc_Sql.= "	WHERE 1 = 1 ";
						$vc_Sql.= "	AND tmp_jug_id_local = jug_id ";
						$vc_Sql.= "	AND tmp_cat_id = ins_cat_id ";
						$vc_Sql.= "	) ";
						$vc_Sql.= "AND NOT EXISTS(";
						$vc_Sql.= "	SELECT 1 ";
						$vc_Sql.= "	FROM cmt_tmp_draws ";
						$vc_Sql.= "	WHERE 1 = 1 ";
						$vc_Sql.= "	AND tmp_jug_id_visita = jug_id ";
						$vc_Sql.= "	AND tmp_cat_id = ins_cat_id";
						$vc_Sql.= "	)";
						$vc_Sql.= "ORDER BY jug_nombre, jug_id";
						$vr_ResultSet=mysqli_query($vc_DbConfig,$vc_Sql);
						if (!$vr_ResultSet) {
							$vc_Mensaje = $vc_HeaderMensaje.'Error: '.mysqli_error($vc_DbConfig).' ejecutando '.$vc_Sql.'<br>Contacta al administrador del sitio<br></font>';
							printf("%s\n",$vc_Mensaje);
							exit(1);
						}

						$vR_RowAlertas=mysqli_fetch_array($vr_ResultSet,MYSQLI_ASSOC);
						$vc_Grid ='<tr class="grid1"><td>Jugadores inscritos pero no incluidos en el cuadro</td>';
						$vc_Grid.='</tr>';
						while($vR_RowAlertas = mysqli_fetch_assoc($vr_ResultSet))
						{
							$vc_Grid.='<tr class="grid0"><td>Jugador '.$vR_RowAlertas['id'].': '.$vR_RowAlertas['nombre'].'</td></tr>';
						}
						$vc_Html.=$vc_Grid;

						// Lista de participantes por categoría
						$vc_Sql = "SELECT   jug_nombre as nombre, clu_nombre as club, ";
						$vc_Sql.= "         tmp_siembra as siembra, jug_puntos as puntos ";
						$vc_Sql.= "FROM     cmt_tmp_draws, cmt_jug_jugadores, cmt_clu_clubes, cmt_par_parametros ";
						$vc_Sql.= "WHERE    1 = 1 ";
						$vc_Sql.= "AND      tmp_cat_id        = par_valor  ";
						$vc_Sql.= "AND      par_nombre        = 'CAT_ID_GENERACION_DRAW'  ";
						$vc_Sql.= "AND      tmp_jug_id_local  = jug_id ";
						$vc_Sql.= "AND      jug_clu_id        = clu_id ";
						$vc_Sql.= "AND      jug_id           <> 4 ";
						$vc_Sql.= "UNION ";
						$vc_Sql.= "SELECT   jug_nombre as nombre, clu_nombre as club, ";
						$vc_Sql.= "         tmp_siembra as siembra, jug_puntos as puntos ";
						$vc_Sql.= "FROM     cmt_tmp_draws, cmt_jug_jugadores, cmt_clu_clubes, cmt_par_parametros ";
						$vc_Sql.= "WHERE    1 = 1 ";
						$vc_Sql.= "AND      tmp_cat_id        = par_valor  ";
						$vc_Sql.= "AND      par_nombre        = 'CAT_ID_GENERACION_DRAW'  ";
						$vc_Sql.= "AND      tmp_jug_id_visita = jug_id ";
						$vc_Sql.= "AND      jug_clu_id        = clu_id ";
						$vc_Sql.= "AND      jug_id           <> 4 ";
						$vc_Sql.= "ORDER BY 3, 1, 2 ";
						
						$vc_NombreArchivo = "docs/siembra.csv";
						$vr_ResultSet=mysqli_query($vc_DbConfig,$vc_Sql);
						if (!$vr_ResultSet) {
							$vc_Mensaje = $vc_HeaderMensaje.'Error: '.mysqli_error($vc_DbConfig).' ejecutando '.$vc_Sql.'<br>Contacta al administrador del sitio<br></font>';
							printf("%s\n",$vc_Mensaje);
							exit(1);
						}

						// Abriendo archivo para escritura
						$vc_Archivo = fopen($vc_NombreArchivo,"w");

						$vR_RowAlertas=mysqli_fetch_array($vr_ResultSet,MYSQLI_ASSOC);
						$vc_Grid ='<tr class="grid1"><td><a href='.$vc_NombreArchivo.'>Descarga de lista de jugadores</a></td>';
						$vc_Grid.='</tr>';
						$vc_Linea = $vR_RowAlertas['siembra'].','.$vR_RowAlertas['nombre'].','.$vR_RowAlertas['club'].','.$vR_RowAlertas['puntos'];
						fputcsv($vc_Archivo,explode(',',$vc_Linea));
						while($vR_RowAlertas = mysqli_fetch_assoc($vr_ResultSet))
						{
							$vc_Linea = $vR_RowAlertas['siembra'].','.$vR_RowAlertas['nombre'].','.$vR_RowAlertas['club'].','.$vR_RowAlertas['puntos'];
							fputcsv($vc_Archivo,explode(',',$vc_Linea));
						}
						fclose($vc_Archivo); 
						$vc_Html.=$vc_Grid;
						
						// Información para generación de draw
						$vc_Sql = "SELECT tmp_partido as partido, tmp_siembra as siembra, tmp_ronda as ronda, ";
						$vc_Sql.= "loc.jug_id as id_local, loc.jug_nombre as local, clo.clu_nombre as club_local, ";
						$vc_Sql.= "		vis.jug_id as id_visita, vis.jug_nombre as visita, cvi.clu_nombre as club_visita ";
						$vc_Sql.= "FROM cmt_tmp_draws, cmt_jug_jugadores loc, cmt_clu_clubes clo, cmt_jug_jugadores vis, cmt_clu_clubes cvi, cmt_par_parametros cat, cmt_par_parametros ronda ";
						$vc_Sql.= "WHERE 1 = 1 ";
						$vc_Sql.= "AND tmp_cat_id = cat.par_valor  ";
						$vc_Sql.= "AND cat.par_nombre = 'CAT_ID_GENERACION_DRAW' ";
						$vc_Sql.= "AND ronda.par_nombre = 'RONDA_GENERACION_DRAWS' ";
						$vc_Sql.= "AND tmp_jug_id_local = loc.jug_id ";
						$vc_Sql.= "AND loc.jug_clu_id = clo.clu_id ";
						$vc_Sql.= "AND tmp_jug_id_visita = vis.jug_id ";
						$vc_Sql.= "AND vis.jug_clu_id = cvi.clu_id ";
						$vc_Sql.= "and tmp_ronda = ronda.par_valor ";
						$vc_Sql.= "ORDER BY tmp_partido ";
						
						$vc_NombreArchivo = "docs/cuadro.csv";
						$vr_ResultSet=mysqli_query($vc_DbConfig,$vc_Sql);
						if (!$vr_ResultSet) {
							$vc_Mensaje = $vc_HeaderMensaje.'Error: '.mysqli_error($vc_DbConfig).' ejecutando '.$vc_Sql.'<br>Contacta al administrador del sitio<br></font>';
							printf("%s\n",$vc_Mensaje);
							exit(1);
						}

						// Abriendo archivo para escritura
						$vc_Archivo = fopen($vc_NombreArchivo,"w");

						$vR_RowAlertas=mysqli_fetch_array($vr_ResultSet,MYSQLI_ASSOC);
						$vc_Grid ='<tr class="grid1"><td><a href='.$vc_NombreArchivo.'>Descarga de cuadro</a></td>';
						$vc_Grid.='</tr>';
						$vc_Linea = $vR_RowAlertas['partido'].','.$vR_RowAlertas['siembra'].','.$vR_RowAlertas['ronda'].',';
						$vc_Linea.= $vR_RowAlertas['id_local'].','.$vR_RowAlertas['local'].','.$vR_RowAlertas['club_local'].',';
						$vc_Linea.= $vR_RowAlertas['id_visita'].','.$vR_RowAlertas['visita'].','.$vR_RowAlertas['club_visita'];
						fputcsv($vc_Archivo,explode(',',$vc_Linea));
						while($vR_RowAlertas = mysqli_fetch_assoc($vr_ResultSet))
						{
							$vc_Linea = $vR_RowAlertas['partido'].','.$vR_RowAlertas['siembra'].','.$vR_RowAlertas['ronda'].',';
							$vc_Linea.= $vR_RowAlertas['id_local'].','.$vR_RowAlertas['local'].','.$vR_RowAlertas['club_local'].',';
							$vc_Linea.= $vR_RowAlertas['id_visita'].','.$vR_RowAlertas['visita'].','.$vR_RowAlertas['club_visita'];
							fputcsv($vc_Archivo,explode(',',$vc_Linea));
						}
						fclose($vc_Archivo); 
						$vc_Html.=$vc_Grid;
						
						$vc_Html.='</table>';
						$vc_Html.='</div>';

						printf("%s\n",$vc_Html);
						
					} // End Post
?>
			</form>
		</div>
	</body>
</html>
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
					if (!isset($_POST['ddl_clu_id'])) {
						$vn_Clu_Id=0;
					}
					else {
						$vn_Clu_Id=intval($_POST['ddl_clu_id']);
					}

					if (!isset($_POST['ddl_cat_id'])) {
						$vn_Cat_Id=0;
					}
					else {
						$vn_Cat_Id=intval($_POST['ddl_cat_id']);
					}

					if (!isset($_POST['tb_inicial_jugador'])) {
						$vc_InicialJugador="%";
					}
					else {
						$vc_InicialJugador=$_POST['tb_inicial_jugador'];
					}
					//printf("%s\n",$vc_InicialJugador);

					$vc_Html = '';
					$vc_Html.='<table class="seleccion">';
					$vc_Html.='<tr class="encabezado"><td colspan="2">Mantenimiento de jugadores</td></tr>';

					// LOV Clubes
					$vc_Sql = "select clu_id as id, clu_nombre as descr ";
					$vc_Sql.="from cmt_clu_clubes ";
					$vc_Sql.="where 1 = 1 ";
					$vc_Sql.="and clu_activo = 'S' ";
					$vc_Sql.="union ";
					$vc_Sql.="select 0, 'Todos' ";
					$vc_Sql.="order by case id when ".$vn_Clu_Id." then 0 else 1 end, descr";
					$vr_ResultSet=mysqli_query($vc_DbConfig,$vc_Sql);
					if (!$vr_ResultSet) {
						$vc_Mensaje = '<font color="yellow">Error: '.mysqli_error($vc_DbConfig).' ejecutando '.$vc_Sql.'<br>Contacta al administrador del sitio<br></font>';
						printf("%s\n",$vc_Mensaje);
						exit(1);
					}
//					else echo $vc_Sql;
					$vR_Row=mysqli_fetch_array($vr_ResultSet,MYSQLI_ASSOC);
					$vc_Ddl_Clubes = '<tr><td align="right"><b>Club:</b></td><td><select id="ddl_clu_id" name="ddl_clu_id">';
					$vc_Ddl_Clubes.='<option value="'.$vR_Row['id'].'">'.$vR_Row['descr'].'</option>';
					while($vR_Row = mysqli_fetch_assoc($vr_ResultSet))
					{
					  $vc_Ddl_Clubes.='<option value="'.$vR_Row['id'].'">'.$vR_Row['descr'].'</option>';
					}
					$vc_Ddl_Clubes.='</select>';
					$vc_Html.=$vc_Ddl_Clubes.'</td></tr>';

					// LOV Categorías
					$vc_Sql = "select cat_id as id, cat_nombre as descr, cat_orden ";
					$vc_Sql.="from cmt_cat_categorias ";
					$vc_Sql.="where 1 = 1 ";
					$vc_Sql.="union ";
					$vc_Sql.="select 0, 'Todas', 99 ";
					$vc_Sql.="order by case id when ".$vn_Cat_Id." then 0 else 1 end, cat_orden, id";
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

					// LOV Black List
					$vc_Ddl_BlackList = '<tr><td align="right"><b>Filtro Especial:</b></td><td><select id="ddl_en_blacklist" name="ddl_en_blacklist">';
					$vc_Ddl_BlackList.='<option value="T">Todos</option>';
					$vc_Ddl_BlackList.='<option value="S">En Black List</option>';
					$vc_Ddl_BlackList.='<option value="N">No est&aacute; en Black List</option>';
					$vc_Ddl_BlackList.='</select>';
					$vc_Html.=$vc_Ddl_BlackList.'</td></tr>';
					
					$vc_Html.='<tr><td><b>Inicial Jugador:</b></td><td><input type="text" name="tb_inicial_jugador" value="'.$vc_InicialJugador.'"%"></tr>';
					$vc_Html.='<tr><td></td><td><input type="submit" value="Consultar"><br><br></td></tr>';
					
					$vc_Html.='</table>';

					printf("%s\n",$vc_Html);

					if($_SERVER['REQUEST_METHOD']=='POST') {
						// Asignando valores
						$vn_Clu_Id=intval($_POST['ddl_clu_id']);
						$vn_Cat_Id=intval($_POST['ddl_cat_id']);
						$vc_En_Blacklist=$_POST['ddl_en_blacklist'];
						//$vc_InicialJugador=$POST['tb_inicial_jugador'];
						//printf("En POST %s\n",$vc_InicialJugador);
						
						$vc_Html = '';
						$vc_Html.='<div class="main_grid">';
						$vc_Html.='<table class="grid">';
						$vc_Html.='<tr class="grid0">';
						$vc_Html.='<td><b>#</b></td>';
						$vc_Html.='<td><b>Club</b></td>';
						$vc_Html.='<td><b>Categor&iacute;a</b></td>';
						$vc_Html.='<td><b>Jugador</b></td>';
						$vc_Html.='<td><b>Email</b></td>';
						$vc_Html.='<td><b>Tel&eacute;fono</b></td>';
						$vc_Html.='<td><b>Fch Nacimiento</b></td>';
						$vc_Html.='<td><b>Rama</b></td>';
						$vc_Html.='<td><b>Activo</b></td>';
						$vc_Html.='<td><b>Black List?</b></td>';
						$vc_Html.='<td><b>Puntos</b></td>';
						$vc_Html.='<td><b>Comentarios</b></td>';
						$vc_Html.='<td><b>Fch Ins</b></td>';
						$vc_Html.='<td><b>Fch Upd</b></td></tr>';

						// Información de Jugadores
						$vc_Sql ="select jug_id as id, jug_nombre as jugador, clu_nombre as club, ";
						$vc_Sql.="cat_nombre as categoria, jug_email as email, jug_telefono as telefono, ";
						$vc_Sql.="jug_fch_nacimiento as fch_nacimiento, jug_rama as rama, jug_activo as activo, ";
						$vc_Sql.="jug_puntos as puntos, jug_en_lista_negra as blacklist, jug_comentarios as comentarios, ";
						$vc_Sql.="jug_fch_ins as fch_ins, jug_fch_upd as fch_upd ";
						$vc_Sql.="from cmt_jug_jugadores, cmt_clu_clubes, cmt_cat_categorias ";
						$vc_Sql.="where 1 = 1 ";
						$vc_Sql.="and jug_clu_id = clu_id ";
						$vc_Sql.="and jug_cat_id = cat_id ";
						if ( $vc_En_Blacklist == "S" ) {
							$vc_Sql.="and jug_en_lista_negra = 'S' ";
						}
						if ( $vc_En_Blacklist == "N" ) {
							$vc_Sql.="and jug_en_lista_negra = 'N' ";
						}
						if ( 0 < $vn_Clu_Id ) {
							$vc_Sql.="and jug_clu_id = ".$vn_Clu_Id." ";
						}
						if ( 0 < $vn_Cat_Id ) {
							$vc_Sql.="and jug_cat_id = ".$vn_Cat_Id." ";
						}
						if ( $vc_InicialJugador != '%' ) {
							$vc_Sql.="and upper(jug_nombre) like upper('%".$vc_InicialJugador."%') ";
						}
						$vc_Sql.="order by jug_nombre, jug_email ";
						$vn_i=1;
						$vr_ResultSet=mysqli_query($vc_DbConfig,$vc_Sql);
						if (!$vr_ResultSet) {
							$vc_Mensaje = '<font color="yellow">Error: '.mysqli_error($vc_DbConfig).' ejecutando '.$vc_Sql.'<br>Contacta al administrador del sitio<br></font>';
							printf("%s\n",$vc_Mensaje);
							exit(1);
						}
	//					else echo $vc_Sql;
// Agrega registro para insertar nuevo jugador
						$vc_Html.='<tr class="grid0"><td><a href="upd_jug.php?jug_id=-1">0</a></td>';
						$vc_Html.='<td>Por definir</td><td>Por definir</td>';
						$vc_Html.='<td>Por definir</td><td>Por definir</td>';
						$vc_Html.='<td>Por definir</td><td>Por definir</td>';
						$vc_Html.='<td>Por definir</td><td>Por definir</td>';
						$vc_Html.='<td>Por definir</td><td>Por definir</td>';
						$vc_Html.='<td>Por definir</td>';
						$vc_Html.='<td>Por definir</td><td>Por definir</td></tr>';

						$vR_RowIns=mysqli_fetch_array($vr_ResultSet,MYSQLI_ASSOC);
						$vc_Grid ='<tr class="grid'.$vn_i.'"><td><a href="upd_jug.php?jug_id='.$vR_RowIns['id'].'">'.$vR_RowIns['id'].'</a></td>';
						$vc_Grid.='<td>'.$vR_RowIns['club'].'</td>';
						$vc_Grid.='<td>'.$vR_RowIns['categoria'].'</td><td>'.$vR_RowIns['jugador'].'</td>';
						$vc_Grid.='<td>'.$vR_RowIns['email'].'</td><td>'.$vR_RowIns['telefono'].'</td>';
						$vc_Grid.='<td>'.$vR_RowIns['fch_nacimiento'].'</td><td>'.$vR_RowIns['rama'].'</td>';
						$vc_Grid.='<td>'.$vR_RowIns['activo'].'</td><td>'.$vR_RowIns['blacklist'].'</td>';
						$vc_Grid.='<td>'.$vR_RowIns['puntos'].'</td><td>'.$vR_RowIns['comentarios'].'</td>';
						$vc_Grid.='<td>'.$vR_RowIns['fch_ins'].'</td><td>'.$vR_RowIns['fch_upd'];
						$vc_Grid.='</td></tr>';
						while($vR_RowIns = mysqli_fetch_assoc($vr_ResultSet))
						{
							if ( $vn_i == 1 ) $vn_i = 0;
							else $vn_i = 1;
							$vc_Grid.='<tr class="grid'.$vn_i.'"><td><a href="upd_jug.php?jug_id='.$vR_RowIns['id'].'">'.$vR_RowIns['id'].'</a></td><td>'.$vR_RowIns['club'].'</td>';
							$vc_Grid.='<td>'.$vR_RowIns['categoria'].'</td><td>'.$vR_RowIns['jugador'].'</td>';
							$vc_Grid.='<td>'.$vR_RowIns['email'].'</td><td>'.$vR_RowIns['telefono'].'</td>';
							$vc_Grid.='<td>'.$vR_RowIns['fch_nacimiento'].'</td><td>'.$vR_RowIns['rama'].'</td>';
							$vc_Grid.='<td>'.$vR_RowIns['activo'].'</td><td>'.$vR_RowIns['blacklist'].'</td>';
							$vc_Grid.='<td>'.$vR_RowIns['puntos'].'</td><td>'.$vR_RowIns['comentarios'].'</td>';
							$vc_Grid.='<td>'.$vR_RowIns['fch_ins'].'</td><td>'.$vR_RowIns['fch_upd'];
							$vc_Grid.='</td></tr>';
						}
						$vc_Html.=$vc_Grid.'</tr>';

						$vc_Html.='</table>';
						$vc_Html.='</div>';

						printf("%s\n",$vc_Html);
						
					} // End Post
?>
			</form>
		</div>
	</body>
</html>
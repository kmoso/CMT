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
					if (!isset($_POST['ddl_cat_id'])) {
						$vn_Cat_Id=0;
					}
					else {
						$vn_Cat_Id=intval($_POST['ddl_cat_id']);
					}

					$vc_Html = '';
					$vc_Html.='<table class="seleccion">';
					$vc_Html.='<tr class="encabezado"><td colspan="2">Mantenimiento de categor&iacute;as</td></tr>';

					// LOV Categorías
					$vc_Sql = "select cat_id as id, cat_nombre as descr ";
					$vc_Sql.="from cmt_cat_categorias ";
					$vc_Sql.="where 1 = 1 ";
					$vc_Sql.="order by case cat_id when ".$vn_Cat_Id." then 0 else 1 end, cat_orden";
					$vr_ResultSet=mysqli_query($vc_DbConfig,$vc_Sql);
					if (!$vr_ResultSet) {
						$vc_Mensaje = '<font color="yellow">Error: '.mysqli_error($vc_DbConfig).' ejecutando '.$vc_Sql.'<br>Contacta al administrador del sitio<br></font>';
						printf("%s\n",$vc_Mensaje);
						exit(1);
					}
//					else echo $vc_Sql;
					$vR_Row=mysqli_fetch_array($vr_ResultSet,MYSQLI_ASSOC);
					$vc_Ddl_Categorias = '<tr><td align="right"><b>Categor&iacute;a:</b></td><td><select id="ddl_cat_id" name="ddl_cat_id">';
					$vc_Ddl_Categorias.='<option value="0">Todas</option>';
					$vc_Ddl_Categorias.='<option value="'.$vR_Row['id'].'">'.$vR_Row['descr'].'</option>';
					while($vR_Row = mysqli_fetch_assoc($vr_ResultSet))
					{
					  $vc_Ddl_Categorias.='<option value="'.$vR_Row['id'].'">'.$vR_Row['descr'].'</option>';
					}
					$vc_Ddl_Categorias.='</select>';
					$vc_Html.=$vc_Ddl_Categorias.'</td></tr>';
					$vc_Html.='<tr><td></td><td><input type="submit" value="Consultar"><br><br></td></tr>';
					
					$vc_Html.='</table>';

					printf("%s\n",$vc_Html);

					if($_SERVER['REQUEST_METHOD']=='POST') {
						// Asignando valores
						$vn_Cat_Id=intval($_POST['ddl_cat_id']);
						
						$vc_Html = '';
						$vc_Html.='<div class="main_grid">';
						$vc_Html.='<table class="grid">';
						$vc_Html.='<tr class="grid0">';
						$vc_Html.='<td><b>#</b></td>';
						$vc_Html.='<td><b>Nombre</b></td>';
						$vc_Html.='<td><b>Orden</b></td>';
						$vc_Html.='<td><b>Activo</b></td>';
						$vc_Html.='<td><b>Puntos Iniciales</b></td>';
						$vc_Html.='<td><b>Puntaje M&aacute;ximo</b></td>';
						$vc_Html.='<td><b>Fch Ins</b></td>';
						$vc_Html.='<td><b>Fch Upd</b></td></tr>';

						// Información de Categorías
						$vc_Sql ="select cat_id as id, cat_nombre as nombre, cat_orden as orden, ";
						$vc_Sql.="cat_activo as activo, cat_puntos_iniciales as pts_ini, cat_puntos_max as pts_max, ";
						$vc_Sql.="cat_fch_ins as fch_ins, cat_fch_upd as fch_upd ";
						$vc_Sql.="from cmt_cat_categorias ";
						$vc_Sql.="where 1 = 1 ";
						if ( 0 < $vn_Cat_Id ) {
							$vc_Sql.="and cat_id = ".$vn_Cat_Id." ";
						}
						$vc_Sql.="order by cat_puntos_iniciales desc, cat_orden, cat_nombre ";
//						echo $vc_Sql;
						$vn_i=1;
						$vr_ResultSet=mysqli_query($vc_DbConfig,$vc_Sql);
						if (!$vr_ResultSet) {
							$vc_Mensaje = '<font color="yellow">Error: '.mysqli_error($vc_DbConfig).' ejecutando '.$vc_Sql.'<br>Contacta al administrador del sitio<br></font>';
							printf("%s\n",$vc_Mensaje);
							exit(1);
						}
	//					else echo $vc_Sql;
// Agrega registro para insertar nueva categoría
						$vc_Html.='<tr class="grid0"><td><a href="upd_cat.php?cat_id=-1">0</a></td>';
						$vc_Html.='<td>Por definir</td><td>Por definir</td>';
						$vc_Html.='<td>Por definir</td><td>Por definir</td>';
						$vc_Html.='<td>Por definir</td><td>Por definir</td>';
						$vc_Html.='<td>Por definir</td></tr>';

						$vR_RowIns=mysqli_fetch_array($vr_ResultSet,MYSQLI_ASSOC);
						$vc_Grid ='<tr class="grid'.$vn_i.'"><td><a href="upd_cat.php?cat_id='.$vR_RowIns['id'].'">'.$vR_RowIns['id'].'</a></td>';
						$vc_Grid.='<td>'.$vR_RowIns['nombre'].'</td>';
						$vc_Grid.='<td>'.$vR_RowIns['orden'].'</td>';
						$vc_Grid.='<td>'.$vR_RowIns['activo'].'</td>';
						$vc_Grid.='<td>'.$vR_RowIns['pts_ini'].'</td>';
						$vc_Grid.='<td>'.$vR_RowIns['pts_max'].'</td>';
						$vc_Grid.='<td>'.$vR_RowIns['fch_ins'].'</td>';
						$vc_Grid.='<td>'.$vR_RowIns['fch_upd'];
						$vc_Grid.='</td></tr>';
						while($vR_RowIns = mysqli_fetch_assoc($vr_ResultSet))
						{
							if ( $vn_i == 1 ) $vn_i = 0;
							else $vn_i = 1;
								$vc_Grid.='<tr class="grid'.$vn_i.'"><td><a href="upd_cat.php?cat_id='.$vR_RowIns['id'].'">'.$vR_RowIns['id'].'</a></td>';
								$vc_Grid.='<td>'.$vR_RowIns['nombre'].'</td>';
								$vc_Grid.='<td>'.$vR_RowIns['orden'].'</td>';
								$vc_Grid.='<td>'.$vR_RowIns['activo'].'</td>';
								$vc_Grid.='<td>'.$vR_RowIns['pts_ini'].'</td>';
								$vc_Grid.='<td>'.$vR_RowIns['pts_max'].'</td>';
								$vc_Grid.='<td>'.$vR_RowIns['fch_ins'].'</td>';
								$vc_Grid.='<td>'.$vR_RowIns['fch_upd'];
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
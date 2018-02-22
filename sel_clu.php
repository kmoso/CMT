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

					$vc_Html = '';
					$vc_Html.='<table class="seleccion">';
					$vc_Html.='<tr class="encabezado"><td colspan="2">Mantenimiento de clubes</td></tr>';

					// LOV Clubes
					$vc_Sql = "select clu_id as id, clu_nombre as descr ";
					$vc_Sql.="from cmt_clu_clubes ";
					$vc_Sql.="where 1 = 1 ";
					$vc_Sql.="order by case clu_id when ".$vn_Clu_Id." then 0 else 1 end, clu_orden";
					$vr_ResultSet=mysqli_query($vc_DbConfig,$vc_Sql);
					if (!$vr_ResultSet) {
						$vc_Mensaje = '<font color="yellow">Error: '.mysqli_error($vc_DbConfig).' ejecutando '.$vc_Sql.'<br>Contacta al administrador del sitio<br></font>';
						printf("%s\n",$vc_Mensaje);
						exit(1);
					}
//					else echo $vc_Sql;
					$vR_Row=mysqli_fetch_array($vr_ResultSet,MYSQLI_ASSOC);
					$vc_Ddl_Clubes = '<tr><td align="right"><b>Club:</b></td><td><select id="ddl_clu_id" name="ddl_clu_id">';
					$vc_Ddl_Clubes.='<option value="0">Todos</option>';
					$vc_Ddl_Clubes.='<option value="'.$vR_Row['id'].'">'.$vR_Row['descr'].'</option>';
					while($vR_Row = mysqli_fetch_assoc($vr_ResultSet))
					{
					  $vc_Ddl_Clubes.='<option value="'.$vR_Row['id'].'">'.$vR_Row['descr'].'</option>';
					}
					$vc_Ddl_Clubes.='</select>';
					$vc_Html.=$vc_Ddl_Clubes.'</td></tr>';
					$vc_Html.='<tr><td></td><td><input type="submit" value="Consultar"><br><br></td></tr>';
					
					$vc_Html.='</table>';

					printf("%s\n",$vc_Html);

					if($_SERVER['REQUEST_METHOD']=='POST') {
						// Asignando valores
						$vn_Clu_Id=intval($_POST['ddl_clu_id']);
						
						$vc_Html = '';
						$vc_Html.='<div class="main_grid">';
						$vc_Html.='<table class="grid">';
						$vc_Html.='<tr class="grid0">';
						$vc_Html.='<td><b>#</b></td>';
						$vc_Html.='<td><b>Nombre</b></td>';
						$vc_Html.='<td><b>Orden</b></td>';
						$vc_Html.='<td><b>Activo</b></td>';
						$vc_Html.='<td><b>Fch Ins</b></td>';
						$vc_Html.='<td><b>Fch Upd</b></td></tr>';

						// Información de Clubes
						$vc_Sql ="select clu_id as id, clu_nombre as nombre, clu_orden as orden, ";
						$vc_Sql.="clu_activo as activo, clu_fch_ins as fch_ins, clu_fch_upd as fch_upd ";
						$vc_Sql.="from cmt_clu_clubes ";
						$vc_Sql.="where 1 = 1 ";
						if ( 0 < $vn_Clu_Id ) {
							$vc_Sql.="and clu_id = ".$vn_Clu_Id." ";
						}
						$vc_Sql.="order by clu_orden, clu_nombre ";
//						echo $vc_Sql;
						$vn_i=1;
						$vr_ResultSet=mysqli_query($vc_DbConfig,$vc_Sql);
						if (!$vr_ResultSet) {
							$vc_Mensaje = '<font color="yellow">Error: '.mysqli_error($vc_DbConfig).' ejecutando '.$vc_Sql.'<br>Contacta al administrador del sitio<br></font>';
							printf("%s\n",$vc_Mensaje);
							exit(1);
						}
	//					else echo $vc_Sql;
// Agrega registro para insertar nuevo club
						$vc_Html.='<tr class="grid0"><td><a href="upd_clu.php?clu_id=-1">0</a></td>';
						$vc_Html.='<td>Por definir</td><td>Por definir</td>';
						$vc_Html.='<td>Por definir</td><td>Por definir</td>';
						$vc_Html.='<td>Por definir</td></tr>';
						$vR_RowIns=mysqli_fetch_array($vr_ResultSet,MYSQLI_ASSOC);
						$vc_Grid ='<tr class="grid'.$vn_i.'"><td><a href="upd_clu.php?clu_id='.$vR_RowIns['id'].'">'.$vR_RowIns['id'].'</a></td>';
						$vc_Grid.='<td>'.$vR_RowIns['nombre'].'</td>';
						$vc_Grid.='<td>'.$vR_RowIns['orden'].'</td>';
						$vc_Grid.='<td>'.$vR_RowIns['activo'].'</td><td>'.$vR_RowIns['fch_ins'].'</td>';
						$vc_Grid.='<td>'.$vR_RowIns['fch_upd'];
						$vc_Grid.='</td></tr>';
						while($vR_RowIns = mysqli_fetch_assoc($vr_ResultSet))
						{
							if ( $vn_i == 1 ) $vn_i = 0;
							else $vn_i = 1;
								$vc_Grid.='<tr class="grid'.$vn_i.'"><td><a href="upd_clu.php?clu_id='.$vR_RowIns['id'].'">'.$vR_RowIns['id'].'</a></td>';
								$vc_Grid.='<td>'.$vR_RowIns['nombre'].'</td>';
								$vc_Grid.='<td>'.$vR_RowIns['orden'].'</td>';
								$vc_Grid.='<td>'.$vR_RowIns['activo'].'</td><td>'.$vR_RowIns['fch_ins'].'</td>';
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
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
		<?php include_once("analytics_tracking.php") ?>
		<div id="page">
<?php
			$vc_Html = '';
			$vc_Html.='<br><br><br><br><table class="inscripcion">';
			$vc_Html.='<tr><td align="center">Zona de descargas</td></tr>';
			$vc_Html.='</table>';
			$vc_Html.='<table border="1" class="small_grid">';

			// LOV Clubes
			$vc_Sql = "select doc_id as id, doc_nombre_archivo as archivo, ";
			$vc_Sql.=" doc_nombre_display as descr, doc_orden orden ";
			$vc_Sql.="from cmt_doc_documentos ";
			$vc_Sql.="where 1 = 1 ";
			$vc_Sql.="and doc_activo = 'S' ";
			$vc_Sql.="order by doc_orden, doc_id";
			$vr_ResultSet=mysqli_query($vc_DbConfig,$vc_Sql);
			if (!$vr_ResultSet) {
				$vc_Mensaje = '<font color="yellow">Error: '.mysqli_error($vc_DbConfig).' ejecutando '.$vc_Sql.' Contacta al administrador del sitio<br></font>';
				printf("%s\n",$vc_Mensaje);
				exit(1);
			}
//			else echo $vc_Sql;
			$vR_Row=mysqli_fetch_array($vr_ResultSet,MYSQLI_ASSOC);
			$i=0;
			$vc_Divide = "N";
			$vc_Html.='<tr class="grid'.$i.'">';
			$vc_Html.='<td align="center">';
			$vc_Html.='<b><a href="/docs/';
			$vc_Html.=$vR_Row['archivo'].'" target="_blank">'.$vR_Row['id'].'</a></b></td>';
			$vc_Html.='<td>'.$vR_Row['descr'].'</td></tr>';
			while($vR_Row = mysqli_fetch_assoc($vr_ResultSet))
			{
				if ($i == 0) $i = 1;
				else $i = 0;
				
				if ( $vR_Row['orden'] >= 50 && $vc_Divide == "N" ) $vc_Divide = "S";
				
				if ( $vc_Divide == "S" ) {
					$vc_Html.='<tr><td><b>===</b></td><td><b>=============================================</b></td></tr>';
					$vc_Divide = "X";
				}					
				$vc_Html.='<tr class="grid'.$i.'"><td align="center"><b><a href="/docs/';
				$vc_Html.=$vR_Row['archivo'].'" target="_blank">'.$vR_Row['id'].'</a></b></td>';
				$vc_Html.='<td>'.$vR_Row['descr'].'</td></tr>';
			}
			$vc_Html.='</table>';

			printf("%s\n",$vc_Html);
?>
		</div>
	</body>
</html>
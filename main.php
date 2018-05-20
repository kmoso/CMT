<!--
< ?php
	header('Location: http://www.cmt.com.mx/error.html');
    header('Location: http://www.cmt.com.mx/JoomlaVersion/');
	exit();
?>
 -->
<!DOCTYPE HTML>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Circuito Mexicano de Tenistas</title>
		<link rel="stylesheet" type="text/css" media="screen" href="screen.css"
	</head>
	<body>
		<div id="page">
			<header>
				<a class="logo" title="CMT" href="https://www.facebook.com/groups/845170825533468/"><span>CMT</span></a>
			</header>
			<section class="main">
				<aside>
					<div class="content inscripciones">
						<h3><a href="val_email.php">Inscr&iacute;bete!</a></h3>
<!--						<p><strongCostos:<br>B $400<br>C/D $400<br>D femenil/D 14 y menores $350<br>Novatos E (perdedores 1ra. ronda en D) $250<br>Incluye pelotas</p> -->

<?php
	include("dbconfig.php");
	// Código para obtener el costo default por torneo
	$vc_Sql ="select par_valor ";
	$vc_Sql.="from cmt_par_parametros ";
	$vc_Sql.="where par_nombre = 'COSTO_DFLT_TORNEO' ";
	$vc_Sql.="and par_orden = 1 ";
	
	$vr_ResultSet=mysqli_query($vc_DbConfig,$vc_Sql);
	if (!$vr_ResultSet) {
		$vc_Mensaje = '<font color="yellow">Error: '.mysqli_error($vc_DbConfig).' ejecutando '.$vc_Sql.'<br>Contacta al administrador del sitio<br></font>';
		printf("%s\n",$vc_Mensaje);
		exit(1);
	}
//			else echo $vc_Sql;

	while($vR_SelCosto = mysqli_fetch_assoc($vr_ResultSet))
	{
		$vn_CostoDflt = $vR_SelCosto['par_valor'];
	}
	$vc_Html = '';
	$vc_Html.='<p><strongCostos:<br>$'.$vn_CostoDflt.' todas las categor&iacute;as</p>';
	printf("%s\n",$vc_Html);
?>
<!--						<p><strongCostos:<br>$390 todas las categor&iacute;as</p> -->
					</div>
				</aside>
				<aside>
					<div class="content avisos">
						<h3><a href="docs/SiguienteEtapa.pdf">Avisos</a></h3>
						<p>Informaci&oacute;n que no puedes dejar pasar</p>
					</div>
				</aside>
				<aside>
					<div class="content descargas">
						<h3><a href="downloads.php">Descargas</a></h3>
						<p>Zona de descarga: Listas preliminares de torneos, convocatorias, horarios, cuadros y m&aacute;s</p>
					</div>
				</aside>
			</section>
			<nav></nav>
			<footer>
				&copy; CMT
				<div class="content">
					<a title="Qui&eacute;nes somos?" href="docs/QuienesSomosCMT.pdf">Qui&eacute;nes somos?</a>
					<a title="Entrena con nosotros" href="https://www.facebook.com/AcademiaCMT/">Entrena con nosotros</a>
					<a title="Reglamento CMT" href="docs/ReglamentoCMT.pdf">Reglamento CMT</a>
					<a title="Calendario CMT" href="docs/Calendario_CMT.pdf">Calendario CMT</a>
					<a title="CMT Team" href="login.php">CMT Team</a>
				</div>
			</footer>
		</div>	
	</body>
</html>

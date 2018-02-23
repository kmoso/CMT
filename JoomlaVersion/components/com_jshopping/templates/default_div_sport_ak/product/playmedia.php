<?php defined( '_JEXEC' ) or die(); ?>
<html>
	<head>
		<title><?php echo $this->description; ?></title>
		<?php print $this->scripts_load?>
	</head>
	<body style = "padding: 0px; margin: 0px;">
		<a class = "video_full" id = "video" href = "<?php echo $this->config->demo_product_live_path.'/'.$this->filename; ?>"></a>
		<script type="text/javascript">
            var liveurl = '<?php echo JURI::root()?>';
			jQuery('#video').media( { width: <?php echo $this->config->video_product_width; ?>, height: <?php echo $this->config->video_product_height; ?>, autoplay: 1} );
		</script>
	</body>
</html>

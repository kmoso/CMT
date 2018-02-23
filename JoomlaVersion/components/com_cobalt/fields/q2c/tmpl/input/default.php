<?php
/**
 * Cobalt by MintJoomla
 * a component for Joomla! 1.7 - 2.5 CMS (http://www.joomla.org)
 * Author Website: http://www.mintjoomla.com/
 * @copyright Copyright (C) 2012 MintJoomla (http://www.mintjoomla.com). All rights reserved.
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die();
echo $this->data;
?>

<script>
	(function($){
		var title = $('#jform_title').val();

		$('#qtcEnableQtcProd').change(function(){
			console.log($('#item_name').val() == '');
			if($('#item_name').val() == '') {
				$('#item_name').val(title);
			}
		});
	}(jQuery));
</script>

<?php
/**
 * Cobalt by MintJoomla
 * a component for Joomla! 1.7 - 2.5 CMS (http://www.joomla.org)
 * Author Website: http://www.mintjoomla.com/
 * @copyright Copyright (C) 2012 MintJoomla (http://www.mintjoomla.com). All rights reserved.
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die();
$default = $this->default;
$hidden = array();
?>

<ul class="nav nav-pills" id="flt-<?php echo $this->key;?>-list">
	<?php foreach($this->values as $key => $value): 
		if (!$value->field_value)
			continue;
		$label = $this->_getVal($value->field_value);
		?>
		<li <?php if(in_array($value->field_value, $default) ) echo 'class="active"';?> id="flt-<?php echo $this->id;?>-<?php echo $key;?>" onclick="Cobalt.setHiddenSelectableFlt<?php echo $this->id;?>(this.id, '<?php echo addslashes(htmlspecialchars($value->field_value));?>')">
		<a href="javascript:void(0);">
			<?php echo $label;?>
			<span class="badge"><?php echo ($this->params->get('params.filter_show_number', 1) ? $value->num : NULL);?></span>
		</a>	
		<?php if (in_array($value->field_value, $default)) : ?>
			<input type="hidden" name="filters[<?php echo $this->key;?>][value][]" value="<?php echo htmlspecialchars($value->field_value);?>" id="flt-<?php echo $this->id;?>-<?php echo $key;?>-hid">
		<?php endif;?>
		</li>
	<?php endforeach;?>
</ul>

<script type="text/javascript">
!function($)
{
	Cobalt.setHiddenSelectableFlt<?php echo $this->id;?> = function(id, value)
	{
		var hid=$("#"+id + "-hid");
		if(hid.length > 0)
		{
			$("#"+id + "-hid").remove();
			$("#"+id).removeClass('active');
		}
		else
		{
			var newhid = $(document.createElement("input")).attr({
				 type: "hidden", 
				 value: value, 
				 id: id+"-hid", 
				 name: "filters[<?php echo $this->key;?>][value][]"
				});
			$("#flt-<?php echo $this->key;?>-list").append(newhid);
			$("#"+id).addClass('active');
		}
	}
}(jQuery);
</script>

<?php
/**
 * Cobalt by MintJoomla
 * a component for Joomla! 1.7 - 2.5 CMS (http://www.joomla.org)
 * Author Website: http://www.mintjoomla.com/
 * @copyright Copyright (C) 2012 MintJoomla (http://www.mintjoomla.com). All rights reserved.
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die();

if($this->params->get('params.chosen', false))
{
	JHtml::_('formbehavior.chosen', '.cobalt-chosen-'.$this->id);
}

$class = ' class="' . $this->params->get('core.field_class', 'inputbox') . ($this->required ? ' required' : NULL) . '"';
$required = $this->required ? ' required="true" ' : NULL;
$style = ' style="max-width: ' . $this->params->get('params.width', '450') . 'px"';
?>

<select name="jform[fields][<?php echo $this->id;?>]" class="elements-list cobalt-chosen-<?php echo $this->id; ?>" id="form_field_list_<?php echo $this->id;?>" <?php echo $required . $style;?>>
	<option value=""><?php echo JText::_($this->params->get('params.'.($this->params->get('params.sql_source') ? "sql_" : null).'label', 'S_CHOOSEVALUE'));?></option>
<?php
$selected = ($this->value ? $this->value : $this->params->get('params.selected'));

if(!is_array($this->value)) settype($this->value, 'array');

foreach($this->values as $key => $line):
	$atr = '';
	if (is_string($line))
		$val = explode($this->params->get('params.color_separator', "^"), $line);
	if (isset($val[1]))
	{
		$atr .= ' style="color:' . $val[1] . '"';
	}

	$v = is_string($line) ? $line : $line->id;
	if ($this->value && in_array($v, $this->value))
	{
		$atr .= ' selected="selected"';
	}
	if($this->params->get('params.sql_source'))
	{
		if($this->value && array_key_exists($line->id, $this->value))
		{
			$atr .= ' selected="selected"';
		}

		$value = $line->id;
		$text = $line->text;
	}
	else
	{
		$value = htmlspecialchars($line, ENT_COMPAT, 'UTF-8');
		$text = JText::_($val[0]);
	}

	?>
	<option value="<?php echo $value;?>" <?php echo $atr;?>><?php echo JText::_($text);?></option>

<?php endforeach; ?>
</select>

<?php if (in_array($this->params->get('params.add_value', 2), $this->user->getAuthorisedViewLevels()) && !$this->params->get('params.sql_source')):?>
	<div class="clearfix"></div>
	<p>
	<div id="variant_<?php echo $this->id;?>">
		<a id="show_variant_link_<?php echo $this->id;?>"
			rel="{field_type:'<?php echo $this->type;?>', id:<?php echo $this->id;?>, inputtype:'option', limit:1}"
			href="javascript:void(0)" onclick="Cobalt.showAddForm(<?php echo $this->id;?>)"><?php echo JText::_($this->params->get('params.user_value_label', 'Your variant'));?></a>
	</div></p>
<?php endif;?>
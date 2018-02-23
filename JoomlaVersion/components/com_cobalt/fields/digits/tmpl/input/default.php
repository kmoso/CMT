<?php
/**
 * Cobalt by MintJoomla
 * a component for Joomla! 1.7 - 2.5 CMS (http://www.joomla.org)
 * Author Website: http://www.mintjoomla.com/
 * @copyright Copyright (C) 2012 MintJoomla (http://www.mintjoomla.com). All rights reserved.
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die();

$class[] = 'inputbox';
$class[] = $this->params->get('core.field_class');
$required = NULL;
if($this->required)
{
	$class[] = 'required';
	$required = ' required="true" ';
}

$class	= ' class="'.implode(' ', $class).'" ';
$required = $required;

$prep = $this->params->get('params.prepend', NULL);
$app = $this->params->get('params.append', NULL)
?>
<div class="<?php if($prep) {echo ' input-prepend';} if($app) {echo ' input-append';}?>">
	<?php if($prep):?>
		<span class="add-on"><?php echo $prep;?></span>
	<?php endif; ?>
	<input type="text" name="jform[fields][<?php echo $this->id;?>]" id="field_<?php echo $this->id;?>" value="<?php echo $this->value;?>"
		size="<?php echo $this->params->get('params.field_size', 10);?>"
		onKeyUp="Cobalt.fieldErrorClear(<?php echo $this->id; ?>); Cobalt.formatFloat(this, <?php echo $this->params->get('params.decimals_num', 0);?>, <?php echo $this->params->get('params.max_num', false);?>, <?php echo $this->params->get('params.val_max', false);?>,	<?php echo $this->params->get('params.val_min', 0);?>, <?php echo $this->id;?>, '<?php echo JText::sprintf('D_MINMAX_ERROR', $this->label, $this->params->get('params.val_min', 0), $this->params->get('params.val_max', 0), array('jsSafe' => true));?>');"
		<?php echo $class.$required;?> />
	<?php if($app):?>
		<span class="add-on"><?php echo $app;?></span>
	<?php endif; ?>
</div>
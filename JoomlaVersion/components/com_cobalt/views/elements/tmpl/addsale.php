<?php
/**
 * Cobalt by MintJoomla
 * a component for Joomla! 1.7 - 2.5 CMS (http://www.joomla.org)
 * Author Website: http://www.mintjoomla.com/
 * @copyright Copyright (C) 2012 MintJoomla (http://www.mintjoomla.com). All rights reserved.
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die();
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.modal');

?>
<style>
<!--
#jform_record_id{ width: 50px;}
#producttitle { width: 150px;}
-->
</style>

<div class="page-header"><h1><?php echo JText::_('CADDSALE')?></h1></div>
<form action="<?php echo JRoute::_('index.php');?>" method="post" name="adminForm" id="adminForm" class="form-horizontal">
	<div class="row-fluid">
		<div class="control-group">
			<div class="control-label span2"><?php echo $this->form->getLabel('gateway_id'); ?></div>
			<div class="controls"><?php echo $this->form->getInput('gateway_id'); ?></div>
		</div>

	<div class="control-group">
		<div class="control-label span2"><?php echo $this->form->getLabel('user_id'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('user_id'); ?>
		<img alt="loading..." id="user-name-check" align="absmiddle" src="<?php echo JURI::root(TRUE);?>/components/com_cobalt/images/load.gif" style="display: none;">
		</div>
	</div>
	<div class="control-group">
		<div class="control-label span2"><?php echo $this->form->getLabel('record_id'); ?></div>
		<div class="controls">
			<div class="input-append">
				<?php echo $this->form->getInput('record_id'); ?>
				<span class="add-on" id="producttitle"><?php if($this->item->record_id) echo $this->item->record_id;?></span>
				<a class="btn btn-primary" onclick="SqueezeBox.fromElement(this, {handler:'iframe', size: {x: 700, y: 500}, url:'<?php echo JRoute::_('index.php?option=com_cobalt&view=elements&layout=products&tmpl=component', false);?>'})">
					<i class="icon-list icon-white"></i>
					<?php echo JText::_('CSELECT');?>
				</a>
			</div>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label span2"><?php echo $this->form->getLabel('amount'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('amount'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label span2"><?php echo $this->form->getLabel('status'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('status'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label span2"><?php echo $this->form->getLabel('comment') ; ?></div>
		<div class="controls"><?php echo $this->form->getInput('comment') ; ?></div>
	</div>
</div>

<div class="form-actions">
	<button type="button" class="btn" onclick="Joomla.submitbutton('sale.save')">
		<?php echo HTMLFormatHelper::icon('disk.png');  ?>
		<?php echo JText::_('CSAVE');?>
	</button>

	<button type="button" class="btn" onclick="Joomla.submitbutton('sale.cancel')">
		<?php echo HTMLFormatHelper::icon('cross.png');  ?>
		<?php echo JText::_('CCANCEL');?>
	</button>
</div>

<script type="text/javascript">
$('jform_user_id').addEvent('blur', function(){
	current_value = $('jform_user_id').value;
	if(!current_value)
	{
		return;
	}
	$('user-name-check').setStyle('display', 'inline');

	new Request.JSON({
		url: '<?php echo JRoute::_('index.php?option=com_cobalt&task=ajax.checkuser&tmpl=component', FALSE) ?>',
		method:'post',
		data:{
			user:current_value
		},
		onComplete: function(json) {
			if(!json)
			{
				return;
			}
			if(!json.success)
			{
				alert(json.error);
				$('user-name-check').setStyle('display', 'none');
				$('jform_user_id').addClass('user-alert');
				return;
			}
			$('user-name-check').set('src', '<?php echo JURI::root(TRUE);?>/media/mint/icons/16/tick.png');
			$('jform_user_id').removeClass('user-alert');

		}.bind(this)
	}).send();
});

$('jform_user_id').addEvent('focus', function(){
	$('user-name-check').set('src', '<?php echo JURI::root(TRUE);?>/components/com_cobalt/images/load.gif');
	$('user-name-check').setStyle('display', 'none');
});
</script>



<input type="hidden" name="task" value="" />
<input type="hidden" name="option" value="com_cobalt" />
<?php echo JHtml::_( 'form.token' ); ?>

</form>

<?php
/**
 * Cobalt by MintJoomla
 * a component for Joomla! 1.7 - 2.5 CMS (http://www.joomla.org)
 * Author Website: http://www.mintjoomla.com/
 * @copyright Copyright (C) 2012 MintJoomla (http://www.mintjoomla.com). All rights reserved.
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */

defined('_JEXEC') or die;

// Include the component HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');

// Load the tooltip behavior.
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');
?>
<style type="text/css">

.pane-sliders {
	margin-top: 0px !important;
}

div.panel fieldset {
	border: 1px solid #CCCCCC !important;
}
</style>
<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'category.cancel' || document.formvalidator.isValid(document.id('item-form'))) {
			<?php echo $this->form->getField('description')->save(); ?>
			Joomla.submitform(task, document.getElementById('item-form'));
		} else {
			alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
		}
	}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_cobalt&view=category&section_id='.JRequest::getInt('section_id').'&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="item-form" class="form-validate form-horizontal">
	<fieldset>
		<legend><?php echo empty($this->item->id) ? JText::_('CNEWCATEGORY') : JText::sprintf('CEDITCATEGORYS', $this->item->title); ?></legend>
		<ul class="nav nav-tabs">
			<li class="active"><a href="#details" data-toggle="tab"><?php echo JText::_('COM_COBALT_FIELDSET_DETAILS');?></a></li>
			<li><a href="#options" data-toggle="tab"><?php echo JText::_('COM_COBALT_FIELDSET_OPTIONS');?></a></li>
			<li><a href="#relative" data-toggle="tab"><?php echo JText::_('CRELATIVECAT');?></a></li>
			<li><a href="#metadata" data-toggle="tab"><?php echo JText::_('JGLOBAL_FIELDSET_METADATA_OPTIONS');?></a></li>
			<?php if ($this->canDo->get('core.admin')): ?>
				<li><a href="#permissions" data-toggle="tab"><?php echo JText::_('COM_CATEGORIES_FIELDSET_RULES');?></a></li>
			<?php endif; ?>
		</ul>
		<div class="tab-content">
			<div class="tab-pane active" id="details">
			<div class="span6">
				<div class="control-group">
					<div class="control-label">
						<?php echo $this->form->getLabel('title'); ?>
					</div>
					<div class="controls">
						<?php echo $this->form->getInput('title'); ?>
					</div>
				</div>
				<div class="control-group">
					<div class="control-label">
						<?php echo $this->form->getLabel('alias'); ?>
					</div>
					<div class="controls">
						<?php echo $this->form->getInput('alias'); ?>
					</div>
				</div>
				<div class="control-group">
					<div class="control-label">
						<?php echo $this->form->getLabel('parent_id'); ?>
					</div>
					<div class="controls">
						<?php echo $this->form->getInput('parent_id'); ?>
					</div>
				</div>
				<legend><?php echo $this->form->getLabel('description'); ?></legend>
				<?php echo $this->form->getInput('description'); ?>
			</div>
			<div class="span5">
				<?php echo MEFormHelper::renderGroups($this->form, $this->params_groups, $this->item->params, FORM_SEPARATOR_H2, FORM_STYLE_TABLE);	?>
			</div>
			</div>
			<div class="tab-pane" id="options">
				<div class="control-group">
					<div class="control-label">
						<?php echo $this->form->getLabel('published'); ?>
					</div>
					<div class="controls">
						<?php echo $this->form->getInput('published'); ?>
					</div>
				</div>
				<div class="control-group">
					<div class="control-label">
						<?php echo $this->form->getLabel('access'); ?>
					</div>
					<div class="controls">
						<?php echo $this->form->getInput('access'); ?>
					</div>
				</div>
				<div class="control-group">
					<div class="control-label">
						<?php echo $this->form->getLabel('language'); ?>
					</div>
					<div class="controls">
						<?php echo $this->form->getInput('language'); ?>
					</div>
				</div>
				<div class="control-group">
					<div class="control-label">
						<?php echo $this->form->getLabel('id'); ?>
					</div>
					<div class="controls">
						<?php echo $this->form->getInput('id'); ?>
					</div>
				</div>
				<?php echo $this->loadTemplate('options'); ?>
			</div>
			<div class="tab-pane" id="relative">
				<?php echo JHtml::_('mrelements.catselector', 'jform[relative_cats][]', $this->item->section_id, $this->item->relative_cats_ids, 0); ?>
			</div>
			<div class="tab-pane" id="metadata">
				<?php echo $this->loadTemplate('metadata'); ?>
			</div>
			<?php if ($this->canDo->get('core.admin')): ?>
				<div class="tab-pane" id="permissions">
					<?php echo $this->form->getInput('rules'); ?>
				</div>
			<?php endif; ?>
		</div>
	</fieldset>


	<div>
		<?php echo $this->form->getInput('section_id'); ?>
		<input type="hidden" name="task" value="" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>

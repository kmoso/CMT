<?php
/**
 * Cobalt by MintJoomla
 * a component for Joomla! 1.7 - 2.5 CMS (http://www.joomla.org)
 * Author Website: http://www.mintjoomla.com/
 * @copyright Copyright (C) 2012 MintJoomla (http://www.mintjoomla.com). All rights reserved.
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */

defined('_JEXEC') or die('Restricted access'); ?>
<?php
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');
JHtml::_('behavior.modal');
?>
<form action="#" method="post" name="adminForm" id="adminForm" class="form-horizontal">
	<ul class="nav nav-tabs">
		<li class="active">
			<a href="#page-main" data-toggle="tab">
				<?php echo JText::_('FS_FORM')?></a></li>
		<li><a href="#page-params" data-toggle="tab"><?php echo JText::_('FS_GENERAL')?></a></li>
		<li><a href="#page-personalize" data-toggle="tab"><?php echo JText::_('FS_PERSPARAMS')?></a></li>
		<li><a href="#page-events" data-toggle="tab"><?php echo JText::_('FS_EVENTPARAMS')?></a></li>
		<!--<li><a href="#page-permissions" data-toggle="tab"><?php echo JText::_('FS_PERMISSIONS')?></a></li>-->
	</ul>
	<div class="tab-content">
		<div class="tab-pane active" id="page-main">
			<div class="pull-left" style="max-width: 500px; min-width:600px; margin-right: 20px;">
				<legend><?php echo empty($this->item->id) ? JText::_('CNEWSECTION') : JText::sprintf('CEDITSECTIONS', $this->item->name); ?></legend>
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('id'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('id'); ?></div>
				</div>
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('name'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('name'); ?></div>
				</div>
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('title'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('title'); ?></div>
				</div>
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('alias'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('alias'); ?></div>
				</div>
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('published'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('published'); ?></div>
				</div>
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('access'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('access'); ?></div>
				</div>
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('language'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('language'); ?></div>
				</div>
				<div><?php echo $this->form->getInput('description'); ?></div>

			</div>
			<div class="pull-left" style="max-width: 500px">
				<?php echo MEFormHelper::renderFieldset($this->params_form, 'general', $this->item->params, 'general', FORM_STYLE_TABLE, 1); ?>
			</div>
		</div>

		<div class="tab-pane" id="page-params">
			<div class="pull-left" style="max-width: 600px; margin-right: 20px;">
				<?php echo MEFormHelper::renderFieldset($this->params_form, 'general2', $this->item->params, 'general', FORM_STYLE_TABLE, 1); ?>
				<?php echo MEFormHelper::renderFieldset($this->params_form, 'submission', $this->item->params, 'general', FORM_STYLE_TABLE, 1); ?>
				<?php echo MEFormHelper::renderFieldset($this->params_form, 'general_tmpl', $this->item->params, 'general', FORM_STYLE_TABLE, 1); ?>
			</div>
			<div class="pull-left" style="max-width: 500px;">
				<?php echo MEFormHelper::renderFieldset($this->params_form, 'search', $this->item->params, 'more', FORM_STYLE_TABLE, 1); ?>
				<?php echo MEFormHelper::renderFieldset($this->params_form, 'general_rss', $this->item->params, 'more', FORM_STYLE_TABLE, 1); ?>
				<?php echo MEFormHelper::renderFieldset($this->params_form, 'metadata', $this->item->params, 'more', FORM_STYLE_TABLE, 1); ?>
			</div>
		</div>

		<div class="tab-pane" id="page-personalize">
			<div class="pull-left" style="max-width: 500px; margin-right: 20px;">
				<?php echo MEFormHelper::renderFieldset($this->params_form, 'persa', $this->item->params, 'personalize', FORM_STYLE_TABLE, 1); ?>
				<?php echo MEFormHelper::renderFieldset($this->params_form, 'vip', $this->item->params, 'personalize', FORM_STYLE_TABLE, 1); ?>
			</div>
			<div class="pull-left" style="max-width: 500px;">
				<?php echo MEFormHelper::renderFieldset($this->params_form, 'categories-private-sub', $this->item->params, 'personalize', FORM_STYLE_TABLE, 1); ?>
				<?php echo MEFormHelper::renderFieldset($this->params_form, 'user-section-set', $this->item->params, 'personalize', FORM_STYLE_TABLE, 1); ?>
			</div>
		</div>

		<div class="tab-pane" id="page-events">
			<div class="pull-left" style="max-width: 500px; margin-right: 20px;">
				<?php echo MEFormHelper::renderFieldset($this->params_form, 'generalevents', $this->item->params, 'events', FORM_STYLE_TABLE, 1); ?>
			</div>
			<div class="pull-left" style="max-width: 500px;">
				<?php echo MEFormHelper::renderFieldset($this->params_form, 'generalevents2', $this->item->params, 'events', FORM_STYLE_TABLE, 1); ?>
			</div>
			<div class="clearfix"></div>
			<div style="max-width: 1000px;">
				<?php echo MEFormHelper::renderFieldset($this->params_form, 'cobevents', $this->item->params, 'events', FORM_STYLE_TABLE, 1); ?>
			</div>
		</div>

		<!-- <div class="tab-pane" id="page-permissions">
			<?php echo $this->form->getInput('rules'); ?>
		</div> -->
	</div>

	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>
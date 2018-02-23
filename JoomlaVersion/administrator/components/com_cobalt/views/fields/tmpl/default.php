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

$user = JFactory::getUser();
$userId = $user->get('id');

$listOrder = $this->state->get('list.ordering');
$listDirn = $this->state->get('list.direction');
$canOrder = $user->authorise('core.edit.state', 'com_cobalt.fields');
$saveOrder = $listOrder == 'f.ordering';

if ($saveOrder)
{
	$saveOrderingUrl = 'index.php?option=com_cobalt&task=fields.ordersave&tmpl=component';
	JHtml::_('sortablelist.sortable', 'fieldsList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}
?>
<form action="<?php echo JRoute::_('index.php?option=com_cobalt&view=fields');?>" method="post" name="adminForm" id="adminForm">
	<div id="filter-bar" class="btn-toolbar">
		<div class="input-append pull-left">
			<input type="text" placeholder="<?php echo JText::_('CFILTER_SEARCH_TYPESDESC'); ?>" style="margin-left: 5px;" name="filter_search" id="filter_search" value="<?php echo $this->state->get('fields.search'); ?>" />

			<button rel="tooltip" class="btn" type="submit" data-original-title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>"><i class="icon-search"></i></button>
			<button rel="tooltip" class="btn" type="button" onclick="document.id('filter_search').value='';this.form.submit();" data-original-title="<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>"><i class="icon-remove"></i></button>
		</div>

		<a class="btn" style="margin-left: 5px;" href="<?php echo JRoute::_('index.php?option=com_cobalt&view=groups&type_id='.$this->state->get('fields.type'));?>"> <img alt="<?php echo JText::_('CMANAGEGROUP')?>" src="<?php echo JURI::root(TRUE)?>/media/mint/icons/16/block.png"><?php echo JText::_('CMANAGEGROUP')?></a>

		<div class="btn-group pull-right hidden-phone">
			<label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC');?></label>
			<?php echo $this->pagination->getLimitBox(); ?>
		</div>
		<div class="btn-group pull-right hidden-phone">
			<label for="directionTable" class="element-invisible"><?php echo JText::_('JFIELD_ORDERING_DESC');?></label>
			<select name="directionTable" id="directionTable" class="input-medium" onchange="Cobalt.orderTable('<?php echo $listOrder?>')">
				<option value=""><?php echo JText::_('JFIELD_ORDERING_DESC');?></option>
				<option value="asc" <?php if ($listDirn == 'asc') echo 'selected="selected"'; ?>><?php echo JText::_('JGLOBAL_ORDER_ASCENDING');?></option>
				<option value="desc" <?php if ($listDirn == 'desc') echo 'selected="selected"'; ?>><?php echo JText::_('JGLOBAL_ORDER_DESCENDING');?></option>
			</select>
		</div>
		<div class="btn-group pull-right">
			<label for="sortTable" class="element-invisible"><?php echo JText::_('JGLOBAL_SORT_BY');?></label>
			<select name="sortTable" id="sortTable" class="input-medium" onchange="Cobalt.orderTable('<?php echo $listOrder?>')">
				<option value=""><?php echo JText::_('JGLOBAL_SORT_BY');?></option>
				<?php echo JHtml::_('select.options', $this->getSortFields(), 'value', 'text', $listOrder);?>
			</select>
		</div>
	</div>

	<div class="clearfix"></div>

	<table class="table table-hover" id="fieldsList">
		<thead>
			<tr>
				<th width="1%" class="nowrap center hidden-phone">
					<?php echo JHtml::_('grid.sort', '<i class="icon-menu-2"></i>', 'f.ordering', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING'); ?>
				</th>
				<th width="1%">
					<input type="checkbox" name="checkall-toggle" value="" onclick="Joomla.checkAll(this)" />
				</th>
				<th width="1%">
					<?php echo JHtml::_('grid.sort',  'CTYPE', 'f.field_type', $listDirn, $listOrder); ?>
				</th>
				<th width="1%">
					<?php echo JHtml::_('grid.sort',  'JSTATUS', 'f.published', $listDirn, $listOrder); ?>
				</th>
				<th class="title">
					<?php echo JHtml::_('grid.sort',  'CFIELDLABEL', 'f.label', $listDirn, $listOrder); ?>
				</th>
				<th width="5%" nowrap="nowrap">
					<?php echo JHtml::_('grid.sort',  'CGROUPNAME', 'g.title', $listDirn, $listOrder); ?>
				</th>
				<th width="1%"><span rel="tooltip" data-original-title="<?php echo JText::_('JGLOBAL_FIELD_KEY_LABEL');?>"><?php echo JString::substr(JText::_('JGLOBAL_FIELD_KEY_LABEL'), 0, 1)?></span></th>
				<th width="1%"><span rel="tooltip" data-original-title="<?php echo JText::_('XML_LABEL_F_REQ');?>"><?php echo JString::substr(JText::_('XML_LABEL_F_REQ'), 0, 1)?></span></th>
				<th width="1%"><span rel="tooltip" data-original-title="<?php echo JText::_('XML_LABEL_F_SEARCHABLE');?>"><?php echo JString::substr(JText::_('XML_LABEL_F_SEARCHABLE'), 0, 1)?></span></th>
				<th width="1%"><span rel="tooltip" data-original-title="<?php echo JText::_('INTRO');?>"><?php echo JString::substr(JText::_('XML_LABEL_F_SHOW_INTRO'), 0, 1)?></span></th>
				<th width="1%"><span rel="tooltip" data-original-title="<?php echo JText::_('FULL');?>"><?php echo JString::substr(JText::_('XML_LABEL_F_SHOW_FULL'), 0, 1)?></span></th>
				<th width="1%" class="nowrap">
					<?php echo JHtml::_('grid.sort',  'ID', 'f.id', $listDirn, $listOrder); ?>
				</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="13">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
		<?php

		foreach($this->items as $i => $item)
		:
			$item->max_ordering = 0; //??
			$ordering   = ($listOrder == 'f.ordering');
			$canCreate  = $user->authorise('core.create',     'com_cobalt.type.'.$item->type_id);
			$canEdit    = $user->authorise('core.edit',       'com_cobalt.type.'.$item->type_id);
			$canCheckin = $user->authorise('core.manage',     'com_checkin') || $item->checked_out == $userId || $item->checked_out == 0;
			$canEditOwn = $user->authorise('core.edit.own',   'com_cobalt.field.'.$item->id) && $item->user_id == $userId;
			$canChange  = $user->authorise('core.edit.state', 'com_cobalt.field.'.$item->id) && $canCheckin;

			$params = new JRegistry();
			$params->loadString($item->params);
			?>
			<tr sortable-group-id="<?php echo $item->group_id?>">
				<td class="order nowrap center hidden-phone">
					<?php if ($canChange) :
						$disableClassName = '';
						$disabledLabel	  = '';

						if (!$saveOrder) :
							$disabledLabel    = JText::_('JORDERINGDISABLED');
							$disableClassName = 'inactive tip-top';
						endif; ?>
						<span class="sortable-handler <?php echo $disableClassName?>" title="<?php echo $disabledLabel?>" rel="tooltip">
							<i class="icon-menu"></i>
						</span>
						<input type="text" style="display:none"  name="order[]" size="5" value="<?php echo $item->ordering;?>" class="width-20 text-area-order " />
					<?php else : ?>
						<span class="sortable-handler inactive" >
							<i class="icon-menu"></i>
						</span>
					<?php endif; ?>
				</td>
				<td class="center">
					<?php echo JHtml::_('grid.id', $i, $item->id); ?>
				</td>
				<td nowrap="nowrap">
					<?php
					$icon = JURI::root() . 'components/com_cobalt/fields/';
					if(JFile::exists(JPATH_ROOT . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_cobalt' . DIRECTORY_SEPARATOR . 'fields' . DIRECTORY_SEPARATOR . $item->field_type . DIRECTORY_SEPARATOR . $item->field_type . '.png'))
					{
						$icon .= "{$item->field_type}/{$item->field_type}.png";
					}
					else
					{
						$icon .= "text/text.png";
					}
					echo JHtml::image($icon, $item->field_type, array(
						'align' => 'absmiddle'
					));
					?>
					<small><a href="#" rel="tooltip" data-original-title="<?php echo JText::_('CFILTERFIELDTYPE')?>" onclick="Cobalt.setAndSubmit('filter_field', '<?php echo $item->field_type?>')"><?php echo $item->field_type?></a></small>
				</td>
				<td class="center">
					<?php echo JHtml::_('field.state', $item->published, $i, 'fields.', $canChange);?>
				</td>
				<td>
					<div class="pull-left">
						<?php if ($item->checked_out) : ?>
							<?php echo JHtml::_('jgrid.checkedout', $i, $item->checked_out, $item->checked_out_time, 'fields.', $canCheckin); ?>
						<?php endif; ?>

						<?php if($params->get('core.icon')):?>
							<img alt="Icon" src="<?php echo JURI::root(TRUE)?>/media/mint/icons/16/<?php echo $params->get('core.icon');?>" align="absmiddle">
						<?php endif;?>

						<a href="<?php echo JRoute::_('index.php?option=com_cobalt&task=field.edit&id='.(int) $item->id);?>">
							<?php echo $this->escape($item->label); ?>
						</a>
					</div>
				</td>
				<td nowrap="nowrap">
					<?php if($item->icon):?>
						<?php echo HTMLFormatHelper::icon($item->icon);?>
					<?php endif;?>
					
					<?php echo $item->group_field_title; ?>
				</td>
				<td>
					<img style="max-width: 16px;" rel="tooltip" data-original-title="<?php echo $item->key;?>" src="<?php echo JUri::root(TRUE)?>/media/mint/icons/16/key.png" />
				</td>
				<td><?php echo JHtml::_('field.required', $params->get('core.required', 0), $i, 'fields.', $canChange)?></td>
				<td><?php echo JHtml::_('field.searchable', $params->get('core.searchable', 0), $i, 'fields.', $canChange)?></td>
				<td><?php echo JHtml::_('field.show_intro', $params->get('core.show_intro', 0), $i, 'fields.', $canChange)?></td>
				<td><?php echo JHtml::_('field.show_full', $params->get('core.show_full', 0), $i, 'fields.', $canChange)?></td>
				<td class="center">
					<small><?php echo (int) $item->id; ?></small>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

	<input type="hidden" name="task" value="" />
	<input type="hidden" name="type_id" value="<?php echo $this->type->id;?>" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
	<?php echo JHtml::_('form.token'); ?>
</form>
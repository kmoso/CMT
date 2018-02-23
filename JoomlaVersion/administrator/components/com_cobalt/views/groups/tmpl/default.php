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
$user	= JFactory::getUser();
$userId	= $user->get('id');

$listOrder	= $this->state->get('list.ordering');
$listDirn	= $this->state->get('list.direction');
$canOrder	= true; //$user->authorise('core.edit.state', 'com_cobalt.groups');
$saveOrder	= $listOrder == 'g.ordering';
if ($saveOrder)
{
	$saveOrderingUrl = 'index.php?option=com_cobalt&task=groups.ordersave&tmpl=component';
	JHtml::_('sortablelist.sortable', 'groupsList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}

?>
<a class="btn btn-primary" href="index.php?option=com_cobalt&view=fields&filter_type=<?php echo $this->state->get('groups.type'); ?>">
	<img alt="BACK" src="<?php echo JUri::root(TRUE)?>/media/mint/icons/16/arrow-180.png">
	<?php JText::printf('CBACKTOFIELD', $this->type->name);?>
</a>
<br />
<br />
<form action="<?php echo $this->action ?>" method="post" name="adminForm" id="adminForm">
	<table class="table table-hover" id="groupsList">
		<thead>
			<th width="1%" class="nowrap center hidden-phone">
				<i class="icon-menu-2"></i>
			</th>
			<th width="1%">
				<input type="checkbox" name="checkall-toggle" value="" onclick="Joomla.checkAll(this)" />
			</th>
			<th class="title">
				<?php echo JText::_('CTITLE'); ?>
			</th>
			<th width="1%">
				<?php echo JText::_('ID'); ?>
			</th>
		</thead>
		<tbody>
		<?php foreach ($this->items as $i => $item) :
			$ordering   = ($listOrder == 'g.ordering');
			$canCreate  = $user->authorise('core.create',     'com_cobalt.group.'.$item->id);
			$canEdit    = $user->authorise('core.edit',       'com_cobalt.group.'.$item->id);
			$canCheckin = $user->authorise('core.manage',     'com_checkin') || $item->checked_out == $userId || $item->checked_out == 0;
			$canChange = true;
			?>
			<tr class="row<?php echo $i % 2; ?>">
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
				<td width="1%">
					<?php echo JHtml::_('grid.id', $i, $item->id); ?>
				</td>
				<td >
					<?php if ($item->checked_out) : ?>
						<?php echo JHtml::_('jgrid.checkedout', $i, $item->checked_out, $item->checked_out_time, 'fields.', $canCheckin); ?>
					<?php endif; ?>

					<a href="<?php echo JRoute::_('index.php?option=com_cobalt&task=group.edit&id='.(int) $item->id);?>">
						<?php echo $item->title; ?>
					</a>

				</td>
				<td>
					<?php echo $item->id?>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

	<?php echo $this->pagination->getListFooter(); ?>

	<input type="hidden" name="type_id" value="<?php echo $this->state->get('groups.type');?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
	<?php echo JHtml::_('form.token'); ?>
</form>
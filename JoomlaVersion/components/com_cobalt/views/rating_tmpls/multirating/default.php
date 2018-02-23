<?php
/**
 * Cobalt by MintJoomla
 * a component for Joomla! 1.7 - 2.5 CMS (http://www.joomla.org)
 * Author Website: http://www.mintjoomla.com/
 * @copyright Copyright (C) 2012 MintJoomla (http://www.mintjoomla.com). All rights reserved.
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */

defined('_JEXEC') or die(); ?>
<table class="table-rating table table-hover table-bordered table-condensed">
	<thead>
		<tr>
			<th width="1%" nowrap="nowrap" style="vertical-align: top;"><?php echo JText::_('CTOTALRATING'); ?></th>
			<td width="1%" nowrap="nowrap" valign="top">
				<?php echo RatingHelp::loadRating($type->params->get('properties.tmpl_rating'), round(@$record->votes_result), $record->id, 500, 'Cobalt.ItemRatingCallBack', false, $record->id);?>
				<small id="rating-text-<?php echo $record->id;?>"><?php echo JText::sprintf('CRAINGDATA', $record->votes_result, $record->votes); ?></small>
			</td>
		</tr>
	</thead>
	<tbody>
		<?php foreach($options as $key => $option): ?>
			<?php $parts = explode('::', $option); ?>
			<tr>
				<th width="1%" nowrap="nowrap" style="vertical-align: top;"><?php echo JText::_($parts[0]); ?></th>
				<td width="1%" nowrap="nowrap" valign="top">
					<?php echo RatingHelp::loadRating(isset($parts[1]) ? $parts[1] : $type->params->get('properties.tmpl_rating'),
						round((int)@$result[$key]['sum']), $record->id, $key, 'Cobalt.ItemRatingCallBackMulti',
						self::canRate('record', $record->user_id, $record->id, $type->params->get('properties.rate_access'), $key, $type->params->get('properties.rate_access_author', 0)));?>
				</td>
			</tr>
		<?php endforeach;?>
	</tbody>
</table>
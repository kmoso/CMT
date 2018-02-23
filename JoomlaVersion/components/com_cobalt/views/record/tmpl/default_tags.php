<?php
/**
 * Cobalt by MintJoomla
 * a component for Joomla! 1.7 - 2.5 CMS (http://www.joomla.org)
 * Author Website: http://www.mintjoomla.com/
 * @copyright Copyright (C) 2012 MintJoomla (http://www.mintjoomla.com). All rights reserved.
 * @license   GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */

defined('_JEXEC') or die();
?>

<?php if(in_array($this->type->params->get('properties.item_can_view_tag', 1), $this->user->getAuthorisedViewLevels())) :

	$app         = JFactory::getApplication();
	$attach_only = TRUE;
	if(MECAccess::allowAccessAuthor($this->type, 'properties.item_can_add_tag', $this->item->user_id) || MECAccess::allowUserModerate($this->user, $this->section, 'allow_tags'))
	{
		$attach_only = FALSE;
	}
	?>


	<?php
	if(
		MECAccess::allowAccessAuthor($this->type, 'properties.item_can_add_tag', $this->item->user_id) ||
		MECAccess::allowAccessAuthor($this->type, 'properties.item_can_attach_tag', $this->item->user_id) ||
		MECAccess::allowUserModerate($this->user, $this->section, 'allow_tags')
	):
		?>
		<dl class="dl-horizontal">
			<dt id="tags-dt">
				<?php echo JText::_('CTAGS'); ?> <?php echo HTMLFormatHelper::icon('price-tag.png'); ?>
			</dt>
			<dd id="tags-dd">
				<div id="add-tags-block<?php echo $this->item->id; ?>">
					<?php echo JHtml::_('tags.add_button', $this->item->id, $this->type->params->get('properties.item_tags_max', 25), $attach_only); ?>
				</div>
			</dd>
		</dl>

	<?php else: ?>
		<?php if($this->item->tags): ?>
			<?php
			if(count($this->item->categories) > 0 && $this->section->params->get('general.filter_mode') == 0) {
				$keys = array_keys($this->item->categories);
				$catid = array_shift($keys);
			}
			$tags = JHtml::_('tags.fetch2',
				$this->item->tags,
				$this->item->id,
				$this->section->id,
				$app->input->getInt('cat_id', $catid),
				$this->type->params->get('properties.item_tag_htmltags', 'h1, h2, h3, h4, h5, h6, strong, em, b, i, big'),
				$this->type->params->get('properties.item_tag_relevance', 0),
				$this->type->params->get('properties.item_tag_num', 0),
				$this->type->params->get('properties.item_tags_max', 25)
			);
			?>
			<style>
				.tag_list .tag_list_item {
					float: left;
					margin: 0;
					margin-right: 10px;
					font-size: 15px;
					font-weight: 300;
					padding: 9px;
				}

				.tag_list .tag_list_item.label {
					background-color: darkgray;
				}

				.tag_list .tag_list_item.label a {
					color: white;
				}

			</style>
			<div id="tag-list-<?php echo $this->item->id ?>" class="tag_list">
				<span class="tag_list_item"><?php echo JText::_('CTAGS'); ?> <?php echo HTMLFormatHelper::icon('price-tag.png'); ?></span>
				<?php foreach($tags AS $tag): ?>
					<span class="label label-default tag_list_item"><a href="<?php echo $tag['link'] ?>" <?php echo $tag['attr'] ?>><?php echo $tag['tag'] ?></a></span>
				<?php endforeach; ?>
			</div>
			<div class="clearfix"></div>
			<br>
		<?php endif; ?>
	<?php  endif; ?>
<?php endif; ?>


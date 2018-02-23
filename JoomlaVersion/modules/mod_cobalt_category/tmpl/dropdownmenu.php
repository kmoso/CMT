<?php
/**
 * Cobalt by MintJoomla
 * a component for Joomla! 1.7 - 2.5 CMS (http://www.joomla.org)
 * Author Website: http://www.mintjoomla.com/
 * @copyright Copyright (C) 2012 MintJoomla (http://www.mintjoomla.com). All rights reserved.
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
 defined('_JEXEC') or die('Restricted access');

if(!function_exists('mod_getChildsDrop')) { function mod_getChildsDrop($category, $params, $k = 1) {
	$category->records = modCobaltCategoriesHelper::getCatRecords($category->id, $params);?>
	<?php if(count($category->children) || count($category->records)) :?>
		<ul class="dropdown-menu">
			<?php foreach($category->children as $i => $cat ) :
				if (!$params->get('tmpl_params.subcat_empty', 1) && !$cat->num_current && !$cat->num_all) continue;  ?>
			<li class="dropdown<?php if($cat->childs_num) echo '-submenu';?><?php /*if(JRequest::getInt('cat_id') == $cat->id) echo ' open';*/ ?>">
				<?php if($params->get('tmpl_params.subcat_limit', 5) <= $i && (count($category->children) > $params->get('tmpl_params.subcat_limit', 5))):?>
				<a href="<?php echo $category->link;?>"><?php echo JText::_('CMORECATS').'...'?></a></li>
				<?php break;?>
			<?php else:?>
				<a href="<?php echo JRoute::_($cat->link)?>">
					<?php echo $cat->title;?>
					<?php if($params->get('tmpl_params.subcat_nums', 0) && $cat->params->get('submission')):?>
						<span class="small">(<?php echo (int)$cat->records_num; ?>)</span>
					<?php endif;?>
				</a>

				<?php if($cat->childs_num):?>
					<?php mod_getChildsDrop($cat, $params, $k + 1);?>
				<?php endif;?>
			<?php endif;?>
				</li>
			<?php endforeach;?>
			<?php if($params->get('records') && count($category->records)):
				foreach ($category->records as $i => $rec):
					if($params->get('records_limit') && $i == $params->get('records_limit') ):
						$rec->title = JText::_('CMORERECORDS');
						$rec->id = -1;
						$rec->url = $category->link;
					endif;
					?>
					<li class="dropdown">
						<a href="<?php echo JRoute::_($rec->url)?>">
							<?php echo $rec->title;?>
						</a>
					</li>
				<?php endforeach;?>
			<?php endif; ?>
		</ul>
	<?php endif; ?>
<?php }} ?>


	<ul class="nav nav-list">
		<?php if( $params->get( 'show_section', 1 ) ) : ?>
	    	<li>
	    		<a class="" href="<?php echo JRoute::_(Url::records($section))  ?>" style="display: inline">
					<?php echo HTMLFormatHelper::icon($section->params->get('personalize.text_icon', 'home.png'));?> <?php echo $section->name;?>
	    			</a></li>
		<?php endif;?>
		<?php foreach ($categories as $cat) :
			if (!$params->get('tmpl_params.cat_empty', 1) && !$cat->num_current && !$cat->num_all) continue;  ?>
			<li class="dropdown<?php if($cat->childs_num || ($params->get('records') && $cat->records_num)) echo '-submenu';?>" >

				<a href="<?php echo JRoute::_($cat->link)?>">
					<?php echo $cat->title;?>
					<?php if($params->get('tmpl_params.cat_nums', 0) && $cat->params->get('submission')):?>
						<span class="small">(<?php echo (int)$cat->records_num; ?>)</span>
					<?php endif;?>
				</a>

				<?php if($cat->childs_num || ($params->get('records') && $cat->records_num)):?>
					<?php mod_getChildsDrop($cat, $params);?>
				<?php endif;?>

			</li>
		<?php endforeach;?>

		<?php if($params->get('records') && $section->records):
			foreach ($section->records as $i => $rec):
				if($params->get('records_limit') && $i == $params->get('records_limit') ):
					$rec->title = JText::_('CMORERECORDS');
					$rec->id = -1;
					$rec->url = $section->link;
				endif;
			?>
				<li class="dropdown">
					<a href="<?php echo JRoute::_($rec->url)?>">
						<?php echo $rec->title;?>
					</a>
				</li>
			<?php endforeach;?>
		<?php endif; ?>

	</ul>
<div class="clearfix"> </div>




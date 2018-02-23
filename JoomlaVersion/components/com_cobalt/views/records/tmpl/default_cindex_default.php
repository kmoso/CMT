<?php
/**
 * Cobalt by MintJoomla
 * a component for Joomla! 1.7 - 2.5 CMS (http://www.joomla.org)
 * Author Website: http://www.mintjoomla.com/
 * @copyright Copyright (C) 2012 MintJoomla (http://www.mintjoomla.com). All rights reserved.
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */

defined('_JEXEC') or die('Restricted access');
$params = $this->tmpl_params['cindex'];
$parent_id = ($params->get('tmpl_params.cat_type', 2) == 1 && $this->category->id) ? $this->category->id : 1;

$cats_model = $this->models['categories'];
$cats_model->section = $this->section;
$cats_model->parent_id = $parent_id;
$cats_model->order = $params->get('tmpl_params.cat_ordering', 'c.lft ASC');
$cats_model->levels = $params->get('tmpl_params.subcat_level');
$cats_model->all = 0;
$cats_model->nums = ($params->get('tmpl_params.cat_nums') || $params->get('tmpl_params.subcat_nums') || !$params->get('tmpl_params.cat_empty', 1));
$categories = $cats_model->getItems();

$cats = array();
foreach ($categories as $cat)
{
	if ($params->get('tmpl_params.cat_empty', 1)
		|| ( !$params->get('tmpl_params.cat_empty', 1) && ($cat->num_current || $cat->num_all) ) )
	$cats[] = $cat;
}
if(!count($cats)) return;

$cols = $params->get('tmpl_params.cat_cols', 3);
$rows = count($cats) / $params->get('tmpl_params.cat_cols',  3);
$rows = ceil($rows);
$ind = 0;
$span = array(1=>12,2=>6,3=>4,4=>3,6=>12);
$api = new CobaltApi();
?>

<?php if($this->tmpl_params['cindex']->get('tmpl_core.show_title', 1)):?>
	<h2><?php echo JText::_($this->tmpl_params['cindex']->get('tmpl_core.title_label', 'Category Index'))?></h2>
<?php endif;?>

<?php for($i = 0; $i < $rows; $i ++):?>
	<div class="row-fluid">
		<?php for($c = 0; $c < $cols; $c++):?>
			<div class="span<?php echo $span[$cols]?> category-box">
				<?php if ($ind < count($cats)): ?>
					<?php $category = $cats[$ind]; ?>
					<div class="<?php echo ($params->get('tmpl_core.well', 1) ? 'well well-small' : NULL)?>">
						<?php if($params->get('tmpl_params.cat_img', 1) && $category->image):?>
							<div style="text-align: center;">
								<?php $url = CImgHelper::getThumb(JPATH_ROOT.DIRECTORY_SEPARATOR.$category->image, $params->get('tmpl_params.cat_img_width', 200), $params->get('tmpl_params.cat_img_height', 200), 'catindex');?>
								<img class="category_icon" alt="<?php echo $category->title; ?>" src="<?php echo $url;?>">
							</div>
							<br>
						<?php endif;?>

						<<?php echo $params->get('tmpl_core.tag', 'h4')?>>
							<a href="<?php echo JRoute::_($category->link)?>">
								<?php if($category->id == JFactory::getApplication()->input->getInt('cat_id')):?>
									<b><?php echo $category->title; ?></b>
								<?php else:?>
									<?php echo $category->title; ?>
								<?php endif;?>
							</a>
							<?php if($params->get('tmpl_params.cat_nums') && $category->params->get('submission')):?>
								<span class="badge badge-info"><?php echo $category->records_num;?></span>
							<?php endif;?>
						</<?php echo $params->get('tmpl_core.tag', 'h4')?>>

						<?php if($params->get('tmpl_params.cat_descr', 0) && $category->description):?>
							<small>
								<?php echo strip_tags($category->{'descr_'.$params->get('tmpl_params.cat_descr')});?>
							</small>
						<?php endif;?>

						<?php if(count($category->children)):?>
							<div class="subcat" id="subcat<?php echo $category->id;?>">
								<?php getChilds($category, $params);?>
							</div>
						<?php endif;?>
					</div>
					<?php $ind++?>
				<?php endif;?>
			</div>
		<?php endfor;?>
	</div>
<?php endfor;?>
<br>

<?php function getChilds($category, $params, $class="nav") { ?>
	<ul class="<?php echo $class?>">
		<?php foreach($category->children as $i => $cat ) :
		if (!$params->get('tmpl_params.subcat_empty', 1) && !$cat->num_current && !$cat->num_all) continue;  ?>
			<li <?php if(count($cat->children)){echo 'class="dropdown-submenu"';}?>>
				<?php if($params->get('tmpl_params.subcat_limit', 5) <= $i && (count($category->children) > $params->get('tmpl_params.subcat_limit', 5))):?>
					<a tabindex="-1" href="<?php echo $category->link;?>"><?php echo JText::_('CMORECATS').'...'?></a>
					</li>
					<?php break;?>
				<?php else:?>
					<a tabindex="-1" href="<?php echo JRoute::_($cat->link)?>">
						<?php echo $cat->title;?>
						<?php if($params->get('tmpl_params.subcat_nums', 0) && $cat->params->get('submission')):?>
							<span class="badge"><?php echo (int)$cat->records_num; ?></span>
						<?php endif;?>
					</a>

					<?php if(count($cat->children)):?>
						<?php getChilds($cat, $params, "dropdown-menu");?>
					<?php endif;?>
				<?php endif;?>
			</li>
		<?php endforeach;?>
	</ul>
<?php } ?>
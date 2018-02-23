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
$cols = $params->get('tmpl_params.cat_cols', 3);
$rows = count($cats) / $params->get('tmpl_params.cat_cols',  3);

$rows = ceil($rows);
$width = round(100 / $cols);
$ind = 0;

//var_dump($parent_id); exit;
?>

<?php if($params->get('tmpl_params.highlight')):?>
<style>
<!--
.category-active a {
	color: <?php echo $params->get('tmpl_params.highlight') ?>;
}
-->
</style>
<?php endif;?>

<?php if (count($cats)) : ?>
	<div class="categories-list" style="padding-bottom: 20px;">
		<?php if($this->tmpl_params['cindex']->get('tmpl_core.show_title', 1)):?>
			<h2 class="contentheading"><?php echo JText::_($this->tmpl_params['cindex']->get('tmpl_core.title_label', 'Category Index'))?></h2>
		<?php endif;?>
		<table class="list" width="100%" border="0" cellpadding="5" cellspacing="0">
			<tr valign="top">
			<?php for($c = 0; $c < $cols; $c++):?>
				<td width="<?php echo $width;?>%">
				<?php for($i = 0; $i < $rows; $i ++):?>
					<?php if ($ind >= count($cats)) continue; ?>
					<?php $category = $cats[$ind]; ?>
					<<?php echo $this->tmpl_params['cindex']->get('tmpl_params.tag', 'h4')?> class="cat-name<?php echo (JFactory::getApplication()->input->getInt('cat_id') == $category->id ? ' category-active' : NULL)?>">
						<?php if($params->get('tmpl_params.cat_img', 1) && $category->image):?>
							<div><img style="max-width:<?php echo $params->get('tmpl_params.cat_img_width', 200)?>px;" class="category_icon" alt="<?php echo $category->title; ?>" src="<?php echo JURI::root().$category->image;?>"></div>
						<?php endif;?>
						<?php if(count($category->children)):?>
							<img style="cursor: pointer;" id="iconsubcat<?php echo $category->id;?>" src="<?php echo JURI::root()?>media/mint/icons/16/toggle.png" align="absmiddle" />
						<?php endif;?>
						<a href="<?php echo JRoute::_($category->link)?>"><?php echo $category->title; ?></a>
						<?php if($params->get('tmpl_params.cat_nums') && $category->params->get('submission')):?>
							(<?php echo $category->records_num;?>)
						<?php endif;?>
					</<?php echo $this->tmpl_params['cindex']->get('tmpl_params.tag', 'h4')?>>
					<?php if($params->get('tmpl_params.cat_descr', 0) && $category->description):?>
						<?php echo $category->{'descr_'.$params->get('tmpl_params.cat_descr')}?>
					<?php endif;?>
					
					<?php if(count($category->children)):?>
						<div style="" class="subcat" id="subcat<?php echo $category->id;?>">
							<?php getChilds($category, $params);?>
						</div>
						<script type="text/javascript">//$('subcats<?php echo $category->id;?>').hide();</script>
					<?php endif;
					$ind++; ?>
				<?php endfor;?>
				</td>
			<?php endfor;?>
			</tr>
		</table>
	</div>
<?php endif; ?>
<script type="text/javascript">
<!--
$$('.subcat').each(function(e){
	var list_element = new Fx.Slide(e.id)
	var icon = $('icon' + e.id);
	if(!Cookie.read('category_state'+e.id))
	{	
		if(!<?php echo $params->get('tmpl_params.cat_unfold') ? 'true' : 'false';?>)
		{
			list_element.hide();
			icon.set('src', '<?php echo JURI::root()?>media/mint/icons/16/toggle-expand.png');
		}
	}
	icon.addEvent('click', function(){
		list_element.toggle();
		if(list_element.from[1] == 0)
		{
			Cookie.write('category_state'+e.id, 1, {duration: 30000});
			icon.set('src', '<?php echo JURI::root()?>media/mint/icons/16/toggle.png');
		}
		else
		{
			Cookie.dispose('category_state'+e.id);
			icon.set('src', '<?php echo JURI::root()?>media/mint/icons/16/toggle-expand.png');
		}	
	});
});
//-->
</script>
<?php function getChilds($category, $params, $k = 1) { ?>
	<ul>
		<?php foreach($category->children as $i => $cat ) :
		if (!$params->get('tmpl_params.subcat_empty', 1) && !$cat->num_current && !$cat->num_all) continue;  ?>
			<li<?php echo (JFactory::getApplication()->input->getInt('cat_id') == $cat->id) ? ' class="category-active" ' : ''; ?>>
				<?php if($params->get('tmpl_params.subcat_limit', 5) <= $i && (count($category->children) > $params->get('tmpl_params.subcat_limit', 5))):?>
					<a href="<?php echo $category->link;?>"><?php echo JText::_('CMORECATS').'...'?></a></li>
					<?php break;?>
				<?php else:?>
					<a href="<?php echo JRoute::_($cat->link)?>">
						<?php echo $cat->title;?>
					</a>
					<?php if($params->get('tmpl_params.subcat_nums', 0) && $cat->params->get('submission')):?>
						<span class="small">(<?php echo (int)$cat->records_num; ?>)</span>
					<?php endif;?>
					
					<?php if(count($cat->children)):?>
						<?php getChilds($cat, $params, $k + 1);?>
					<?php endif;?>
				<?php endif;?>
			</li>
		<?php endforeach;?>
	</ul>
<?php } ?>
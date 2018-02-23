<?php
/**
 * Cobalt by MintJoomla
 * a component for Joomla! 1.7 - 2.5 CMS (http://www.joomla.org)
 * Author Website: http://www.mintjoomla.com/
 * @copyright Copyright (C) 2012 MintJoomla (http://www.mintjoomla.com). All rights reserved.
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die('Restricted access');


$vw = JFactory::getApplication()->input->get('view_what');
$params = $this->tmpl_params['markup'];
$listparams = $this->tmpl_params['list'];
$user = JFactory::getUser(JFactory::getApplication()->input->getInt('user_id'));
$cats = count((array)@$this->user_categories);
if($cats > 4)
{
	$cwidth = 25;
}
else
{
	$cwidth = $cats ? floor(100 / $cats) : 0;
}
$current_user = JFactory::getUser(JFactory::getApplication()->input->getInt('user_id', $this->user->get('id')));
$num = CEventsHelper::showNum('total', 0);

?>

<div class="page-header">
	<?php if($this->isMe):?>
		<div class="btn-group pull-right">
			<?php if($params->get('menu.menu_user_cat_manage') && in_array($this->section->params->get('personalize.pcat_submit'), $this->user->getAuthorisedViewLevels())):?>
				<a class="btn btn-small" rel="tooltip" data-original-title="<?php echo JText::_($params->get('menu.menu_user_cat_manage_label', 'Manage Categories'))?>" href="<?php echo JRoute::_(Url::_('categories').'&return='.Url::back())?>">
					<img src="<?php echo JURI::root(TRUE);?>/media/mint/icons/16/category.png" align="absmiddle" /></a>
			<?php endif;?>
			<?php if($params->get('menu.menu_user_moder') && MECAccess::allowModerate(NULL, NULL, $this->section)):?>
				<a class="btn btn-small" rel="tooltip" data-original-title="<?php echo JText::_($params->get('menu.menu_user_moder_label', 'Manage Moderators'))?>" href="<?php echo JRoute::_('index.php?option=com_cobalt&view=moderators&filter_section='.$this->section->id.'&return='.Url::back());?>">
						<img src="<?php echo JURI::root(TRUE);?>/media/mint/icons/16/user-share.png" align="absmiddle" /></a>
			<?php endif;?>

			<?php if($this->section->params->get('personalize.allow_section_set', 1)):?>
				<a class="btn btn-small" rel="tooltip" data-original-title="<?php echo JText::sprintf('CSELECTOPTIONS', $this->section->name);?>" href="<?php echo JRoute::_('index.php?option=com_cobalt&view=options&layout=section&section_id='.$this->section->id.'&return='.Url::back());?>">
					<img src="<?php echo JURI::root(TRUE);?>/media/mint/icons/16/gear.png" align="absmiddle" /></a>
			<?php endif;?>
		</div>
	<?php elseif ($this->user->get('id') && in_array($this->section->params->get('events.subscribe_user'), $this->user->getAuthorisedViewLevels())):?>
		<div class="pull-right">
			<?php echo HTMLFormatHelper::followuser($current_user->get('id'), $this->section);?>
		</div>
	<?php endif;?>

	<h1>
		<?php if(CUsrHelper::getOption($user, "sections.{$this->section->id}.title") && JFactory::getApplication()->input->get('view_what', 'created') == 'created'):?>
			<?php echo CUsrHelper::getOption($user, "sections.{$this->section->id}.title");?>
		<?php else:?>
			<?php if($this->isMe):?>
				<?php echo JText::_($params->get('title.TITLE_1_'.strtoupper(JFactory::getApplication()->input->get('view_what', 'created')))); ?>
			<?php else:?>
				<?php echo JText::sprintf($params->get('title.TITLE_0_'.strtoupper(JFactory::getApplication()->input->get('view_what', 'created'))), CCommunityHelper::getName(JFactory::getApplication()->input->getInt('user_id'), $this->section, array('nohtml' => 1))); ?>
			<?php endif;?>
		<?php endif;?>

		<?php if(!empty($this->user_category->name)):?>
			- <?php echo $this->user_category->name; ?>
		<?php endif;?>

		<?php if($params->get('menu.menu_user_evented') && CEventsHelper::getNum('section', $this->section->id)):?>
			<a rel="tooltip" data-original-title="<?php echo JText::_($params->get('menu.menu_user_events_label', 'With new events'))?>" href="<?php echo JRoute::_(Url::user('events'));?>">
				<?php echo CEventsHelper::showNum('section', $this->section->id)?></a>
		<?php endif;?>
	</h1>
</div>

<?php if(JFactory::getApplication()->input->get('view_what') == 'events'):?>
	<button href="javascript:void(0);" class="btn" type="button" onclick="Joomla.submitbutton('records.markread')">
		<img src="<?php echo JURI::root(TRUE)?>/media/mint/icons/16/bell--minus.png"
			align="absmiddle" alt="<?php echo JText::_('CCLEARALL_NOTIF'); ?>" />
		<?php echo Jtext::_('CCLEARALL_NOTIF');?>
	</button>

	<button class="btn" type="button"
		onclick="window.location = '<?php echo JRoute::_('index.php?option=com_cobalt&view=notifications&return='.Url::back());?>'">
		<img src="<?php echo JURI::root(TRUE)?>/media/mint/icons/16/bell.png"
			align="absmiddle" alt="<?php echo JText::_('CNOTCENTR'); ?>" />
		<?php echo JText::_('CNOTCENTR');?>
		<?php if($num):?>
			<?php echo $num?>
		<?php endif;?>
	</button>

	<button class="btn" type="button" onclick="window.location = '<?php echo JRoute::_('index.php?option=com_cobalt&view=options&return='.Url::back())?>'">
		<img src="<?php echo JURI::root(TRUE)?>/media/mint/icons/16/gear.png" align="absmiddle">
		<?php echo JText::_('CNOTSET')?>
	</button>
<?php else: ?>
	<?php if((!$vw || $vw == 'created')
		&& ($params->get('personal.user_avatar') || $params->get('personal.user_info') || $params->get('personal.user_message'))):?>
		<table>
			<tr>
				<?php if($params->get('personal.user_avatar')):?>
					<td valign="top" width="<?php echo $params->get('personal.user_avatar_w', 100) + 20?>px">
						<img src="<?php echo CCommunityHelper::getAvatar(JFactory::getApplication()->input->getInt('user_id'), $params->get('personal.user_avatar_w', 100), $params->get('personal.user_avatar_h', 100));?>" />
					</td>
				<?php endif;?>

				<td>
					<?php if($params->get('personal.user_info')):?>
						<div>
							<small>
								<?php echo CCommunityHelper::getName($current_user->id, $this->section);?>
								<?php if($this->section->params->get('events.subscribe_user')):?>
									<?php echo JText::_('CFOLLOWING') ?> <span class="badge lead"><?php echo CStatistics::follow($current_user->get('id', 0), $this->section->id) ?></span>
									<?php echo JText::_('CFOLLOWERS') ?>  <span class="badge lead"><?php echo CStatistics::followed($current_user->get('id', 0), $this->section->id) ?></span>
								<?php endif;?>
							</small>
						</div>
					<?php endif;?>
					<?php if($params->get('personal.user_message')):?>
						<div class="lead">
							<?php if(CUsrHelper::getOption($user, "sections.{$this->section->id}.description")):?>
								<?php echo nl2br(CUsrHelper::getOption($user, "sections.{$this->section->id}.description"));?>
							<?php elseif($this->isMe && !CUsrHelper::getOption($user, "sections.{$this->section->id}.description") && $this->section->params->get('personalize.personalize', 1)):?>
								<?php echo JText::sprintf('CEDITWELCOMETEXT', JRoute::_('index.php?option=com_cobalt&view=options&layout=section&section_id='.$this->section->id.'&return='.Url::back()))?>
							<?php endif;?>
						</div>
					<?php endif;?>
				</td>
			</tr>
		</table>
	<?php endif;?>

	<?php if(!empty($this->user_category->description)):?>
		<div class="lead pull-left">
			<?php echo $this->user_category->description; ?>
		</div>
	<?php endif;?>
<?php endif;?>
<div class="clearfix"></div>
<br />
<?php if($params->get('personal.user_menu')):?>
	<ul class="nav nav-tabs">
		<?php $counts = $this->_getUsermenuCounts($params, $current_user->get('id')); ?>
		<?php if($params->get('menu.menu_user_my')):?>
			<?php if(!empty($this->user_categories) && ($vw == 'created' || !$vw)):?>
				<li <?php if(!$vw || $vw == 'created') echo 'class="active"'?>>
					<a href="#" class="dropdown-toggle" data-toggle="collapse" data-target="#categories">
						<?php if($params->get('menu.menu_user_my_icon')):?>
							<?php echo HTMLFormatHelper::icon($this->section->params->get('personalize.text_icon', 'home.png'));?>
						<?php endif;?>
						<?php echo JText::_($this->isMe ? $params->get('menu.menu_user_my_label', 'My Home') : $params->get('menu.menu_user_other_label', 'Created'))?>
						<sup><?php echo $counts->created;?></sup>
						<b class="caret"></b>
					</a>
				</li>
			<?php else: ?>
				<li <?php if(!$vw || $vw == 'created') echo 'class="active"'?>>
					<a href="<?php echo JRoute::_(Url::user('created', $current_user->get('id')));?>">
						<?php if($params->get('menu.menu_user_my_icon')):?>
							<?php echo HTMLFormatHelper::icon($this->section->params->get('personalize.text_icon', 'home.png'));?>
						<?php endif;?>
						<?php echo JText::_($this->isMe ? $params->get('menu.menu_user_my_label', 'My Home') : $params->get('menu.menu_user_other_label', 'Created'))?>
						<sup><?php echo $counts->created;?></sup>
					</a>
				</li>
			<?php endif;?>
		<?php endif;?>

		<?php if($params->get('menu.menu_user_followed') && MECAccess::allowUserMenu($user, 'followed', $this->section)):?>
			<li <?php if($vw == 'follow') echo 'class="active"'?>>
				<a href="<?php echo JRoute::_(Url::user('follow', $current_user->get('id')));?>">
					<?php if($params->get('menu.menu_user_follow_icon')):?>
						<img src="<?php echo JURI::root(TRUE);?>/media/mint/icons/16/follow1.png" align="absmiddle" />
					<?php endif;?>
					<?php echo JText::_($params->get('menu.menu_user_follow_label', 'Follow'))?>
					<sup><?php echo $counts->followed;?></sup>
				</a>
			</li>
		<?php endif;?>

		<?php if($params->get('menu.menu_user_favorite') && MECAccess::allowUserMenu($user, 'bookmarked', $this->section)):?>
			<li <?php if($vw == 'favorited') echo 'class="active"'?>>
				<a href="<?php echo JRoute::_(Url::user('favorited', $current_user->get('id')));?>">
					<?php if($params->get('menu.menu_user_favorite_icon')):?>
						<img src="<?php echo JURI::root(TRUE) . '/media/mint/icons/bookmarks/' . $listparams->get('tmpl_core.bookmark_icons', 'star') . '/state1.png';?>" align="absmiddle" />
					<?php endif;?>
					<?php echo JText::_($params->get('menu.menu_user_favorite_label', 'Bookmarked'))?>
					<sup><?php echo $counts->favorited; ?></sup>
				</a>
			</li>
		<?php endif;?>

		<?php if($params->get('menu.menu_user_rated') && MECAccess::allowUserMenu($user, 'rated', $this->section)):?>
			<li <?php if($vw == 'rated') echo 'class="active"'?>>
				<a href="<?php echo JRoute::_(Url::user('rated', $current_user->get('id')));?>">
					<?php if($params->get('menu.menu_user_rated_icon')):?>
						<img src="<?php echo JURI::root(TRUE);?>/media/mint/icons/16/star.png" align="absmiddle" />
					<?php endif;?>
					<?php echo JText::_($params->get('menu.menu_user_rated_label', 'Rated'))?>
					<sup><?php echo $counts->rated; ?></sup>
				</a>
			</li>
		<?php endif;?>

		<?php if($params->get('menu.menu_user_commented') && MECAccess::allowUserMenu($user, 'commented', $this->section)):?>
			<li <?php if($vw == 'commented') echo 'class="active"'?>>
				<a href="<?php echo JRoute::_(Url::user('commented', $current_user->get('id')));?>">
					<?php if($params->get('menu.menu_user_commented_icon')):?>
						<img src="<?php echo JURI::root(TRUE);?>/media/mint/icons/16/balloon-left.png" align="absmiddle" />
					<?php endif;?>
					<?php echo JText::_($params->get('menu.menu_user_commented_label', 'Commented'))?>
					<sup><?php echo $counts->commented; ?></sup>
				</a>
			</li>
		<?php endif;?>

		<?php if($params->get('menu.menu_user_visited') && MECAccess::allowUserMenu($user, 'visited', $this->section)):?>
			<li <?php if($vw == 'visited') echo 'class="active"'?>>
				<a href="<?php echo JRoute::_(Url::user('visited', $current_user->get('id')));?>">
					<?php if($params->get('menu.menu_user_visited_icon')):?>
						<img src="<?php echo JURI::root(TRUE);?>/media/mint/icons/16/hand-point-090.png" align="absmiddle" />
					<?php endif;?>
					<?php echo JText::_($params->get('menu.menu_user_visited_label', 'Visited'))?>
					<sup><?php echo $counts->visited; ?></sup>
				</a>
			</li>
		<?php endif;?>

		<?php
		if(
			($user->get('id') && $user->get('id') == JFactory::getUser()->get('id')) &&
			(
				($params->get('menu.menu_user_hidden') && MECAccess::allowUserMenu($user, 'hidden', $this->section)) ||
				($params->get('menu.menu_user_expire') && MECAccess::allowUserMenu($user, 'expire', $this->section)) ||
				($params->get('menu.menu_user_unpublished') && MECAccess::allowUserMenu($user, 'unpublished', $this->section))
			)
		):
		?>
			<?php $in_array = in_array($vw, array('hidden', 'expire', 'unpublished'));?>
			<li class="dropdown <?php if($in_array) echo 'active'?>">
				<a class="dropdown-toggle" data-toggle="dropdown" href="#">
					<?php if ($in_array):?>
						<?php switch ($vw):
								  case 'hidden':?>
								<img src="<?php echo JURI::root(TRUE);?>/media/mint/icons/16/eye-half.png" align="absmiddle" />
								<?php echo JText::_($params->get('menu.menu_user_'.$vw.'_label', ''))?>
								<sup><?php echo $counts->hidden; ?></sup>
							<?php break;?>
							<?php case 'expire':?>
								<img src="<?php echo JURI::root(TRUE);?>/media/mint/icons/16/clock--exclamation.png" align="absmiddle" />
								<?php echo JText::_($params->get('menu.menu_user_'.$vw.'_label', ''))?>
								<sup><?php echo $counts->expired; ?></sup>
							<?php break;?>
							<?php case 'unpublished':?>
								<img src="<?php echo JURI::root(TRUE);?>/media/mint/icons/16/minus-circle.png" align="absmiddle" />
								<?php echo JText::_($params->get('menu.menu_user_'.$vw.'_label', ''))?>
								<sup><?php echo $counts->unpublished; ?></sup>
							<?php break;?>
						<?php endswitch;?>
					<?php endif;?>
					<span class="caret"></span>
		    	</a>
		    	<ul class="dropdown-menu">
					<?php if($params->get('menu.menu_user_hidden') && MECAccess::allowUserMenu($user, 'hidden', $this->section)):?>
						<li <?php if($vw == 'hidden') echo 'class="active"'?>>
							<a href="<?php echo JRoute::_(Url::user('hidden', $current_user->get('id')));?>">
								<?php if($params->get('menu.menu_user_hidden_icon')):?>
									<img src="<?php echo JURI::root(TRUE);?>/media/mint/icons/16/eye-half.png" align="absmiddle" />
								<?php endif;?>
								<?php echo JText::_($params->get('menu.menu_user_hidden_label', 'Hidden'))?>
								<sup><?php echo $counts->hidden; ?></sup>
							</a>
						</li>
					<?php endif;?>
					<?php if($params->get('menu.menu_user_expire') && MECAccess::allowUserMenu($user, 'expire', $this->section)):?>
						<li <?php if($vw == 'expired') echo 'class="active"'?>>
							<a href="<?php echo JRoute::_(Url::user('expired', $current_user->get('id')));?>">
								<?php if($params->get('menu.menu_user_expire_icon')):?>
									<img src="<?php echo JURI::root(TRUE);?>/media/mint/icons/16/clock--exclamation.png" align="absmiddle" />
								<?php endif;?>
								<?php echo JText::_($params->get('menu.menu_user_expire_label', 'Expired'))?>
								<sup><?php echo $counts->expired; ?></sup>
							</a>
						</li>
					<?php endif;?>
					<?php if($params->get('menu.menu_user_unpublished') && MECAccess::allowUserMenu($user, 'unpublished', $this->section)):?>
						<li <?php if($vw == 'unpublished') echo 'class="active"'?>>
							<a href="<?php echo JRoute::_(Url::user('unpublished', $current_user->get('id')));?>">
								<?php if($params->get('menu.menu_user_unpublished_icon')):?>
									<img src="<?php echo JURI::root(TRUE);?>/media/mint/icons/16/minus-circle.png" align="absmiddle" />
								<?php endif;?>
								<?php echo JText::_($params->get('menu.menu_user_unpublished_label', 'Expired'))?>
								<sup><?php echo $counts->unpublished; ?></sup>
							</a>
						</li>
					<?php endif;?>
			    </ul>
			</li>
		<?php endif;?>
	</ul>
<?php endif;?>
<!--
Show user categories only if we ave some and we look user created rcords.
Do not show category if it is reted or comemnted.
-->
<?php if(!empty($this->user_categories) && ($vw == 'created' || !$vw)):?>
<style type="text/css">
.cat-icon {
	width: 100%;
	height: <?php echo  $params->get('personal.cat_icon_height', 100) ?>px;
	display: block;
	background-repeat: no-repeat;
	background-position: center center;
	margin-bottom: 10px;
}
.cat-content {
	text-align: center;
}
</style>
	<?php
	$cols = $params->get('personal.user_cat_cols', 3);
	$cats = count($this->user_categories);
	$rows = ceil($cats / $cols);
	$spans = array('2' => 6, '3' => 4, '4' => 3, '6' => 2); $k = 0;
	?>
	<div id="categories" class="categories container-fluid collapse">
		<?php for ($r=0; $r < $rows; $r++):?>
			<div class="row-fluid">
				<?php for ($c=0; $c < $cols; $c++):?>
					<?php $category = array_shift($this->user_categories);?>
					<div class="cat-content span<?php echo $spans[$cols];?> <?php if(isset($category->id) && JFactory::getApplication()->input->getInt('ucat_id') == $category->id) echo 'active';?>">
						<?php if($category): ?>
							<?php if($params->get('personal.user_categories_icons')):?>
								<div class="cat-icon" style="background-image: url(<?php
									if($category->icon) echo CImgHelper::getThumb(JPATH_ROOT. DIRECTORY_SEPARATOR .'images'. DIRECTORY_SEPARATOR .'usercategories'. DIRECTORY_SEPARATOR .JFactory::getApplication()->input->getInt('user_id'). DIRECTORY_SEPARATOR .$category->icon,
										$params->get('personal.cat_icon_width', 100), $params->get('personal.cat_icon_height', 100), 'usercatthumbnails', JFactory::getApplication()->input->getInt('user_id'))?>)"></div>
							<?php endif;?>
							<div>
								<big><a href="<?php echo JRoute::_(URL::usercategory_records(JFactory::getApplication()->input->getInt('user_id'), $this->section, $category->id) )?>" class="<?php if($category->id == JFactory::getApplication()->input->getInt('ucat_id')) {echo 'cat-active';}?>">
									<?php echo $category->name;?>
								</a></big>
							</div>
							<?php if($category->description):?>
								<small><?php echo $this->escape($category->description);?></small>
							<?php endif;?>
						<?php endif;?>
					</div>
				<?php endfor; ?>
			</div>
		<?php endfor; ?>
		<div style="clear:both;"></div>
		<br>
	</div>
<?php endif;?>

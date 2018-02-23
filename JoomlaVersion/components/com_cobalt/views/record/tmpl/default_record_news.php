<?php
/**
 * a component for Joomla! 1.7 - 2.5 CMS (http://www.joomla.org)
 * Author: Vadim Kozhukhov
 * Author Website: http://www.torbara.com/
 * @copyright Copyright (C) 2015 Torbara (http://www.torbara.com). All rights reserved.
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */

defined('_JEXEC') or die();

$item = $this->item;
$params = $this->tmpl_params['record'];
$icons = array();
$category = array();
$author = array();
$details = array();
$started = FALSE;
$i = $o = 0;

?>


<article class="<?php echo $this->appParams->get('pageclass_sfx')?><?php if($item->featured) echo ' article-featured' ?>">
	<?php if($this->user->get('id')&&($item->controls)):?>			
            <ul class="admin-edit">
                <?php echo list_controls($item->controls);?>
            </ul>
        <?php endif;?>
	<div class="clearfix"></div>

	
        <div class="article-slider">
            <?php $field = $item->fields_by_id[11]; // Article Slider ?>
            <?php echo $field->result; ?>
        </div>
        <div class="article-param">
            <div class="date">
                <i class="uk-icon-calendar"></i>
                <?php
                  echo JHtml::_('date', $item->created, $params->get('tmpl_core.item_time_format'));
                ?>
            </div>
            <div class="author">
                <i class="uk-icon-user"></i>
                <?php
                echo FilterHelper::filterLink('filter_user', $item->user_id, CCommunityHelper::getName($item->user_id, $this->section), JText::sprintf('CSHOWALLUSERREC', CCommunityHelper::getName($item->user_id, $this->section, array('nohtml' => 1))), JText::sprintf('CSHOWALLUSERREC', CCommunityHelper::getName($item->user_id, $this->section, array('nohtml' => 1))), $this->section);
                ?>
            </div>
            <div class="categories">
                <i class="uk-icon-list-ul"></i>
                <?php echo implode(', ', $item->categories_links); ?>
            </div>
        </div>
        <div class="article-single-text">
            <?php $field = $item->fields_by_id[13]; // Article Text ?>
            <?php echo $field->result; ?>
        </div>

        <?php echo $this->loadTemplate('sportaktags');?>
        <div class="share-wrap">
            <div class="share-title">share</div>
           <script type="text/javascript" src="//yastatic.net/share/share.js" charset="utf-8"></script>
           <div class="yashare-auto-init" data-yashareL10n="en" data-yashareType="none" data-yashareQuickServices="facebook,twitter,gplus"></div>
        </div>
</article>
<div class="news-nav-wrap">
    {modulepos news-nav}
</div>
<div>
    {modulepos news-related}
</div>


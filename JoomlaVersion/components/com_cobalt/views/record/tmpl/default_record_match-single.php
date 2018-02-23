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


<article class="match-article <?php echo $this->appParams->get('pageclass_sfx')?><?php if($item->featured) echo ' article-featured' ?>">
	<?php if($this->user->get('id')&&($item->controls)):?>			
            <ul class="admin-edit">
                <?php echo list_controls($item->controls);?>
            </ul>
        <?php endif;?>
	<div class="clearfix"></div>
    <div class="uk-grid">
        <div class="uk-width-6-10">
            <div class="top-text article-single-text">
                <div class="big-title">About <span>Match</span></div>
                <?php $field = $item->fields_by_id[20]; // About text ?>
                <?php echo $field->result; ?>
            </div>
        </div>
        <div class="uk-width-4-10">
            <?php $field = $item->fields_by_id[10]; // Map ?>
            <?php echo $field->result; ?>
        </div>
    </div>
    <div class="uk-grid">
        <div class="uk-width-1-1">
            <div class="middle-text article-single-text">
                <?php $field = $item->fields_by_id[22]; // Middle Text ?>
                <?php echo $field->result; ?>
            </div>
        </div>
        <div class="uk-width-1-1">
            <div class="match-gallery">
                
                <?php $field = $item->fields_by_id[19]; // Gallery ?>
                <?php echo $field->result; ?>
            </div>
        </div>
        <div class="uk-width-1-1">
            <div class="article-single-text">
                <?php $field = $item->fields_by_id[21]; // Bottom Text ?>
                <?php echo $field->result; ?>
            </div>
        </div>
        <div class="uk-width-1-1">
            <div class="share-wrap">
                <div class="share-title">share</div>
               <script type="text/javascript" src="//yastatic.net/share/share.js" charset="utf-8"></script>
               <div class="yashare-auto-init" data-yashareL10n="en" data-yashareType="none" data-yashareQuickServices="facebook,twitter,gplus"></div>
            </div>
        </div>
    </div>    
    

</article>



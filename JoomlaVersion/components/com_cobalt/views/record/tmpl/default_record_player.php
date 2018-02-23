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

<article class="player-single <?php echo $this->appParams->get('pageclass_sfx')?><?php if($item->featured) echo ' article-featured' ?>">
    <div class="uk-container uk-container-center alt">
        <div class="uk-gfid">
            <?php if($this->user->get('id')&&($item->controls)):?>			
                <ul class="admin-edit">
                    <?php echo list_controls($item->controls);?>
                </ul>
            <?php endif;?>
         </div>
    </div>
	
        <div class="player-top">
            <div class="uk-container uk-container-center alt">
            <div class="uk-grid">
                <div class="uk-width-large-5-12 uk-width-medium-1-1">
                    <div class="avatar">
                        <?php $field = $item->fields_by_id[1]; // Avatar ?>
                        <?php echo $field->result; ?>
                    </div>
                </div>
                <div class="uk-width-large-1-12 uk-width-medium-1-5">
                    <div class="number">
                        <?php $field = $item->fields_by_id[36]; // Player Number ?>
                        <?php echo $field->result; ?>
                    </div>
                </div>
                <div class="uk-width-large-6-12 uk-width-medium-4-5">
                    <div class="name">
                        <h2>
                            <?php $field = $item->fields_by_id[2]; // Player Name ?>
                            <?php echo $field->result; ?>
                        </h2>
                    </div>
                    <div class="wrap">
                        <ul class="info">
                            <?php if(@$item->fields_by_id[26]): ?><li><span>POSITION</span><span><?php $field = $item->fields_by_id[26]; ?><?php echo $field->result; ?></span></li><?php endif; ?>
                            <?php if(@$item->fields_by_id[27]): ?><li><span>APPEARANCES</span><span><?php $field = $item->fields_by_id[27]; ?><?php echo $field->result; ?></span></li><?php endif; ?>
                            <?php if(@$item->fields_by_id[28]): ?><li><span>GOALs</span><span><?php $field = $item->fields_by_id[28]; ?><?php echo $field->result; ?></span></li><?php endif; ?>
                            <?php if(@$item->fields_by_id[29]): ?><li><span>YELLOW CARDS</span><span><?php $field = $item->fields_by_id[29]; ?><?php echo $field->result; ?></span></li><?php endif; ?>
                            <?php if(@$item->fields_by_id[30]): ?><li><span>RED CARDS</span><span><?php $field = $item->fields_by_id[30]; ?><?php echo $field->result; ?></span></li><?php endif; ?>
                            <?php if(@$item->fields_by_id[31]): ?><li><span>D.O.B</span><span><?php $field = $item->fields_by_id[31]; ?><?php echo $field->result; ?></span></li><?php endif; ?>
                            <?php if(@$item->fields_by_id[32]): ?><li><span>NATIONALITY</span><span><?php $field = $item->fields_by_id[32]; ?><?php echo $field->result; ?></span></li><?php endif; ?>
                            <?php if(@$item->fields_by_id[33]): ?><li><span>HEIGHT</span><span><?php $field = $item->fields_by_id[33]; ?><?php echo $field->result; ?></span></li><?php endif; ?>
                            <?php if(@$item->fields_by_id[34]): ?><li><span>WEIGHT</span><span><?php $field = $item->fields_by_id[34]; ?><?php echo $field->result; ?></span></li><?php endif; ?>
                        </ul>
                        <ul class="socials">
                            <?php if(@$item->fields_by_id[37]): ?><li class="twitter"><?php $field = $item->fields_by_id[37]; ?><?php echo $field->result; ?></li><?php endif; ?>
                            <?php if(@$item->fields_by_id[38]): ?><li class="facebook"><?php $field = $item->fields_by_id[38]; ?><?php echo $field->result; ?></li><?php endif; ?>
                            <?php if(@$item->fields_by_id[39]): ?><li class="google-plus"><?php $field = $item->fields_by_id[39]; ?><?php echo $field->result; ?></li><?php endif; ?>
                            <?php if(@$item->fields_by_id[40]): ?><li class="pinterest"><?php $field = $item->fields_by_id[40]; ?><?php echo $field->result; ?></li><?php endif; ?>
                            <?php if(@$item->fields_by_id[41]): ?><li class="linkedin"><?php $field = $item->fields_by_id[41]; ?><?php echo $field->result; ?></li><?php endif; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        </div>
        <div class="uk-container uk-container-center alt">
            <div class="uk-grid">
                <div class="uk-width-1-1"><div class="line"></div></div>
            </div>
        </div>
        <div class="uk-container uk-container-center alt">
            <div class="uk-grid">
                <div class="uk-width-9-10 uk-push-1-10">
                    <div class="player-total-info">
                        <?php $field = $item->fields_by_id[35]; ?><?php echo $field->result; ?>
                    </div>
                </div>
            </div>
        </div>
</article>

<div>
    
                {modulepos other-players}
            
</div>


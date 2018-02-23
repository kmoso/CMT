<?php
/**
 * a component for Joomla! 1.7 - 2.5 CMS (http://www.joomla.org)
 * Author: Vadim Kozhukhov
 * Author Website: http://www.torbara.com/
 * @copyright Copyright (C) 2015 Torbara (http://www.tobara.com). All rights reserved.
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die('Restricted access'); ?>

<div class="match-list-wrap foot">
<div id="carusel-<?php echo $key ?>" class="uk-slidenav-position" data-uk-slideshow="{ height : 37, autoplay:true, animation:'scroll' }">
    <div class="last-match-top">
        <div class="last-match-title">Siguientes partidos</div>
        <div class="footer-slider-btn">
            <a href="" class="uk-slidenav uk-slidenav-contrast uk-slidenav-previous" data-uk-slideshow-item="previous"></a>
            <a href="" class="uk-slidenav uk-slidenav-contrast uk-slidenav-next" data-uk-slideshow-item="next"></a>
        </div>
        <div class="clear"></div>
    </div>
    <ul class="uk-slideshow">
        <?php foreach ($this->items AS $item): ?>
        <li>
        <div class="match-list-item alt foot">    
            <div class="wrapper">
                <div class="logo">
                    <?php if (@$item->fields_by_id[8]): ?>
                        <?php $field = $item->fields_by_id[8]; // Team 1 Logo  ?>
                        <?php echo $field->result; ?>
                    <?php endif; ?>
                </div>
                <div class="team-name">
                    <?php $field = $item->fields_by_id[5]; // Team 1 Name ?>
                    <?php echo $field->result; ?>
                </div>
                <div class="versus">VS</div>

                <div class="team-name">
                    <?php $field = $item->fields_by_id[7]; // Team 2 Name  ?>
                    <?php echo $field->result; ?>
                </div>
                <div class="logo">
                    <?php if (@$item->fields_by_id[9]): ?>
                        <?php $field = $item->fields_by_id[9]; // Team 2 Logo  ?>
                        <?php echo $field->result; ?>
                    <?php endif; ?>
                </div><!-- 4: Fecha 8: Logo -->								<!--                  <a class="read-more" href="<?php echo JRoute::_($item->url);?>">Read More</a>				-->
				</div>    
        </div>    
        </li>    
        <?php endforeach; ?>
    </ul>
</div>



    
</div>

<?php
/**
 * a component for Joomla! 1.7 - 2.5 CMS (http://www.joomla.org)
 * Author: Vadim Kozhukhov
 * Author Website: http://www.torbara.com/
 * @copyright Copyright (C) 2015 Torbara (http://www.tobara.com). All rights reserved.
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die('Restricted access'); ?>

<div class="match-list-wrap">
	<?php foreach ($this->items AS $item): ?>
				

                

            <div class="match-list-item">    
                <div class="date">
                    <?php $field = $item->fields_by_id[4]; // Date ?>
                    <?php echo $field->result; ?>
                </div>
                <div class="logo">
                    <?php if(@$item->fields_by_id[8]): ?>
                        <?php $field = $item->fields_by_id[8]; // Team 1 Logo ?>
                        <?php echo $field->result; ?>
                     <?php endif; ?>
                </div>
                <div class="team-name">
                    <?php $field = $item->fields_by_id[5]; // Team 1 Name ?>
                    <?php echo $field->result; ?>
                </div>
                
                <?php if(@$item->fields_by_id[42]&&@$item->fields_by_id[43]) : ?>
                    <div class="team-score">
                        <?php $field = $item->fields_by_id[42]; // Team 1 Score ?>
                        <?php echo $field->result; ?>
                    </div>
                    <div class="score-separator">:</div>
                    <div class="team-score">
                        <?php $field = $item->fields_by_id[43]; // Team 2 Score ?>
                        <?php echo $field->result; ?>
                    </div>
                <?php else : ?>
                    <div class="versus">VS</div>
                <?php endif; ?>
                <div class="team-name">
                    <?php $field = $item->fields_by_id[7]; // Team 2 Name ?>
                    <?php echo $field->result; ?>
                </div>
                <div class="logo">
                    <?php if(@$item->fields_by_id[9]): ?>
                        <?php $field = $item->fields_by_id[9]; // Team 2 Logo ?>
                        <?php echo $field->result; ?>
                    <?php endif; ?>
                </div>
                <div class="location">
                    <?php $field = $item->fields_by_id[10]; // Match location ?>
                    <?php echo $field->result; ?>
                </div>
                <div class="va-view-wrap">
                    <a class="view-article" href="<?php echo JRoute::_($item->url);?>">view</a>
                </div>
            </div>    
<?php if($this->user->get('id')&&($item->controls)):?>			
    <ul class="admin-edit">
        <?php echo list_controls($item->controls);?>
    </ul>
<?php endif;?>
<?php endforeach;?>
</div>
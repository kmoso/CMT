<?php
/**
 * a component for Joomla! 1.7 - 2.5 CMS (http://www.joomla.org)
 * Author: Vadim Kozhukhov
 * Author Website: http://www.torbara.com/
 * @copyright Copyright (C) 2015 Torbara (http://www.tobara.com). All rights reserved.
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die('Restricted access'); ?>





	<?php foreach ($this->items AS $item): ?>
				
<div class="va-latest-wrap">
    <div class="uk-container uk-container-center">
        <div class='va-latest-top'>
            <h3>Latest <span>Results</span></h3>
            <div class="tournament">
                <?php $field = $item->fields_by_id[10]; // Match location ?>
                <?php echo $field->result; ?>
            </div>
            <div class="date">
                <?php $field = $item->fields_by_id[4]; // Date  ?>
                <?php echo JText::sprintf( JHTML::_('date', strtotime($field->value[0]), 'F d, Y | g:i a')); ?>
            </div>
        </div>
    </div>
    <div class="va-latest-middle uk-flex uk-flex-middle">
        <div class="uk-container uk-container-center">
            <div class="uk-grid uk-flex uk-flex-middle">
                <div class="uk-width-2-12 center">
                    <?php if(@$item->fields_by_id[8]): ?>
                        <?php $field = $item->fields_by_id[8]; // Team 1 Logo ?>
                        <?php echo $field->result; ?>
                    <?php endif; ?>
                </div>
                <div class="uk-width-3-12 name uk-vertical-align">
                    <div class="wrap uk-vertical-align-middle">
                        <?php $field = $item->fields_by_id[5]; // Team 1 Name ?>
                        <?php echo $field->result; ?>
                    </div>
                </div>
                <div class="uk-width-2-12 score">
                    <div class="title">score</div>
                    <div class="table">
                        <div class="left"><?php $field = $item->fields_by_id[42]; // Team 1 Score ?>
                            <?php echo $field->result; ?></div>
                        <div class="right"><?php $field = $item->fields_by_id[43]; // Team 2 Score ?>
                            <?php echo $field->result; ?></div>
                        <div class="uk-clearfix"></div>
                    </div>
                </div>
                <div class="uk-width-3-12 name alt uk-vertical-align">
                    <div class="wrap uk-vertical-align-middle">
                        <?php $field = $item->fields_by_id[7]; // Team 2 Name ?>
                        <?php echo $field->result; ?>
                    </div>
                </div>
                <div class="uk-width-2-12 center">
                    <?php if(@$item->fields_by_id[9]): ?>
                        <?php $field = $item->fields_by_id[9]; // Team 2 Logo ?>
                        <?php echo $field->result; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>                
<div class="uk-container uk-container-center">
        <div class="va-latest-bottom">
            <div class="uk-grid">
                <div class="uk-width-8-12 uk-container-center text">
                    <?php $field = $item->fields_by_id[20]; // About text ?>
                    <?php echo $field->result; ?>
                </div>
            </div>
            
            <?php if(JFactory::getApplication()->input->get('view')!="record"): ?>
                <div class="uk-grid">
                  <div class="uk-width-1-1">
                  <div class="btn-wrap uk-container-center">
                    <a class="read-more" href="<?php echo JRoute::_($item->url);?>">More Info</a>
                  </div>
                  </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
            

<?php endforeach;?>

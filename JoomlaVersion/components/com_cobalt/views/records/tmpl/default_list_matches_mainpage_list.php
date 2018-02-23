<?php
/**
 * a component for Joomla! 1.7 - 2.5 CMS (http://www.joomla.org)
 * Author: Vadim Kozhukhov
 * Author Website: http://www.torbara.com/
 * @copyright Copyright (C) 2015 Torbara (http://www.tobara.com). All rights reserved.
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die('Restricted access');
//JFactory::getDocument()->addScript(JURI::root(TRUE) . '/media/mint/js/moocountdown/SimpleCounter.js');
?>

<div class="uk-container uk-container-center">
<div class="uk-grid uk-grid-collapse">
    <div class="uk-width-medium-1-2 uk-width-small-1-1 va-single-next-match">
        <div class="va-main-next-wrap">
            <div class="main-next-match-title"><em>Siguientes <span>Partidos</span></em></div>
        <div class="match-list-single">
        <?php $item = $this->items[0]; ?>
        <div class="match-list-item">
            <div class="count">
                <?php
                $val = $item->fields_by_id[4]->value;
                rsort($val);
                $val = $val[0];

                $date = '<span class="countdown_date">' . $item->fields_by_id[4]->_getFormatted($val, true) . '</span>';
                $text = (strtotime($val) > time()) ? JText::sprintf('D_LEFT', $date) : JText::sprintf('D_PAST', $date);

                $format = $item->fields_by_id[4]->params->get('params.time', 0) ? '{D} {H} {M} {S}' : '{D}';
                $format = '{D} {H} {M} {S}';

                $diff = time() - strtotime($val);
                $days = $diff / 3600 / 24;
                $color = '';
                $type = 'normal';
                if ($days < 0) {
                    if (abs($days) <= $item->fields_by_id[4]->params->get('params.notify_days', 30)) {
                        $type = 'notify';
                    }
                } else {
                    $type = 'past';
                }
                if ($item->fields_by_id[4]->params->get('params.' . $type . '_color') != '')
                    $color = 'style=\'color: ' . $item->fields_by_id[4]->params->get('params.' . $type . '_color') . '\'';
                ?>

                <div id="countdown<?php echo $item->id; ?>"></div>

                <div class="clearfix"></div>

                <script type='text/javascript'>
                    new SimpleCounter("countdown<?php echo $item->id; ?>", <?php echo strtotime($val); ?>, {
                        'continue': 0,
                        format: '<?php echo $format ?>',
                        lang: {
                            d: {single: '<?php echo JText::_('D_COUNTDAY'); ?>', plural: '<?php echo JText::_('D_COUNTDAYS'); ?>'}, //days
                            h: {single: '<?php echo JText::_('D_COUNTHOUR'); ?>', plural: '<?php echo JText::_('D_COUNTHOURS'); ?>'}, //hours
                            m: {single: '<?php echo JText::_('D_COUNTMINUTE'); ?>', plural: '<?php echo JText::_('D_COUNTMINUTES'); ?>'}, //minutes
                            s: {single: '<?php echo JText::_('D_COUNTSECOND'); ?>', plural: '<?php echo JText::_('D_COUNTSECONDS'); ?>'}  //seconds
                        },
                        formats: {
                            full: "<span class='countdown_number' <?php echo $color; ?>>{number}</span> <span class='countdown_word' <?php echo $color; ?>>{word}</span> <span class='countdown_separator'>:</span>", //Format for full units representation
                            shrt: "<span class='countdown_number' <?php echo $color; ?>>{number}</span>" //Format for short unit representation
                        }
                    });
                </script>


            </div>
            <div class="clear"></div>
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
            <div class="versus">VS</div>

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
            <div class="clear"></div>
            <div class="date">
                <?php $field = $item->fields_by_id[4]; // Date  ?>
                <?php echo JText::sprintf( JHTML::_('date', strtotime($field->value[0]), 'F d, Y | g:i a')); ?>
            </div>
            <div class="clear"></div>
            <div class="location">
                <?php $field = $item->fields_by_id[10]; // Match location ?>
                <?php echo $field->result; ?>
            </div>
        </div>
            <?php unset($this->items[0]); ?>
        </div>
    </div>
    </div>
    <div class="uk-width-medium-1-2 uk-width-small-1-1 va-list-next-match">
        <div class="match-list-wrap">
            <?php foreach ($this->items AS $item): ?>

                <div class="match-list-item alt">    
                    <div class="date">
                        <?php $field = $item->fields_by_id[4]; // Date  ?>
                        <?php echo JText::sprintf( JHTML::_('date', strtotime($field->value[0]), 'F d, Y')); ?>
                    </div>
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
                        </div>
                    </div>
                </div>    

            <?php endforeach; ?>
        </div>
    </div>
</div>
</div>


<?php
/**
 * a component for Joomla! 1.7 - 2.5 CMS (http://www.joomla.org)
 * Author: Vadim Kozhukhov
 * Author Website: http://www.torbara.com/
 * @copyright Copyright (C) 2015 Torbara (http://www.tobara.com). All rights reserved.
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die('Restricted access'); 

var_dump(count($this->items));
?>

<?php foreach ($this->items AS $item): ?>

<article class="has-context <?php if($item->featured) {echo 'featured';}?>">
				
    <div class="latest-news-wrap">
        <div class="img-wrap">
            <?php $field = $item->fields_by_id[11]; // image ?>
            <?php echo $field->result; ?>
        </div>
        <div class="info">
            <div class="date">
                <?php echo JHtml::_('date', $item->created, 'F d, Y'); ?>
            </div>
            <div class="name">
                <h4>
                    <a <?php echo $item->nofollow ? 'rel="nofollow"' : '';?> href="<?php echo JRoute::_($item->url);?>">
                            <?php echo $item->title?>
                    </a>		
                </h4>
            </div>
        </div>
    </div>

</article>
		

<?php endforeach; ?>

			
<?php
/**
 * a component for Joomla! 1.7 - 2.5 CMS (http://www.joomla.org)
 * Author: Vadim Kozhukhov
 * Author Website: http://www.torbara.com/
 * @copyright Copyright (C) 2015 Torbara (http://www.tobara.com). All rights reserved.
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die('Restricted access');
$k = $p1 = 0;
$params = $this->tmpl_params['list'];
$core = array('type_id' => 'Type', 'user_id','','','','','','','','', );
JHtml::_('dropdown.init');
$exclude = $params->get('tmpl_params.field_id_exclude');
settype($exclude, 'array');
foreach ($exclude as &$value) {
	$value = $this->fields_keys_by_id[$value];
}

?>

<h3 class="other-post-title">Other <span>Posts</span></h3>

<div class="uk-grid" data-uk-grid-match>
<?php foreach ($this->items AS $item): ?>

<div class="uk-width-large-1-3 uk-width-medium-2-4 uk-width-small-2-4 list-article other uk-flex uk-flex-column">
    <div class="wrapper">
        <div class="img-wrap">
            <?php 
                $field = $item->fields_by_id[11]; // image
                echo $field->result;
            ?>
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

</div>

<?php endforeach; ?>
</div>



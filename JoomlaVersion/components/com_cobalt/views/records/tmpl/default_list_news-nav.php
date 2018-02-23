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


<div class="uk-grid" data-uk-grid-match>
<?php foreach ($this->items AS $item): ?>

<div class="uk-width-1-2 uk-panel list-article news-nav">

    <div class="name">
        <h4>
            <a <?php echo $item->nofollow ? 'rel="nofollow"' : '';?> href="<?php echo JRoute::_($item->url);?>">
                    <?php echo $item->title?>
            </a>		
        </h4>
    </div>

</div>

<?php endforeach; ?>
</div>



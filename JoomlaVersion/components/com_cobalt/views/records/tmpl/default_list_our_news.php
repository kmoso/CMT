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


$commentsAPI = JPATH_SITE . '/components/com_jcomments/jcomments.php';
if (file_exists($commentsAPI)) {
    require_once($commentsAPI);
}

?>

<div class="uk-container uk-container-center">
<div class="uk-grid uk-grid-collapse" data-uk-grid-match>
{modulepos our-news-info}
<?php foreach ($this->items AS $item): ?>

<article class="uk-width-large-1-2 uk-width-medium-1-1 uk-width-small-1-1 our-news-article" data-uk-grid-match>
        <?php 
                $field = $item->fields_by_id[11]; // gallery
                $src = (string) reset(simplexml_import_dom(DOMDocument::loadHTML($field->result))->xpath("//img/@src"));
                
            ?>
        <div class="img-wrap uk-cover-background uk-position-relative" style=" background-image: url(<?php echo $src; ?>);">
            
            
            <a href="<?php echo JRoute::_($item->url);?>"></a>
            <img class="uk-invisible" src="<?php echo $src; ?>" alt="" />
           
        </div>
        <div class="info">
            <div class="date">
                <?php echo JHtml::_('date', $item->created, 'M d, Y'); ?>
            </div>
            <div class="name">
                <h4>
                    <a <?php echo $item->nofollow ? 'rel="nofollow"' : '';?> href="<?php echo JRoute::_($item->url);?>">
                        <?php echo $item->title?>
                    </a>		
                </h4>
            </div>
            <div class="text">
                <?php $field = $item->fields_by_id[13]; // Article Text ?>
                <?php echo $field->result; ?>
                <div class="read-more"><a href="<?php echo JRoute::_($item->url);?>">Read More</a></div>
            </div>
        </div>

</article>

<?php endforeach; ?>
</div>
</div>
{modulepos all-news-btn}


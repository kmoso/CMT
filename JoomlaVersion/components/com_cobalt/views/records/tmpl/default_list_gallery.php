<?php
/**
 * a component for Joomla! 1.7 - 2.5 CMS (http://www.joomla.org)
 * Author: Vadim Kozhukhov
 * Author Website: http://www.torbara.com/
 * @copyright Copyright (C) 2015 Torbara (http://www.tobara.com). All rights reserved.
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die('Restricted access');
JHtml::_('jquery.framework');
JFactory::getDocument()->addScript(JURI::root(TRUE) . 'templates/sport.ak/js/isotope.pkgd.min.js');

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



<script>
jQuery(window).load(function(){
    colWidth = jQuery('.grid').width();
   
    $grid = jQuery('.grid');    
        $grid.isotope({
            // options
            itemSelector: '.grid-item',
            percentPosition: true,
            layoutMode: 'masonry',
            masonry: {
                columnWidth:  $grid.find('.grid-item')[1]
              }

        });  
   
    
    jQuery('.filter-button-group').on( 'click', 'button', function() {
        $grid.isotope({
            // options
            itemSelector: '.grid-item',
            percentPosition: true,
            layoutMode: 'masonry',
            masonry: {
                columnWidth:  $grid.find('.grid-item')[1]
              }

        }); 
        var filterValue = jQuery(this).attr('data-filter');
        jQuery(this).toggleClass('active').siblings().removeClass('active');
        $grid.isotope({ filter: filterValue });
    });
    
    
    
});
</script>

{module 149}

<div class="button-group filter-button-group" data-uk-sticky="{top:70}">
    <div class="uk-container uk-container-center">
        <button class="active" data-filter="*">all</button><?php
        $arr = array();
        foreach ($this->items AS $item){ 
            foreach ($item->categories as $cat) { $arr [] = $cat; }
        }
        $arr = array_unique($arr);

        foreach ($arr as $category) {
            ?><button data-filter="<?php echo ".tt_".md5($category); ?>"><?php echo $category; ?></button><?php
        }?>
    </div>    
</div>


<div class="uk-grid uk-grid-collapse grid" data-uk-grid-match>
<?php $count = 0; ?>
<?php foreach ($this->items AS $item): ?>
    
    <?php if($count==0) : ?>
    
        <div class="uk-width-1-1 uk-width-medium-1-2 uk-width-large-1-4 grid-item article-slider <?php foreach ($item->categories as $cat) { echo "tt_".md5($cat)." "; } ?>"> 
            <div class="uk-slidenav-position" data-uk-slideshow="{height:300}">
                <ul class="uk-slideshow">
                <?php $field = $item->fields_by_id[23]; // images ?>
                <?php 
                    $dir = JComponentHelper::getParams('com_cobalt')->get('general_upload') . DIRECTORY_SEPARATOR . $field->params->get('params.subfolder', $field->field_type) . DIRECTORY_SEPARATOR;
                    foreach($field->value as $picture_index => $file) {
                        $url = $dir . $file['fullpath'];
                        ?><li>
                            <img class="uk-responsive-height" src="<?php echo $url; ?>" alt="">
                            <div class="titles">
                                <div class="sub-title">
                                    <?php $field = $item->fields_by_id[25]; // Sub-Title ?>
                                    <?php echo $field->result; ?>
                                </div>
                            </div>
                        </li><?php
                    }
                 ?>
            </ul>
            <div class="article-slider-btn">
                <a href="" class="uk-slidenav uk-slidenav-contrast uk-slidenav-previous" data-uk-slideshow-item="previous"></a>
                <a href="" class="uk-slidenav uk-slidenav-contrast uk-slidenav-next" data-uk-slideshow-item="next"></a>
            </div>
        </div>    
            <?php if($this->user->get('id')&&($item->controls)):?>			
                <ul class="admin-edit">
                    <?php echo $item->controls[0];?>
                </ul>
            <?php endif;?>
            
        </div>
    
    <?php else : ?>
    
        <div class="uk-width-1-1 uk-width-medium-1-2 uk-width-large-1-4 grid-item <?php foreach ($item->categories as $cat) { echo "tt_".md5($cat)." "; } ?>">
            <div class="gallery-album"> 
                <?php $field = $item->fields_by_id[23]; // images ?>
                <?php echo $field->result; ?>
                <div class="titles">
                    <div class="title">
                        <?php echo $item->title; ?>
                    </div>
                    <div class="sub-title">
                        <?php $field = $item->fields_by_id[25]; // Sub-Title ?>
                        <?php echo $field->result; ?>
                    </div>
                </div>
            </div>
            
            <?php if($this->user->get('id')&&($item->controls)):?>			
                <ul class="admin-edit">
                    <?php echo $item->controls[0];?>
                </ul>
            <?php endif;?>
            
        </div>
    
    <?php endif; ?>
    <?php $count++;?>
<?php endforeach; ?>
</div>



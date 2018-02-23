<?php
/**
 * a component for Joomla! 1.7 - 2.5 CMS (http://www.joomla.org)
 * Author Website: http://www.torbara.com/
 * @copyright Copyright (C) 2015 Torbara (http://www.torbara.com). All rights reserved.
 * @license   GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die();

if(empty($this->value)) { return null; }


$key = $this->id . '-' . $record->id;
$dir = JComponentHelper::getParams('com_cobalt')->get('general_upload') . DIRECTORY_SEPARATOR . $this->params->get('params.subfolder', $this->field_type) . DIRECTORY_SEPARATOR;
?>

<div class="uk-slidenav-position" data-uk-slider>
    <div class="uk-slider-container">
        <div class="big-title">Photo <span>stadium</span></div>
        <div class="match-slider-btn">
            <a href="" class="uk-slidenav uk-slidenav-previous" data-uk-slider-item="previous"></a>
            <a href="" class="uk-slidenav uk-slidenav-next" data-uk-slider-item="next"></a>
        </div>
        <ul class="uk-slider uk-grid uk-grid-width-1-3">
            <?php
            foreach($this->value as $picture_index => $file) {
                $url = $dir . $file['fullpath'];
                ?><li><img class="uk-responsive-height" src="<?php echo $url; ?>" alt=""></li><?php
            } ?>
        </ul>
        
    </div>
</div>
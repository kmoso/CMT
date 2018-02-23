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

<div id="carusel-<?php echo $key ?>" class="uk-slidenav-position" data-uk-slideshow="{ height : 510 }">
    <ul class="uk-slideshow">
        <?php
        foreach($this->value as $picture_index => $file) {
            $url = $dir . $file['fullpath'];
            ?><li><img src="<?php echo $url; ?>" alt=""></li><?php
        } ?>
    </ul>
    <div class="article-slider-btn">
        <a href="" class="uk-slidenav uk-slidenav-contrast uk-slidenav-previous" data-uk-slideshow-item="previous"></a>
        <a href="" class="uk-slidenav uk-slidenav-contrast uk-slidenav-next" data-uk-slideshow-item="next"></a>
    </div>
</div>
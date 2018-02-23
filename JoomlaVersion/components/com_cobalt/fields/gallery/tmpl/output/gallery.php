<?php
/**
 * Cobalt by MintJoomla
 * a component for Joomla! 1.7 - 2.5 CMS (http://www.joomla.org)
 * Author Website: http://www.mintjoomla.com/
 * @copyright Copyright (C) 2012 MintJoomla (http://www.mintjoomla.com). All rights reserved.
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die();

if(empty($this->value)) {
    return null;
}

$this->record = @$record;
$this->_init();


$key = $this->id . '-' . $record->id;
$dir = JComponentHelper::getParams('com_cobalt')->get('general_upload') . DIRECTORY_SEPARATOR . $this->params->get('params.subfolder', $this->field_type) . DIRECTORY_SEPARATOR;
foreach ($this->value as $f) {
    $array_keys[] = $f['filename'];
}
$array_keys = array_flip($array_keys);

if ($this->params->get('params.thumbs_list_random', 1)) {
    shuffle($this->value);
}

$rel = '';
if ($this->params->get('params.lightbox_click_list', 0) == 0)
{
	$rel = 'data-lightbox="' . $this->id . '_' . $this->record->id.'"';
	if ($this->params->get('params.show_mode', 'gallerybox') == 'gallerybox')
	{
		$rel = 'rel="gallerybox' . $this->id . '_' . $this->record->id.'"';
	}
	if ($this->params->get('params.show_mode', 'gallerybox') == 'rokbox')
	{
		$rel = 'data-rokbox data-rokbox-album="'.htmlentities($this->record->title, ENT_COMPAT, 'UTF-8').'"';
	}
}


$i = 0; $out2 = $out = array();
foreach($this->value as $picture_index => $file)
{
	$picture = $dir . $file['fullpath'];
	$url     = CImgHelper::getThumb($picture, $this->params->get('params.thumbs_list_width', 100), $this->params->get('params.thumbs_list_height', 100), 'gallery' . $key, $record->user_id,
		array(
			 'mode'       => $this->params->get('params.thumbs_list_mode', 6),
			 'strache'    => $this->params->get('params.thumbs_list_stretch', 1),
			 'background' => $this->params->get('params.thumbs_background_color', "#000000"),
			 'quality'    => $this->params->get('params.thumbs_list_quality', 80)
		));
	if($rel)
	{
		$url_orig =  CImgHelper::getThumb($picture, $this->params->get('params.full_width', 100), $this->params->get('params.full_height', 100), 'gallery' . $key, $record->user_id,
			array(
				 'mode'       => $this->params->get('params.full_mode', 6),
				 'strache'    => $this->params->get('params.full_stretch', 1),
				 'background' => $this->params->get('params.thumbs_background_color', "#000000"),
				 'quality'    => $this->params->get('params.full_quality', 80)
			));
		if($i <= 2) {
			$out[] = '<a href="'.$url_orig.'" '.$rel.' class="img-'.$array_keys[$file['filename']].'"><img src="'.$url.'" alt=""></a>';
		} else {
			$out2[] = '<a href="'.$url_orig.'" '.$rel.' class="img-'.$array_keys[$file['filename']].'"><img src="'.$url.'" alt=""></a>';
		}
	}
	else
	{
		if($i <= 2) {
			$out[] = '<a href="'.$record->url.'" class="image-wrapper-thumb"><img src="'.$url.'" class="img-polaroid" alt=""></a>';
		}
	}
	$i++;
}
?>

	<?php echo implode('', $out);?>

<?php if ($out2) : ?>
	<div style="display:none;"><?php echo implode('', $out2);?></div>
<?php endif; ?>

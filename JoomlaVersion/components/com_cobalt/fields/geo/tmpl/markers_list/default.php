<?php
/**
 * by MintJoomla
 * a component for Joomla! 3.x CMS (http://www.joomla.org)
 * Author Website: http://www.mintjoomla.com/
 * @copyright Copyright (C) 2007-2014 MintJoomla (http://www.mintjoomla.com). All rights reserved.
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die('Restricted access');

$db = JFactory::getDbo();

$query = $db->getQuery(TRUE);
$query->select('value_index, field_value, record_id');
$query->from('#__js_res_record_values');
$query->where("value_index IN('lat', 'lng', 'marker')");
$query->where('section_id = ' . $this->request->getInt('section_id'));

//if($this->request->getInt('markers', 0))
{
	$section        = ItemsStore::getSection($this->request->getInt('section_id'));
	$model          = JModelLegacy::getInstance('Records', 'CobaltModel');
	$model->section = $section;
	$model->total   = TRUE;

	$sql = $model->getListQuery(TRUE);
	$db->setQuery($sql);
	$ids   = $db->loadColumn();
	$ids[] = 0;

	$query->where("record_id IN (" . implode(',', $ids) . ")");
}

$db->setQuery($query);

$list = $db->loadObjectList();
$out  = array();
foreach($list as $position)
{
	if($position->value_index === 0)
	{
		//continue;
	}

	$val = $position->value_index == 'marker' ?
		JUri::root() . 'components/com_cobalt/fields/geo/markers/' . $this->params->get('params.map_icon_src.dir', 'custom') . '/' . $position->field_value :
		(float)$position->field_value;

	$out[$position->record_id][$position->value_index] = $val;
}

foreach($out as $record => $value)
{
	if(empty($value['marker']))
	{
		$out[$record]['marker'] = JUri::root() . 'components/com_cobalt/fields/geo/markers/' . $this->params->get('params.map_icon_src.dir', 'custom') . '/' . $this->params->get('params.map_icon_src.icon');
	}
}

return $out ? $out : 1;
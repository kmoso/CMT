<?php
/**
 * Cobalt by MintJoomla
 * a component for Joomla! 1.7 - 2.5 CMS (http://www.joomla.org)
 * Author Website: http://www.mintjoomla.com/
 * @copyright Copyright (C) 2012 MintJoomla (http://www.mintjoomla.com). All rights reserved.
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die();

jimport('joomla.application.component.modellist');

class CobaltModelElements extends JModelList
{

	
	public function getArt()
	{
		$user = JFactory::getUser();
		
		$db = JFactory::getDbo();
		
		$query = $db->getQuery(true);
		$query->select('r.id, r.title, r.alias, r.section_id, 1 as subscribed');
		$query->from('#__js_res_subscribe AS s');
		$query->leftJoin('#__js_res_record AS r ON r.id = s.ref_id');
		$query->where("`type` = 'record'");
		$query->where('s.user_id = '.$user->id);
		
		$db->setQuery($query, $this->getStart(), $this->getState('list.limit'));
		
		return $db->loadObjectList();
	}
	
}
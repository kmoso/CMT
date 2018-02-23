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

class CobaltModelnotifications extends JModelList
{

	protected function populateState($ordering = null, $direction = null)
	{
		$app = JFactory::getApplication();
	
		$show_new = $app->getUserStateFromRequest($this->context.'.notifications.show_new', 'show_new', $app->input->getInt('show_new'), 'int');
		$this->setState('notifications.show_new', $show_new);			
		
		$event = $app->getUserStateFromRequest($this->context.'.notifications.event', 'event', $app->input->getInt('event'), 'string');
		$this->setState('notifications.event', $event);			

		$search = $app->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
		$this->setState('filter.search', $search);
		
		$section = $app->getUserStateFromRequest($this->context.'.notifications.section_id', 'section_id', $app->input->getInt('section_id'), 'int');
		$this->setState('notifications.section_id', $section);	
		
		parent::populateState('m.ctime', 'desc');
	}

	public function getTable($type = 'Notification', $prefix = 'CobaltTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	public function getListQuery()
	{
		$user = JFactory::getUser();
		$db = $this->getDbo();
		
		$section_id = $this->state->get('notifications.section_id');
		$show_new = $this->state->get('notifications.show_new');
		$event = $this->state->get('notifications.event', 0);
		$search = $this->getState('filter.search', 0);
		
		$query = $db->getQuery(true);
		$sql = $db->getQuery(true);
		
		$query->select('*, TO_DAYS(CURRENT_DATE) - TO_DAYS(ctime) as days');
		$query->from('#__js_res_notifications');
		
		$query->where('user_id = ' . $user->id);
		if($show_new)
			$query->where('state_new = 1' );
		if($section_id)
			$query->where('ref_2 = '.$section_id);
		if($event)
			$query->where("type = '".$event."'");
		
		$sql = $query;
		if($search)
		{
			if(strstr($search, 'secid:'))
			{
				$query->where('ref_2 = '.str_replace('secid:', '', $search));
			}
			else if(strstr($search, 'rid:'))
			{
				$query->where('ref_1 = '.str_replace('rid:', '', $search));
			}
			else if(strstr($search, 'uid:'))
			{
				$query->where("eventer = ".str_replace('uid:', '', $search));
			}
			else 
			{
				$where[] = "ref_1 IN (SELECT id FROM #__js_res_record WHERE title LIKE '%$search%')";
				$where[] = "ref_2 IN (SELECT id FROM #__js_res_sections WHERE name LIKE '$search%')";
				
				$query->where(implode(' OR ', $where));
			}
		}
		$query->order('ctime DESC');
		

		return $query;
	}
	
}
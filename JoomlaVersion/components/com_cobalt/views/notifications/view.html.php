<?php
/**
 * Cobalt by MintJoomla
 * a component for Joomla! 1.7 - 2.5 CMS (http://www.joomla.org)
 * Author Website: http://www.mintjoomla.com/
 * @copyright Copyright (C) 2012 MintJoomla (http://www.mintjoomla.com). All rights reserved.
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die();

jimport('joomla.application.component.view');

class CobaltViewNotifications extends JViewLegacy
{

	function display($tpl = null)
	{

		$model = JModelLegacy::getInstance('Notification', 'CobaltModel');
		$this->state = $this->get('State');

		$this->section_id = $this->state->get('notifications.section_id');
		$this->section = ItemsStore::getSection($this->section_id);

		$this->items = $this->get('Items');

		$this->pagination = $this->get('Pagination');

		$this->sections_list = array();

		$today = $yesterday = $thisweek = $lastweek = $older = array();
		$records = array();
		$users = array();
		$event_types = array();
		$num_sections = array();
		$new = 0;
		foreach($this->items as $i => $item)
		{
			if(!isset($records[$item->ref_1])) $records[$item->ref_1] = 0;
			if(!isset($users[$item->eventer])) $users[$item->eventer] = 0;
			if(!isset($event_types[$item->type])) $event_types[$item->type] = 0;
			if(!isset($num_sections[$item->ref_2])) $num_sections[$item->ref_2] = 0;

			$item->params = new JRegistry($item->params);
			$item->html = CEventsHelper::get_notification($item);
			$item->date = JHtml::_('date', $item->ctime, $this->section->params->get('events.event_date_format', $this->section->params->get('events.event_date_custom', 'd M Y')));

			if ($item->days < 0)
				$item->days = 0;

			if ($item->days == 0)
			{
				$today[] = $item;
			}
			elseif ($item->days == 1)
			{
				$yesterday[] = $item;
			}
			elseif ($item->days > 1 && $item->days < 7)
			{
				$thisweek[] = $item;
			}
			elseif ($item->days >= 7 && $item->days < 14)
			{
				$lastweek[] = $item;
			}
			else
			{
				$older[] = $item;
			}
			$records[$item->ref_1] ++;
			$users[$item->eventer] ++;
			$event_types[$item->type] ++;
			$num_sections[$item->ref_2] ++;
			if($item->state_new)
				$new++;
		}

		$this->records = $records;//array_keys($records);
		$this->users = $users;//array_keys($users);
		$this->num_sections = $num_sections;//array_keys($users);
		$event_types1 = $event_types;
		$event_types = array_keys($event_types);

		$this->sort_items = array('today' => $today, 'yesterday' => $yesterday, 'thisweek' => $thisweek, 'lastweek' => $lastweek, 'older' => $older);

		$list['sections'] = '';
		//$sections = $model->getSections();
		$sections = array_keys($num_sections);

		if (count($sections) > 0)
		{
			$options = array();
			$options[] = JHTML::_('select.option', '0', JText::_('CSELECTSECTION'));
			foreach($sections as $type)
			{
				$type = ItemsStore::getSection($type);
				$options[] = JHTML::_('select.option', $type->id, $type->name);

			}
			$list['sections'] = JHtml::_('select.genericlist', $options, 'section_id', ' onchange="this.form.submit();"', 'value', 'text', $this->section_id);
		}
		else if (count($sections) == 1)
		$this->section_id = $sections[0];

		$this->sections = $sections;

		$options = array();
		$list['show_new'] = '';
		if($new)
		{
			$options[] = JHTML::_('select.option', '0', JText::_('CSHOWALLNTF'));
			$options[] = JHTML::_('select.option', '1', JText::_('CSHOWUNREADNTF'));

			$list['show_new'] = JHtml::_('select.genericlist', $options, 'show_new', 'onchange="this.form.submit();"', 'value', 'text', $this->state->get('notifications.show_new'));
		}

		$events = CEventsHelper::getEventsList();
		$show_events = array();
		$options = array();
		$options[] = JHTML::_('select.option', '0', JText::_('CSHOWALLNTFTYPES'));
		foreach ($events as $event => $title)
		{
			if(in_array($event, $event_types))
			{
				$options[] = JHTML::_('select.option', $event, $title);
				$show_events[$event] = $title.' <span class="badge">'.$event_types1[$event].'</span>';
			}
		}
		$list['events'] = JHtml::_('select.genericlist', $options, 'event', 'onchange="this.form.submit();"', 'value', 'text', $this->state->get('notifications.event'));

		$list['clear_list'] = $show_events;

		$this->list = $list;

		parent::display($tpl);
	}

}
?>
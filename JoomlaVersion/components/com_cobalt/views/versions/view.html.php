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
JHtml::addIncludePath(JPATH_COMPONENT . DIRECTORY_SEPARATOR . 'library' . DIRECTORY_SEPARATOR . 'php');

class CobaltViewVersions extends JViewLegacy
{

	function display($tpl = null)
	{
		$doc = JFactory::getDocument();
		$user = JFactory::getUser();
		$app = JFactory::getApplication();

		$model = JModelLegacy::getInstance('Auditversions', 'CobaltModel');
		$this->state = $model->getState();
		$record_id = JFactory::getApplication()->input->getInt('record_id');
		if(!$record_id)
		{
			JError::raiseWarning(404, JText::_('NORECORDID'));

			return;
		}

		$this->record = $model->record = ItemsStore::getRecord($record_id);
		$this->section = ItemsStore::getSection($this->record->section_id);
		$record_type = ItemsStore::getType($this->record->type_id);

		$items = $model->getItems();


		foreach ($items as $k => $item)
		{
			$items[$k]->date = JHtml::_('date', $item->ctime, $record_type->params->get('audit.audit_date_format', $record_type->params->get('audit.audit_date_custom', 'd M Y h:i:s')));
		}

		if(!count($items))
		{
			JError::raiseWarning(403, JText::_('CERR_NOVERSIONS'));
			return;
		}

		$this->items = $items;
		$this->pagination = $model->getPagination();

		$this->_prepareDocument();

		parent::display($tpl);
	}

	private function _prepareDocument()
	{
		$app = JFactory::getApplication();
		$doc = JFactory::getDocument();
		$menus = $app->getMenu();
		$menu = $menus->getActive();
		$this->appParams = $app->getParams();
		$pathway = $app->getPathway();

		$title = null;
		$path = array();

		if($menu)
		{
			$title = $menu->params->get('page_title', $menu->title);
			$this->appParams->def('page_heading', $title);
		}

		if($this->record->title)
		{
			$title = JText::_('CAUDITVERSIONS').' : '.$this->record->title.' v. '.$this->record->version;
		}
		$path = array(array('title' => $title, 'link' => ''));
		$path[] = array('title' => $this->section->name, 'link' => JRoute::_(Url::records($this->section)));

		if(empty($title))
		{
			$title = $app->getCfg('sitename');
		}
		elseif($app->getCfg('sitename_pagetitles', 0) == 1)
		{
			$title = JText::sprintf('JPAGETITLE', $app->getCfg('sitename'), $title);
		}
		elseif($app->getCfg('sitename_pagetitles', 0) == 2)
		{
			$title = JText::sprintf('JPAGETITLE', $title, $app->getCfg('sitename'));
		}

		$doc->setTitle($title);

		$path = array_reverse($path);

		foreach($path as $item)
		{
			$pathway->addItem($item['title'], $item['link']);
		}
	}
}
?>
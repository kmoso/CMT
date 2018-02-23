<?php
/**
 * Cobalt by MintJoomla
 * a component for Joomla! 1.7 - 2.5 CMS (http://www.joomla.org)
 * Author Website: http://www.mintjoomla.com/
 * @copyright Copyright (C) 2012 MintJoomla (http://www.mintjoomla.com). All rights reserved.
 * @license   GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

class CobaltModelImport extends JModelList
{
	public function getPresets()
	{
		$this->_db->setQuery("SELECT id as value, name as text FROM #__js_res_import WHERE section_id = " .
			JFactory::getApplication()->input->get('section_id') . " AND user_id = " . JFactory::getUser()->get('id', 0));

		return $this->_db->loadObjectList();
	}

	public function getPreset()
	{
		$this->_db->setQuery("SELECT * FROM #__js_res_import WHERE id = " . (int)JFactory::getApplication()->input->get('preset'));
		$preset = $this->_db->loadObject();

		if(!@preset)
		{
			return NULL;
		}

		@$preset->params = new JRegistry(@$preset->params);

		return $preset;
	}
}

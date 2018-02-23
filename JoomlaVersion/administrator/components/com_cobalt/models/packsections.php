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

class CobaltBModelPacksections extends JModelList
{
	public function __construct($config = array())
	{
		if (empty ( $config ['filter_fields'] ))
		{
			$config ['filter_fields'] = array ('id', 'ctime');
		}
		$this->option = 'com_cobalt';
		parent::__construct ( $config );
	}

	protected function populateState($ordering = null, $direction = null)
	{
		$app = JFactory::getApplication('administrator');
		$pack = $app->getUserStateFromRequest($this->context . '.pack', 'filter_pack', '', 'int');
		$this->setState('pack', $pack);

		parent::populateState();
	}

	protected function getStoreId($id = '')
	{
		$id .= ':' . $this->getState('pack');

		return parent::getStoreId($id);
	}

	protected function getListQuery()
	{
		$db		= $this->getDbo();
		$query	= $db->getQuery(true);

		$query->select('ps.*');
		$query->from('#__js_res_packs_sections AS ps');

		$query->select('s.name');
		$query->leftJoin('#__js_res_sections AS s ON s.id = ps.section_id');

		if($pack = $this->getState('pack'))
		{
			$query->where('ps.pack_id = ' . $query->quote($pack));
		}

		$orderCol	= $this->state->get('list.ordering', 'id');
		$orderDirn	= $this->state->get('list.direction', 'asc');
		$query->order($db->escape($orderCol.' '.$orderDirn));

		return $query;
	}

	public function getPackSectoins($pack_id = null)
	{
		if(!$pack_id)
		{
			return false;
		}

		$db		= $this->getDbo();
		$query	= $db->getQuery(true);

		$query->select('ps.*');
		$query->from('#__js_res_packs_sections AS ps');
		$query->where('pack_id = '.$pack_id);
		$db->setQuery($query);
		$result = $db->loadObjectList('section_id');

		foreach ($result as $res) {
			$res->params = new JRegistry($res->params);
		}

		return $result;
	}

}

<?php
/**
 * Cobalt by MintJoomla
 * a component for Joomla! 1.7 - 2.5 CMS (http://www.joomla.org)
 * Author Website: http://www.mintjoomla.com/
 * @copyright Copyright (C) 2012 MintJoomla (http://www.mintjoomla.com). All rights reserved.
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

class CobaltModelFields extends JModelList
{
	public function populateState($ordering = null, $direction = null)
	{
		parent::populateState($ordering = null, $direction = null);
	}
	public function getListQuery()
	{

		$this->setState('list.start', 0);
		$this->setState('list.limit', 1009);

		$typeId = $this->getState('fields.type_id', JFactory::getApplication()->input->getInt('type_id'));

		if(!$typeId)
		{
			$this->setError('Fields: Type not set');
			return FALSE;
		}

		$this->setState('list.start', 0);
		$this->setState('list.limit', 1000);
		$db = $this->getDbo();

		$ord  = $db->getQuery(true);
		$ord->select('ordering');
		$ord->from('#__js_res_fields_group');
		$ord->where('id = f.group_id');
		
		$query = $db->getQuery(true);
		$query->select('f.id as fid, f.*');
		$query->select('g.id as gid, g.title as group_title, g.description AS group_descr, g.icon AS group_icon');
		$query->leftJoin('#__js_res_fields_group AS g ON g.id = f.group_id');
		$query->select('('.$ord.') AS gordering');
		$query->from('#__js_res_fields AS f');

		$query->where('f.type_id = '.$typeId);
		$query->where('f.published = 1');

		if($module_field = JFactory::getApplication()->input->getVar('module_video'))
		{
			settype($module_field, 'array');
			$query->where("f.id IN (".implode(',', $module_field).")");
		}
		
		$query->order('gordering ASC');
		$query->order('f.ordering ASC');

 		//echo nl2br(str_replace('#_', 'jos', $query));
		return $query;
	}

	public function getFieldTypeId($id)
	{
		static $cache = array();

		if(isset($cache[$id]))
		{
			return $cache[$id];
		}

		$this->_db->setQuery("SELECT type_id FROM #__js_res_fields WHERE id = {$id}");
		$cache[$id] = $this->_db->loadResult();

		return $cache[$id];
	}
	public function getTotal()
	{
		return 100000;
	}
	public function getStoreId($id = NULL)
	{
		/*if(!$id)
		{
			$id	.= ':'.implode(',', JFactory::getApplication()->getUserStateFromRequest('com_cobalt.fields.skipers', 'skip_fields', '', 'array'));
		}*/

		$id .= ':'.$this->getState('fields.type_id');
		$id	.= ':'.$this->getState('list.start');
		$id	.= ':'.$this->getState('list.ordering');
		$id	.= ':'.$this->getState('list.direction');
		if(JFactory::getApplication()->input->get('skipfield'))
		{
			$id	.= ':'.JJFactory::getApplication()->input->get('skipfield');
		}


		return md5($this->context.':'.$id);
	}

	// TODO as getRecordFields
	public function getFormFields($typeId = null, $itemId = NULL, $cache = TRUE, $fields = null)
	{

        static $out = array();


		if(isset($out[$typeId][$itemId]) && ($cache == TRUE)){
			return $out[$typeId][$itemId];
		}

		$this->setState('fields.type_id', $typeId);
		$items = $this->getItems();
		
		$f = $fields = array();

		if($itemId && !$fields)
		{
			$db = JFactory::getDbo();
			$db->setQuery("SELECT `fields` FROM `#__js_res_record` WHERE `id` = {$itemId}");
			$json = $db->loadResult();
			$fields = json_decode($json, true);
		}
		
		$data = JFactory::getApplication()->getUserState('com_cobalt.edit.form.data', array());

		if(!empty($items))
		{
			foreach ($items as $key => $item)
			{
				$file = JPATH_ROOT. DIRECTORY_SEPARATOR .'components/com_cobalt/fields/'.$item->field_type.'/'.$item->field_type.'.php';
				if(!JFile::exists($file))
				{
					JError::raiseWarning(404, JText::sprintf("CFIELDNOTFOUND", $item->field_type));
					continue;
				}
				require_once $file;
				
				if(isset($data['fields'][$item->id]))
				{
					$default = $data['fields'][$item->id];
				}
				else
				{
					$default = @$fields[$item->id];
				}

				$name = 'JFormFieldC'.ucfirst($item->field_type);
				$f[$item->id] = new $name($item, $default);
				$f[$item->id]->isnew = ($itemId == null);
			}
		}
		if($cache == TRUE)
		{
			$out[$typeId][$itemId] = $f;
		}

		return $f;
	}

	public function getRecordFields($item, $client='full', $cache = TRUE)
	{
		static $fields = array(), $params = array(), $items = array();
		$hide = array();

		$storeid = $this->getStoreId($item->id);
		
		if(isset($fields[$storeid]) && ($cache == TRUE)){
			return $fields[$storeid];
		}

		$fields[$storeid] = array();

		$form_model = JModelLegacy::getInstance('Form', 'CobaltModel');

		$record_fields = json_decode($item->fields, true);

		$this->setState('fields.type_id', $item->type_id);

		if(!isset($items[$item->type_id]))
		{
			$items[$item->type_id] = $this->getItems();
		}

		foreach ($items[$item->type_id] as $key => $field)
		{
			//echo $item->group_icon;
			if(!isset($params[$field->id]))
			{
				$params[$field->id] = new JRegistry($field->params);
			}
			if($client == 'feed' && !$params[$field->id]->get('core.show_feed', 0)) continue;
			if($client == 'full' && !$params[$field->id]->get('core.show_full', 0)) continue;
			if($client == 'list' && !$params[$field->id]->get('core.show_intro', 0)) continue;

			$file = JPATH_ROOT. DIRECTORY_SEPARATOR .'components/com_cobalt/fields/'.$field->field_type.'/'.$field->field_type.'.php';
			if(!JFile::exists($file))
			{
				JError::raiseWarning(404, JText::sprintf("CFIELDNOTFOUND", $field->field_type));
				continue;
			}
			require_once $file;

			$name = 'JFormFieldC'.ucfirst($field->field_type);
			$fields[$storeid][$field->id] = new $name($field, @$record_fields[$field->id]);
			$add_hide = $fields[$storeid][$field->id]->hideOthers($client);
			$hide = array_merge($hide, $add_hide);
		}
		
		foreach ($hide AS $id)
		{
			if(isset($fields[$storeid][$id]))
			{
				unset($fields[$storeid][$id]);
			}
		}

		return $fields[$storeid];
	}

	public function getField($field_id, $type_id, $record_id = NULL)
	{
		$fields = $this->getFormFields($type_id, $record_id);
		return $fields[$field_id];
	}

	public function getItems()
	{
		// Get a storage key.
		//$store = $this->getStoreId();

		// Try to load the data from internal storage.

		// Load the list items.
		$query = (string)$this->_getListQuery();

		if (isset($this->cache[md5($query)]))
		{
			return $this->cache[md5($query)];
		}

		try
		{
			$items = $this->_getList($query, $this->getStart(), $this->getState('list.limit'));
		}
		catch (RuntimeException $e)
		{
			$this->setError($e->getMessage());
			return false;
		}

		// Add the items to the internal cache.
		$this->cache[md5($query)] = $items;

		return $this->cache[md5($query)];
	}
}

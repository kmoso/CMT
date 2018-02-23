<?php
/**
 * Cobalt by MintJoomla
 * a component for Joomla! 1.7 - 2.5 CMS (http://www.joomla.org)
 * Author Website: http://www.mintjoomla.com/
 *
 * @copyright Copyright (C) 2012 MintJoomla (http://www.mintjoomla.com). All rights reserved.
 * @license   GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */

defined('_JEXEC') or die('Restricted access');

jimport('joomla.table.table');
jimport('legacy.access.rules');
jimport('joomla.access.rules');
jimport('joomla.filter.input');

class CobaltTableRecord extends JTable
{
	public function __construct(&$db)
	{
		parent::__construct('#__js_res_record', 'id', $db);
		$this->_trackAssets = FALSE;
	}

	protected function _getAssetName()
	{
		return 'com_cobalt.record.' . (int)$this->id;
	}

	protected function _getAssetTitle()
	{
		return $this->title;
	}


	public function bind($array, $ignore = '')
	{
		if(isset($array['rules']) && is_array($array['rules']))
		{
			$rules = new JAccessRules($array['rules']);
		}
		else
		{
			$rules = new JAccessRules(array());
		}
		$this->setRules($rules);

		return parent::bind($array, $ignore);
	}

	public function onFollow()
	{
		$this->_db->setQuery("SELECT COUNT(*) FROM #__js_res_subscribe WHERE ref_id = {$this->id} and `type` = 'record'");
		$this->_db->execute();
		$this->subscriptions_num = $this->_db->loadResult();
		$this->store();
	}

	public function onBookmark()
	{
		$this->_db->setQuery("SELECT COUNT(*) FROM #__js_res_favorite WHERE record_id = " . $this->id);
		$this->_db->execute();
		$this->favorite_num = $this->_db->loadResult();
		$this->store();
	}

	public function onRepost()
	{
		$this->_db->setQuery("SELECT host_id FROM `#__js_res_record_repost` WHERE record_id = " . $this->id);
		$this->_db->execute();
		$this->repostedby = json_encode($this->_db->loadColumn());
		$this->store();
	}

	public function check()
	{
		$isNew = (boolean)empty($this->id);
		$user = JFactory::getUser();
		$app = JFactory::getApplication();

		if($this->checked_out && $user->get('id') != $this->checked_out)
		{
			$this->setError(JText::sprintf('CANNOTEDITCHECKOUT', CCommunityHelper::getName($this->checked_out, $this->section_id), CCommunityHelper::getName($this->checked_out, $this->section_id)));

			return FALSE;
		}
		if(trim($this->title) == '')
		{
			$this->setError(JText::_('MUSTCONTAINTITLE'));

			return FALSE;
		}
		if(!$this->ip)
		{
			$this->ip = $_SERVER['REMOTE_ADDR'];
		}


		if(!$this->langs)
		{
			$lang = JFactory::getLanguage();
			$this->langs = $lang->getTag();
		}

		if($this->ctime == '' || $this->ctime == '0000-00-00 00:00:00')
		{
			$this->ctime = JFactory::getDate()->toSql();
		}

		if($this->inittime == '' || $this->inittime == '0000-00-00 00:00:00')
		{
			$this->inittime = JFactory::getDate()->toSql();
		}

		$this->mtime = JFactory::getDate()->toSql();


		if(!$this->user_id && $isNew)
		{
			$this->user_id = (int)$user->get('id');
		}

		if($app->input->getInt('parent_id'))
		{
			$this->parent_id = $app->input->getInt('parent_id');
			$this->parent = $app->input->get('parent', 'com_cobalt');
		}

		$section = JModelLegacy::getInstance('Section', 'CobaltModel')->getItem($app->input->getInt('section_id', $this->section_id));
		$type = JModelLegacy::getInstance('Form', 'CobaltModel')->getRecordType($this->type_id);
		$fields_list = JModelLegacy::getInstance('Fields', 'CobaltModel')->getFormFields($type->id);

		$post = JArrayHelper::getValue($_POST, 'jform', array(), 'array');

		$fields = @$post['fields'];
		settype($fields, 'array');

		$out = array();
		if($this->id)
		{
			$record = JTable::getInstance('Record', 'CobaltTable');
			$record->load($this->id);
			$out = json_decode($record->fields, TRUE);
		}

		$out_fieldsdata = array();
		$task = $app->input->get('task');
		foreach($fields_list as $field)
		{
			if($task == 'save2copy' || $task == 'copy')
			{
				$value = $field->onCopy(@$fields[$field->id], $this, $type, $section, $field);
			}
			else
			{
				// MJTODO check value overwrite
				$value = CensorHelper::cleanText(@$fields[$field->id]);
			}

			if((!$this->id && in_array($field->params->get('core.field_submit_access'), $user->getAuthorisedViewLevels())) ||
				($this->id && in_array($field->params->get('core.field_edit_access'), $user->getAuthorisedViewLevels()))
			)
			{
				$out[$field->id] = $field->onPrepareSave($value, $this, $type, $section);
			}

			$data = $field->onPrepareFullTextSearch($value, $this, $type, $section);
			if(is_array($data))
			{
				foreach($data AS &$v)
				{
					if(is_array($v))
					{
						$v = implode(' ', $v);
					}
				}
				$data = implode(', ', $data);
			}
			$text_fields[$field->id] = $data;
		}

		$this->fields = json_encode($out);



		if($isNew)
		{
			if(!isset($post['access']))
			{
				$this->access = $type->params->get('submission.access', 1);
			}

			if(empty($post['extime']) && $type->params->get('submission.default_expire', 0) > 0)
			{
				$this->extime = JFactory::getDate('+ ' . $type->params->get('submission.default_expire') . ' DAY')->toSql();
			}
		}
		else
		{
			if(strtotime($post['extime']) > time())
			{
				$this->exalert = 0;
			}
		}

		/* ---- CATEGORIES ---- */
		$categories = array();
		if($section->categories)
		{
			if(!in_array($type->params->get('submission.allow_category'), $user->getAuthorisedViewLevels()))
			{
				if($app->input->get('cat_id'))
				{
					$categories[] = $app->input->get('cat_id');
				}

				if(!$categories && $this->categories)
				{
					$cat_array = json_decode($this->categories, TRUE);
					$categories = array_keys($cat_array);
				}
			}
			else
			{
				$categories = @$post['category'];
				if($categories && !is_array($categories))
				{
					$categories = explode(',', $categories);
				}
				if(!$categories && $app->input->get('cat_id'))
				{
					$categories[] = $app->input->get('cat_id');
				}
			}


			ArrayHelper::clean_r($categories, TRUE);
			JArrayHelper::toInteger($categories);

			if($section->categories && !$categories && !$type->params->get('submission.first_category', 0))
			{
				$this->setError(JText::_('C_MSG_SELECTCATEGORY'));

				return FALSE;
			}

			if($type->params->get('submission.multi_max_num', 0) > 0 && count($categories) > $type->params->get('submission.multi_max_num', 0))
			{
				$this->setError(JText::plural('C_MSG_CATEGORYLIMIT', $type->params->get('submission.multi_max_num', 0)));

				return FALSE;
			}

			$cats = array();
			if($type->params->get('category_limit.category'))
			{
				$cats = $type->params->get('category_limit.category');

				if($type->params->get('category_limit.category_limit_mode') == 1)
				{
					$cats = MECAccess::_getsubcats($cats, $section);
				}

				if($type->params->get('category_limit.allow') == 1 && $cats)
				{
					$cats = MECAccess::_invertcats($cats, $section);
				}
			}

			if($mrcats = MECAccess::getModeratorRestrictedCategories($user->get('id'), $section))
			{
				$cats = $mrcats;
			}

			foreach($categories as $k => $category)
			{
				if((int)$category == 0)
				{
					unset($categories[$k]);
				}
				if(in_array($category, $cats))
				{
					unset($categories[$k]);

					$this->setError(JText::_('C_MSG_CAT_NOTALLOW'));

					return FALSE;
				}
			}

			if($categories)
			{
				$db = JFactory::getDbo();

				$sql = "SELECT id, title, params, access FROM #__js_res_categories WHERE id IN (" . implode(',', $categories) . ")";
				$db->setQuery($sql);
				$cats = $db->loadObjectList();

				$categories = array();

				foreach($cats as $cat)
				{
					$categories[$cat->id] = $cat->title;
					if($isNew && empty($post['access']))
					{
						$catparams = new JRegistry($cat->params);
						if($catparams->get('access_level'))
						{
							$this->access = $cat->access;
						}
					}
				}
			}
		}

		$this->categories = json_encode($categories);

		/* ---- CATEGORIES ---- */

		if($type->params->get('properties.item_title') == 2)
		{
			$title = $type->params->get('properties.item_title_composite', 'Please set composite title mask in type parameters');

			$field_vals = new JRegistry($out);

			foreach($out as $id => $value)
			{
				if(strpos($title, "[{$id}]") !== FALSE)
				{
					if(!empty($text_fields[$id]))
					{
						$title = str_replace("[{$id}]", $text_fields[$id], $title);
					}
					$title = str_replace("[{$id}]", '', $title);
				}


				if(preg_match_all("/\[{$id}::(.*)\]/iU", $title, $matches))
				{
					foreach($matches[0] AS $key => $match)
					{
						$path = $id . "." . str_replace('::', '.', $matches[1][$key]);
						if($field_vals->get($path))
						{
							$title = str_replace($match, $field_vals->get($path), $title);
						}
						$title = str_replace($match, '', $title);
					}
				}
			}

			$title = str_replace(
				array(
					'[USER]',
					'[TIME]'
				),
				array(
					CCommunityHelper::getName($this->user_id, $section, TRUE),
					time()
				),
				$title
			);

			if(preg_match('/\[RND::([0-9\:]*)\]/iU', $title, $matches))
			{
				$data = new JRegistry(explode('::', $matches[1]));

				if($data->get('1', 1) && $data->get('2', 1))
				{
					$rand = md5(time() . '-' . $title);
				}
				elseif($data->get('1', 1))
				{
					$rand = rand();
					$rand .= rand();
					$rand .= rand();
					$rand .= rand();
					$rand .= rand();
					$rand .= rand();
					$rand .= rand();
					$rand .= rand();
				}
				elseif($data->get('2', 1))
				{
					$rand = base64_encode(md5(time() . '-' . $title));
					$rand = JFilterInput::getInstance()->clean($rand);
				}

				$rand = substr($rand, 0, $data->get('0', 8));

				$title = str_replace($matches[0], $rand, $title);

			}
			if(preg_match('/\[DATE::(.*)\]/iU', $title, $matches))
			{
				$title = str_replace($matches[0], date($matches[1]), $title);
			}

			$this->title = $title;
		}


		$this->title = CensorHelper::cleanText($this->title);
		if($type->params->get('properties.item_title_limit', 0))
		{
			if(JString::strlen($this->title) > $type->params->get('properties.item_title_limit', 0))
			{
				$this->setError(JText::sprintf('C_MSG_TITLETOLONG', $type->params->get('properties.item_title_limit', 0)));

				return FALSE;
			}
		}

		$this->meta_descr = CensorHelper::cleanText($this->meta_descr);
		$this->meta_key = CensorHelper::cleanText($this->meta_key);

		if($type->params->get('properties.item_title_unique'))
		{
			$sql = "SELECT id from #__js_res_record WHERE title = '{$this->_db->escape($this->title)}' AND type_id = {$this->type_id} AND id NOT IN(" . (int)@$this->id . ")";
			$this->_db->setQuery($sql);
			if($this->_db->loadResult())
			{
				$this->setError(JText::_('C_MSG_TITLEEXISTS'));

				return FALSE;
			}
		}

		if($this->getErrors())
		{
			return FALSE;
		}

		if(!array_key_exists('published', $post))
		{
			if($isNew && $type->params->get('submission.autopublish', 1) == 0)
			{
				$this->published = 0;
				JError::raiseNotice(1, JText::_('CNEWARTICLEAPPROVE'));
			}

			if(!$isNew && $type->params->get('submission.edit_autopublish', 1) == 0)
			{
				$this->published = 0;
				JError::raiseNotice(1, JText::_('CEDITARTICLEAPPROVE'));
			}

			if(is_null($this->published))
			{
				$this->published = 1;
			}
		}


		$this->title = trim($this->title);
		$this->access_key = md5(time() . $_SERVER['REMOTE_ADDR'] . $this->title);


		if(!$this->alias || ($task == 'save2copy' || $task == 'copy'))
		{
			$this->alias = $this->title;
		}

		if(!$this->alias)
		{
			$this->alias = JFactory::getDate()->format('Y-m-d-H-i-s');
		}

		$this->alias = JApplication::stringURLSafe(strip_tags(CensorHelper::cleanText($this->alias)));

		return TRUE;
	}

	public function index()
	{
		$section = ItemsStore::getSection($this->section_id);
		$type = ItemsStore::getType($this->type_id);
		$field_list = JModelLegacy::getInstance('Fields', 'CobaltModel')->getFormFields($type->id);
		$values = json_decode($this->fields, TRUE);

		$fieldsdata = array();

		foreach($field_list as $field)
		{
			if(!$field->params->get('core.searchable'))
			{
				continue;
			}

			$value = $values[$field->id];
			$data = $field->onPrepareFullTextSearch($value, $this, $type, $section);

			if(is_array($data))
			{
				foreach($data AS &$v)
				{
					if(is_array($v))
					{
						$v = implode(' ', $v);
					}
				}
				$data = implode(', ', $data);
			}
			$fieldsdata[$field->id] = $data;

		}


		if($section->params->get('more.search_title'))
		{
			$fieldsdata[] = $this->title;
		}
		if($section->params->get('more.search_name'))
		{
			$fieldsdata[] = JFactory::getUser($this->user_id)->get('name');
			$fieldsdata[] = JFactory::getUser($this->user_id)->get('username');
		}
		if($section->params->get('more.search_email'))
		{
			$fieldsdata[] = JFactory::getUser($this->user_id)->get('email');
		}
		if($section->params->get('more.search_category') && $this->categories != '[]')
		{
			$cats = json_decode($this->categories, TRUE);
			$fieldsdata[] = implode(', ', array_values($cats));
		}

		if($section->params->get('more.search_comments'))
		{
			$fieldsdata[] = CommentHelper::fullText($type, $this);
		}

		$this->fieldsdata = strip_tags(implode(', ', $fieldsdata));

		$this->store();

	}

	public function check_cli()
	{
		$isNew = (boolean)empty($this->id);
		$user = JFactory::getUser();

		if($this->checked_out && $user->get('id') != $this->checked_out)
		{
			$this->setError(JText::sprintf('CANNOTEDITCHECKOUT', CCommunityHelper::getName($this->checked_out, $this->section_id), CCommunityHelper::getName($this->checked_out, $this->section_id)));

			return FALSE;
		}
		if(trim($this->title) == '')
		{
			$this->setError(JText::_('MUSTCONTAINTITLE'));

			return FALSE;
		}
		if(!$this->ip)
		{
			$this->ip = $_SERVER['REMOTE_ADDR'];
		}


		if(!$this->langs)
		{
			$lang = JFactory::getLanguage();
			$this->langs = $lang->getTag();
		}

		if($this->ctime == '' || $this->ctime == '0000-00-00 00:00:00')
		{
			$this->ctime = JFactory::getDate()->toSql();
		}

		if($this->inittime == '' || $this->inittime == '0000-00-00 00:00:00')
		{
			$this->inittime = JFactory::getDate()->toSql();
		}

		$this->mtime = JFactory::getDate()->toSql();


		if(!$this->user_id && $isNew)
		{
			$this->user_id = (int)$user->get('id');
		}

		if($this->getErrors())
		{
			return FALSE;
		}

		$this->title = trim($this->title);
		$this->access_key = md5(time() . $this->ip . $this->title);


		if(!$this->alias)
		{
			$this->alias = $this->title;
		}

		if(!$this->alias)
		{
			$this->alias = JFactory::getDate()->format('Y-m-d-H-i-s');
		}

		$this->alias = JApplication::stringURLSafe(strip_tags($this->alias));

		return TRUE;
	}
}
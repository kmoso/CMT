<?php
/**
 * Cobalt by MintJoomla
 * a component for Joomla! 1.7 - 2.5 CMS (http://www.joomla.org)
 * Author Website: http://www.mintjoomla.com/
 *
 * @copyright Copyright (C) 2012 MintJoomla (http://www.mintjoomla.com). All rights reserved.
 * @license   GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */

defined('_JEXEC') or die();

jimport('joomla.application.component.controlleradmin');

class CobaltControllerRecords extends JControllerAdmin
{

	public function __construct($config = array())
	{
		parent::__construct($config);

		if(!$this->input)
		{
			$this->input = JFactory::getApplication()->input;
		}
	}

	public function getModel($type = 'Record', $prefix = 'CobaltModel', $config = array())
	{
		return JModelLegacy::getInstance($type, $prefix, $config);
	}

	public function rectorefile()
	{
		if(!$this->_checkAccess('Restore', $this->input->getInt('id')))
		{
			return;
		}

		$db = JFactory::getDbo();
		$db->setQuery("UPDATE `#__js_res_files` SET saved = 1 WHERE id = " . $this->input->get('fid'));
		$db->execute();

		$db->setQuery('SELECT id, filename, realname, ext, size, title, description, width, height, fullpath, params
		FROM #__js_res_files WHERE id = ' . $this->input->get('fid'));
		$file = $db->loadAssoc();

		if(!$file)
		{
			$this->_finish(JText::_('CMSG_CANNOTRESTOREFILE'));

			return;
		}

		$fields = json_decode($this->record->fields, TRUE);
		settype($fields[$this->input->get('field_id')], 'array');
		foreach($fields[$this->input->get('field_id')] AS $f)
		{
			if($f['id'] == $this->input->get('fid'))
			{
				$this->_finish(JText::_('CMSG_FILERESTORED'));

				return;
			}
		}
		$fields[$this->input->get('field_id')][] = $file;

		$this->record->fields = json_encode($fields);
		$this->record->store();

		$this->record->file = $file;
		ATlog::log($this->record, ATlog::REC_FILE_RESTORED);

		$this->_finish(JText::_('CMSG_FILERESTORED'));

	}

	public function commentsdisable()
	{
		if(!$this->_checkAccess('CommentBlock', $this->input->getInt('id')))
		{
			return;
		}

		$params = new JRegistry($this->record->params);
		$params->set('comments.comments_access_post', 0);

		$this->record->params = $params->toString();
		$this->record->store();

		$this->_finish(JText::_('CMSG_COMMENTDISAB'));
	}

	public function commentsenable()
	{


		if(!$this->_checkAccess('CommentBlock', $this->input->getInt('id')))
		{
			return;
		}

		$params = new JRegistry($this->record->params);
		$params->set('comments.comments_access_post', $this->type->params->get('comments.comments_access_post'));

		$this->record->params = $params->toString();
		$this->record->store();

		$this->_finish(JText::_('CMSG_COMMMENAD'));
	}

	public function depost()
	{
		if(!$this->_checkAccess('Depost', $this->input->getInt('id')))
		{
			return;
		}

		$db = JFactory::getDbo();
		$db->setQuery("DELETE FROM `#__js_res_record_repost` WHERE record_id = {$this->record->id} AND host_id = " . JFactory::getUser()->get('id'));
		$db->query();

		$this->record->onRepost();

		$this->_finish(JText::_('CMSG_DEPOSTED'));
	}

	public function restore()
	{
		$db = JFactory::getDbo();
		$db->setQuery("SELECT params FROM #__js_res_audit_log WHERE record_id = " . $this->input->getInt('id'));
		$record = $db->loadResult();
		if(!$record)
		{
			$this->_finish(JText::_('CERRRECNOTFOUND'), TRUE);

			return;
		}

		$record = json_decode($record);

		if(!MECAccess::allowRestore($record))
		{
			$this->_finish(JText::_('CERRNOACTIONACCESS'), TRUE);
		}

		$db->setQuery("SELECT * FROM #__js_res_audit_restore WHERE record_id = " . $this->input->getInt('id'));
		$restore = $db->loadObject();

		if(!$restore)
		{
			$this->_finish(JText::_('CERRRESTORENOTFOUND'), TRUE);

			return;
		}

		$db->setQuery("SELECT * FROM #__js_res_audit_versions WHERE record_id = " . $this->input->getInt('id') . " ORDER BY `version` DESC LIMIT 1");
		$lastversion = $db->loadObject();

		if(!$lastversion)
		{
			$this->_finish(JText::_('CERRRESTORENOTFOUND'), TRUE);

			return;
		}

		$sql = "INSERT INTO #__js_res_record (id) values (" . $this->input->getInt('id') . ")";
		$db->setQuery($sql);
		$db->query();

		$this->record = JTable::getInstance('Record', 'CobaltTable');

		$this->_rollback($lastversion);

		$comments = json_decode($restore->comments, TRUE);
		$this->_restore_table($comments, 'CobComments');

		$favorites = json_decode($restore->favorites, TRUE);
		$this->_restore_table($favorites, 'Favorites');

		$files = json_decode($restore->files, TRUE);
		$this->_restore_table($files, 'Files');

		$hits = json_decode($restore->hits, TRUE);
		$this->_restore_table($hits, 'Hits');

		$subscriptions = json_decode($restore->subscriptions, TRUE);
		$this->_restore_table($subscriptions, 'Subscribe', 'ref_id');

		$votes = json_decode($restore->votes, TRUE);
		$this->_restore_table($votes, 'Votes', 'ref_id');

		$notifications = json_decode($restore->notifications, TRUE);
		$this->_restore_table($notifications, 'Notificat', 'ref_1');

		$db->setQuery("DELETE FROM #__js_res_audit_restore WHERE record_id = " . $this->input->getInt('id'));
		$db->query();

		ATlog::log($this->record, ATlog::REC_RESTORED);
		$this->_finish(JText::_('CMSG_RESTORED'));
	}

	public function rollback()
	{
		if(!$this->_checkAccess('Rollback', $this->input->getInt('id')))
		{
			return;
		}

		$version = $this->input->getInt('version');

		if(!$version)
		{
			$this->_finish(JText::_('CNOTICEVERNOTSET'), TRUE);

			return;
		}

		$db = JFactory::getDbo();
		$db->setQuery("SELECT * FROM #__js_res_audit_versions WHERE `version` = {$version} AND record_id = {$this->record->id}");
		$restore = $db->loadObject();

		if(!$restore)
		{
			$this->_finish(JText::sprintf('CNOTICEVERSNOTFUOND', $version), TRUE);

			return;
		}

		$this->_rollback($restore);

		ATlog::log($this->record, ATlog::REC_ROLLEDBACK);

		$this->_finish(JText::sprintf('CMSG_ROLLBACKSUCCESS', $this->record->title, $version));
	}

	private function _rollback($restore)
	{
		$db = JFactory::getDbo();

		$record = json_decode($restore->record_serial, TRUE);

		$this->record->bind($record);
		$this->record->store();


		$tags = json_decode($restore->tags_serial, TRUE);
		$this->_restore_table($tags, 'Taghistory');

		$values = json_decode($restore->values_serial, TRUE);
		$this->_restore_table($values, 'Record_values');

		$categories = json_decode($restore->category_serial, TRUE);
		$this->_restore_table($categories, 'Record_category');
	}

	private function _restore_table($list, $table_class, $ref = 'record_id')
	{
		$table = JTable::getInstance($table_class, 'CobaltTable');
		$name = $table->getTableName();

		if(!$name)
		{
			JError::raiseError(500, 'no table:' . $table_class);

			return;
		}

		$db  = JFactory::getDbo();
		$sql = "DELETE FROM {$name} WHERE {$ref} = " . $this->record->id;
		if($table_class == 'Votes')
		{
			$sql .= " AND ref_type = 'record' ";
		}
		$db->setQuery($sql);
		$db->query();

		foreach($list AS $item)
		{
			if(is_object($item))
			{
				$item = get_object_vars($item);
			}
			unset($item['id']);
			$table->save($item);

			$table->reset();
			$table->id = NULL;
		}
	}

	public function prolong()
	{


		if(!$this->_checkAccess('Extend', $this->input->getInt('id')))
		{
			return;
		}
		$user = JFactory::getUser();
		CEmeraldHelper::allowType('extend', $this->type, $user->id, $this->section, TRUE, '', $this->record->user_id);

		$this->record->extime  = JFactory::getDate("+" . $this->type->params->get('properties.default_extend', 10) . ' day')->toSql();
		$this->record->exalert = 0;

		$type = ItemsStore::getType($this->record->type_id);
		if($type->params->get('properties.item_expire_access'))
		{
			$this->record->access = $type->params->get('submission.access');
		}

		$this->record->store();

		$data = $this->record->getProperties();
		CEventsHelper::notify('record', CEventsHelper::_RECORD_EXTENDED, $this->record->id, $this->record->section_id, 0, 0, 0, $data);

		$db = JFactory::getDbo();
		$db->setQuery("DELETE FROM #__js_res_notifications WHERE `type` = 'record_expired' AND ref_1 = {$this->record->id}");
		$db->execute();
		//CEmeraldHelper::countLimit('type', 'extend', $this->type, $user->id);

		ATlog::log($this->record, ATlog::REC_PROLONGED);

		$this->_finish(JText::sprintf('CMSG_RECEXTENDED', $this->type->params->get('properties.default_extend', 10)));
	}

	public function shide()
	{


		if(!$this->_checkAccess('Hide', $this->input->getInt('id')))
		{
			return;
		}

		$this->record->hidden = 1;
		$this->record->store();

		ATlog::log($this->record, ATlog::REC_HIDDEN);

		$this->_finish(JText::_('CMSG_RECHIDDEN'));
	}

	public function sunhide()
	{


		if(!$this->_checkAccess('Hide', $this->input->getInt('id')))
		{
			return;
		}

		$this->record->hidden = 0;
		$this->record->store();

		ATlog::log($this->record, ATlog::REC_UNHIDDEN);

		$this->_finish(JText::_('CMSG_RECUNHIDDEN'));
	}

	public function sfeatured()
	{
		if(!$this->_checkAccess('Featured', $this->input->getInt('id')))
		{
			return;
		}

		$user = JFactory::getUser();

		CEmeraldHelper::allowType('feature', $this->type, $user->id, $this->section, TRUE, NULL, $this->record->user_id);

		$this->record->featured = 1;
		$this->record->ftime    = JFactory::getDate("+" . $this->type->params->get('emerald.type_feature_subscription_time', 10) . ' day')->toSql();
		$this->record->store();

		$data = $this->record->getProperties();
		CEventsHelper::notify('record', CEventsHelper::_RECORD_FEATURED, $this->record->id, $this->record->section_id, 0, 0, 0, $data);

		//CEmeraldHelper::countLimit('type', 'feature', $this->type, $user->id);

		ATlog::log($this->record, ATlog::REC_FEATURED);


		$this->_finish(JText::sprintf('CMSG_RECFEATUREDOK', $this->type->params->get('emerald.type_feature_subscription_time', 10)));

	}

	public function sunfeatured()
	{
		if(!$this->_checkAccess('Featured', $this->input->getInt('id')))
		{
			return;
		}
		$user = JFactory::getUser();

		//CEmeraldHelper::allowType('feature', $this->type, $user->id, $this->section, TRUE, $this->record->user_id);

		$this->record->featured = 0;
		$this->record->ftime    = '0000-00-00 00:00:00';
		$this->record->store();

		$data = $this->record->getProperties();

		CEventsHelper::notify('record', CEventsHelper::_RECORD_FEATURED_EXPIRED, $this->record->id, $this->record->section_id, 0, 0, 0, $data, 2, $this->record->user_id);

		ATlog::log($this->record, ATlog::REC_UNFEATURED);


		$this->_finish(JText::_('CMSG_RECUNFEATUREDOK'));

	}

	public function sunpub()
	{


		if(!$this->_checkAccess('Publish', $this->input->getInt('id')))
		{
			return;
		}

		$this->record->published = 0;
		$this->record->store();

		if($this->record->user_id)
		{
			$data = $this->record->getProperties();
			CEventsHelper::notify('record', CEventsHelper::_RECORD_UNPUBLISHED, $this->record->id, $this->record->section_id, 0, 0, 0, $data, 2, $this->record->user_id);
		}

		ATlog::log($this->record, ATlog::REC_UNPUBLISHED);

		$this->_finish(JText::_('CMSG_RECUNPUBOK'));
	}

	public function spub()
	{
		if(!$this->_checkAccess('Publish', $this->input->getInt('id')))
		{
			return;
		}

		if($this->record->pubtime == '0000-00-00 00:00:00')
		{
			CEventsHelper::notify('category', CEventsHelper::_RECORD_NEW, $this->record->id, $this->record->section_id, 0, 0, 0, $this->record->getProperties());
		}

		$this->record->published = 1;
		$this->record->pubtime   = JFactory::getDate()->toSql();
		$this->record->store();

		if($this->record->user_id)
		{
			$data = $this->record->getProperties();
			CEventsHelper::notify('record', CEventsHelper::_RECORD_APPROVED, $this->record->id, $this->record->section_id, 0, 0, 0, $data, 2, $this->record->user_id);
		}

		ATlog::log($this->record, ATlog::REC_PUBLISHED);

		$this->_finish(JText::_('CMSG_RECPUBOK'));
	}

	public function sarchive()
	{


		if(!$this->_checkAccess('Archive', $this->input->getInt('id')))
		{
			return;
		}

		$this->record->archive = 1;
		$this->record->store();

		ATlog::log($this->record, ATlog::REC_ARCHIVE);


		$this->_finish(JText::_('CMSG_RECARCHIVEOK'));

	}

	public function delete()
	{
		if(!$this->_checkAccess('Delete', $this->input->getInt('id')))
		{
			$this->_finish('');

			return;
		}

		$type = ItemsStore::getType($this->record->type_id);

		if($type->params->get('audit.versioning'))
		{
			$versions = JTable::getInstance('Audit_versions', 'CobaltTable');
			$version  = $versions->snapshot($this->input->getInt('id'), $type);
		}

		if(!$this->record->delete())
		{
			JError::raiseError(500, 'Cannot delete, something is wrong');

			return;
		}

		$db = JFactory::getDbo();

		$db->setQuery("DELETE FROM #__js_res_record_category WHERE record_id = " . $this->input->getInt('id'));
		$db->execute();

		$db->setQuery("DELETE FROM #__js_res_record_values WHERE record_id = " . $this->input->getInt('id'));
		$db->execute();

		$db->setQuery("DELETE FROM #__js_res_tags_history WHERE record_id = " . $this->input->getInt('id'));
		$db->execute();

		$db->setQuery("SELECT * FROM #__js_res_files WHERE record_id = " . $this->input->getInt('id'));
		$files = $db->loadObjectList('id');


		if(!empty($files) && !$type->params->get('audit.versioning'))
		{
			$field_table   = JTable::getInstance('Field', 'CobaltTable');
			$cobalt_params = JComponentHelper::getParams('com_cobalt');

			foreach($files AS $file)
			{
				$field_table->load($file->field_id);
				$field_params = new JRegistry($field_table->params);
				$subfolder    = $field_params->get('params.subfolder', $field_table->field_type);
				if(JFile::exists(JPATH_ROOT . DIRECTORY_SEPARATOR . $cobalt_params->get('general_upload') . DIRECTORY_SEPARATOR . $subfolder . DIRECTORY_SEPARATOR . $file->fullpath))
				{
					unlink(JPATH_ROOT . DIRECTORY_SEPARATOR . $cobalt_params->get('general_upload') . DIRECTORY_SEPARATOR . $subfolder . DIRECTORY_SEPARATOR . $file->fullpath);
				}
				// deleting image field files
				elseif(JFile::exists(JPATH_ROOT . DIRECTORY_SEPARATOR . $file->fullpath))
				{
					unlink(JPATH_ROOT . DIRECTORY_SEPARATOR . $file->fullpath);
				}
			}
			$db->setQuery("DELETE FROM #__js_res_files WHERE id IN (" . implode(',', array_keys($files)) . ")");
			$db->execute();
		}

		if($files)
		{
			$db->setQuery("UPDATE #__js_res_files SET `saved` = 2 WHERE id IN (" . implode(',', array_keys($files)) . ")");
			$db->execute();
		}

		if($type->params->get('audit.versioning'))
		{
			$restore['files']     = json_encode($files);
			$restore['record_id'] = $this->input->getInt('id');
			$restore['dtime']     = JFactory::getDate()->toSql();

			$db->setQuery("SELECT * FROM #__js_res_comments WHERE record_id = " . $this->input->getInt('id'));
			$restore['comments'] = json_encode($db->loadAssocList());

			$db->setQuery("SELECT * FROM #__js_res_favorite WHERE record_id = " . $this->input->getInt('id'));
			$restore['favorites'] = json_encode($db->loadAssocList());

			$db->setQuery("SELECT * FROM #__js_res_hits WHERE record_id = " . $this->input->getInt('id'));
			$restore['hits'] = json_encode($db->loadAssocList());

			$db->setQuery("SELECT * FROM #__js_res_subscribe WHERE type = 'record' AND ref_id = " . $this->input->getInt('id'));
			$restore['subscriptions'] = json_encode($db->loadAssocList());

			$db->setQuery("SELECT * FROM #__js_res_vote WHERE (ref_id = " . $this->input->getInt('id') .
				" AND ref_type = 'record') OR (ref_id IN(SELECT id FROM #__js_res_comments WHERE record_id = " . $this->input->getInt('id') . ") AND ref_type = 'comment')");
			$restore['votes'] = json_encode($db->loadAssocList());

			$db->setQuery("SELECT * FROM #__js_res_notifications WHERE ref_1 = " . $this->input->getInt('id'));
			$restore['notifications'] = json_encode($db->loadAssocList());

			$restore['type_id'] = $type->id;

			$table = JTable::getInstance('Audit_restore', 'CobaltTable');
			$table->save($restore);
		}

		$db->setQuery("DELETE FROM #__js_res_vote WHERE (ref_id = " . $this->input->getInt('id') .
			" AND ref_type = 'record') OR (ref_id IN(SELECT id FROM #__js_res_comments WHERE record_id = " . $this->input->getInt('id') . ") AND ref_type = 'comment')");
		$db->execute();

		$db->setQuery("DELETE FROM #__js_res_comments WHERE record_id = " . $this->input->getInt('id'));
		$db->execute();

		$db->setQuery("DELETE FROM #__js_res_favorite WHERE record_id = " . $this->input->getInt('id'));
		$db->execute();

		$db->setQuery("DELETE FROM #__js_res_hits WHERE record_id = " . $this->input->getInt('id'));
		$db->execute();

		$db->setQuery("DELETE FROM #__js_res_subscribe WHERE type = 'record' AND ref_id = " . $this->input->getInt('id'));
		$db->execute();

		$db->setQuery("DELETE FROM #__js_res_notifications WHERE ref_1 = " . $this->input->getInt('id'));
		$db->execute();

		$db->setQuery("DELETE FROM #__js_res_record WHERE parent = 'com_cobalt' AND parent_id = " . $this->input->getInt('id'));
		$db->execute();

		if($this->record->user_id)
		{
			$data = $this->record->getProperties();
			//CEventsHelper::notify('record', CEventsHelper::_RECORD_DELETED, $this->record->id, $this->record->section_id, 0, 0, 0, $data, 2, $this->record->user_id);
		}

		ATlog::log($this->record, ATlog::REC_DELETE);
		$this->_finish(JText::_('CMSG_RECDELETEDOK'));

		JPluginHelper::importPlugin('mint');
		$dispatcher = JEventDispatcher::getInstance();
		$dispatcher->trigger('onRecordDelete', array($this->record));

		$this->setRedirect(CobaltFilter::base64($this->input->getBase64('return')));

		return TRUE;
	}

	public function markread()
	{
		$app  = JFactory::getApplication();
		$user = JFactory::getUser();

		$db = JFactory::getDbo();
		$db->setQuery("UPDATE #__js_res_notifications SET state_new = 0, notified = 1 WHERE user_id = " . $user->get('id') . ' AND ref_2 = ' . $this->input->getInt('section_id'));
		$db->execute();

		$app->enqueueMessage(JText::_('EVENT_CLEAR'));

		if($this->input->getInt('section_id'))
		{
			$section = ItemsStore::getSection($this->input->getInt('section_id'));
			$url     = Url::records($section);
		}
		else
		{
			$url = $this->_getUrl();
		}
		$this->setRedirect(JRoute::_($url, FALSE));
	}

	private function _finish($msg, $err = FALSE)
	{
		if($err)
		{
			JError::raiseWarning(500, $msg);
		}
		else
		{
			if($msg)
			{
				$app = JFactory::getApplication();
				$app->enqueueMessage($msg);
			}
		}
		$url = Url::get_back('return');
		$this->setRedirect(JRoute::_($url, FALSE));
	}

	private function _checkAccess($control, $id)
	{
		$this->record = JTable::getInstance('Record', 'CobaltTable');
		$this->record->load($id);

		$this->record;

		if(!$this->record->id)
		{
			JError::raiseWarning(403, JText::_('No record found'));

			return FALSE;
		}

		$params               = new JRegistry($this->record->params);
		$this->record->params = $params;

		$this->type    = JModelLegacy::getInstance('Form', 'CobaltModel')->getRecordType($this->record->type_id);
		$this->section = JModelLegacy::getInstance('Section', 'CobaltModel')->getItem($this->record->section_id);

		$control = 'allow' . $control;

		if(!MECAccess::$control($this->record, $this->type, $this->section))
		{
			JError::raiseWarning(403, JText::_('CERRNOACTIONACCESS'));

			return FALSE;
		}

		return TRUE;
	}


	public function cleanall()
	{
		$this->_clean();
		$app = JFactory::getApplication();
		$app->enqueueMessage(JText::_('CMSG_FILTERCLEANALL'));
		$url = $this->_getUrl();
		$this->setRedirect(JRoute::_($url, FALSE));
	}

	private function _clean()
	{
		$app = JFactory::getApplication();
		$key = FilterHelper::key();

		$sec_model = JModelLegacy::getInstance('Section', 'CobaltModel');
		$section   = $sec_model->getItem($this->input->getInt('section_id'));

		$rec_model          = JModelLegacy::getInstance('Records', 'CobaltModel');
		$rec_model->section = $section;
		$list               = $rec_model->getResetFilters();
		foreach($list as $filter)
		{
			echo $filter->key;
			$app->setUserState('com_cobalt.section' . $key . '.filter_' . $filter->key, '');
		}

		$app->setUserState('com_cobalt.section' . $key . '.filter_search', '');
		$app->setUserState('com_cobalt.section' . $key . '.filter_type', '');
		$app->setUserState('com_cobalt.section' . $key . '.filter_tag', '');
		$app->setUserState('com_cobalt.section' . $key . '.filter_user', '');
		$app->setUserState('com_cobalt.section' . $key . '.filter_alpha', '');
		$app->setUserState('com_cobalt.section' . $key . '.filter_cat', '');
	}

	public function clean()
	{
		$key   = FilterHelper::key();
		$app   = JFactory::getApplication();
		$clean = $this->input->get('clean', array(), 'array');

		foreach($clean as $name => $val)
		{
			if($val)
			{
				$app->setUserState('com_cobalt.section' . $key . '.' . $name, NULL);
			}
		}

		$url = $this->_getUrl();
		$this->setRedirect(JRoute::_($url, FALSE));
	}

	public function filters()
	{
		$key     = FilterHelper::key();
		$db      = JFactory::getDbo();
		$app     = JFactory::getApplication();
		$filters = $this->input->get('filters', array(), 'array');

		$sec_model = JModelLegacy::getInstance('Section', 'CobaltModel');
		$section   = $sec_model->getItem($this->input->getInt('section_id'));

		$app->setUserState('com_cobalt.records' . $section->id . '.limitstart', 0);
		$app->setUserState('com_cobalt.section' . $key . '.filter_search', $this->input->get('filter_search', NULL, 'string'));

		//JError::raiseNotice(100, 'com_cobalt.section' . $key . '.filter_type');
		$app->setUserState('com_cobalt.section' . $key . '.filter_type', @$filters['type']);
		unset($filters['type']);

		$tags = @$filters['tags'];
		unset($filters['tags']);
		if(!is_array($tags))
		{
			$tags = explode(',', $tags);

			ArrayHelper::clean_r($tags);
			JArrayHelper::toInteger($tags);
		}
		$app->setUserState('com_cobalt.section' . $key . '.filter_tag', $tags);

		$users = @$filters['users'];
		unset($filters['users']);
		if(!is_array($users))
		{
			$users = explode(',', $users);

			ArrayHelper::clean_r($users);
			JArrayHelper::toInteger($users);
		}
		$app->setUserState('com_cobalt.section' . $key . '.filter_user', $users);

		$cats = @$filters['cats'];
		unset($filters['cats']);
		if(!is_array($cats))
		{
			$cats = explode(',', $cats);

			ArrayHelper::clean_r($cats);
			JArrayHelper::toInteger($cats);
		}
		$app->setUserState('com_cobalt.section' . $key . '.filter_cat', $cats);

		settype($filters, 'array');

		$rec_model          = JModelLegacy::getInstance('Records', 'CobaltModel');
		$rec_model->section = $section;
		$list               = $rec_model->getFilters();
		$store              = array();
		foreach($list as $fkey => $filter)
		{
			$app->setUserState('com_cobalt.section' . $key . '.filter_' . $fkey, @$filters[$fkey]);
			if($filters)
			{
				$store[$filter->key] = $filter;
			}
		}

		$url = $this->_getUrl();
		$this->setRedirect(JRoute::_($url, FALSE));
	}

	public function filter()
	{
		if($this->input->get('clean'))
		{
			$this->_clean();
		}

		$names = $this->input->get('filter_name', array(), 'array');
		$vals  = $this->input->get('filter_val', array(), 'array');

		foreach($names as $k => $name)
		{
			$key = FilterHelper::key();

			if($name == 'filter_tpl')
			{
				$key = $this->input->getInt('section_id');
				if($this->input->getInt('cat_id'))
				{
					$category = ItemsStore::getCategory($this->input->getInt('cat_id'));
					$t        = $category->params->get('tmpl_list');
					ArrayHelper::clean_r($t);
					if($t)
					{
						$key .= '-' . $this->input->getInt('cat_id');
					}
				}

				$oldname = JFactory::getApplication()->getUserState('com_cobalt.section' . $key . '.filter_tpl', 'default');
				if($oldname != $vals[$k])
				{
					$section = ItemsStore::getSection($this->input->getInt('section_id'));
					$section->params->set('general.tmpl_list', $vals[$k]);
					$lparams = CTmpl::prepareTemplate('default_list_', 'general.tmpl_list', $section->params);

					JFactory::getApplication()->setUserState('global.list.limit', $lparams->get('tmpl_core.item_limit_default', 20));
				}
			}
			preg_match('/^filter_([0-9]*)$/iU', $name, $match);
			if(!empty($match[1]))
			{
				$db = JFactory::getDbo();
				$db->setQuery("SELECT `key` FROM #__js_res_fields WHERE id = " . $match[1]);
				$name = 'filter_' . $db->loadResult();
			}

			JFactory::getApplication()->setUserState('com_cobalt.section' . $key . '.' . $name, $vals[$k]);
		}

		$url = $this->_getUrl();
		$this->setRedirect(JRoute::_($url, FALSE));
	}

	private function _getUrl()
	{
		$url = 'index.php?option=com_cobalt&view=records';
		if($s = $this->input->getInt('section_id'))
		{
			$url .= '&section_id=' . $s;
		}
		if($c = $this->input->getInt('cat_id'))
		{
			$url .= '&cat_id=' . $c;
		}
		if($uc = $this->input->getInt('ucat_id'))
		{
			$url .= '&ucat_id=' . $uc;
		}
		$u = $this->input->get('user_id', NULL);
		if(!is_null($u))
		{
			$u = (int)$u;
		}
		if($u || $u === 0)
		{
			$url .= '&user_id=' . $u;
			if($v = $this->input->get('view_what', 'created'))
			{
				$url .= '&view_what=' . $v;
			}
		}
		if($i = $this->input->getInt('Itemid'))
		{
			$url .= '&Itemid=' . $i;
		}
		if($l = $this->input->getInt('lang'))
		{
			$url .= '&lang=' . $l;
		}

		return $url;
	}
}
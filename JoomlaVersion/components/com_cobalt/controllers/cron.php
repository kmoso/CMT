<?php
/**
 * Cobalt by MintJoomla
 * a component for Joomla! 1.7 - 2.5 CMS (http://www.joomla.org)
 * Author Website: http://www.mintjoomla.com/
 * @copyright Copyright (C) 2012 MintJoomla (http://www.mintjoomla.com). All rights reserved.
 * @license   GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */

defined('_JEXEC') or die();

jimport('joomla.application.component.controlleradmin');

class CobaltControllerCron extends JControllerAdmin
{

	public function __construct($config = array())
	{
		$config = JComponentHelper::getParams('com_cobalt');


		parent::__construct($config);

		if(!$this->input)
		{
			$this->input = JFactory::getApplication()->input;
		}

		if(!$this->input->get('secret') || $config->get('cron_key') != $this->input->get('secret'))
		{
			echo "Secret code is wrong. Add secret word in Cobalt global config and add <code>&secret=secretword</code> to URL.";
			JFactory::getApplication()->close();
		}
	}

	public function checkIn()
	{
		$interval = 30;

		$date = JFactory::getDate()->toSql();
		$db   = JFactory::getDBO();
		$sql  = "UPDATE #__js_res_record
			SET checked_out = 0, checked_out_time = '0000-00-00 00:00:00'
			WHERE checked_out > 0
			AND checked_out_time < '{$date}' - INTERVAL $interval MINUTE";
		$db->setQuery($sql);
		$db->query();
	}

	/*public function cleanFiles()
	{
		$db = JFactory::getDBO();

		$sql = "SELECT * FROM #__js_res_files WHERE record_id NOT IN (SELECT r.id FROM #__js_res_record AS r) OR saved = 0";
		$db->setQuery($sql);
		$files = $db->loadObjectList('id');
		$files_ids = array_keys($files);
		$cobalt_params = JComponentHelper::getParams('com_cobalt');

		if(!empty($files_ids))
		{
			$field_table = JTable::getInstance('Field', 'CobaltTable');

			foreach ($files AS $file)
			{
				$field_table->load($file->field_id);
				$field_params = new JRegistry($field_table->params);
				$subfolder = $field_params->get('params.subfolder', $field_table->field_type);
				$part = explode("_", $file->filename);
				unlink(JPATH_ROOT. DIRECTORY_SEPARATOR .$cobalt_params->get('general_upload'). DIRECTORY_SEPARATOR .$subfolder. DIRECTORY_SEPARATOR .date($cobalt_params->get('folder_format'), $part[0]). DIRECTORY_SEPARATOR .$file->filename);
			}

			$sql = "DELETE FROM #__js_res_files WHERE id IN (".implode(',', $files_ids).")";
			$db->setQuery($sql);
			$db->query();
		}

		$files_in_folder = JFolder::files(JPATH_ROOT. DIRECTORY_SEPARATOR .$cobalt_params->get('general_upload'), '.', TRUE, TRUE,
				array('.svn', 'CVS', '.DS_Store', '__MACOSX', 'index.html'), array('^\..*', '.*~', '^thumbs_cache*', '^avatars*'));

		$sql = "SELECT filename FROM #__js_res_files";
		$db->setQuery($sql);
		$files_in_db = $db->loadColumn();

		if(!empty($files_in_folder))
		{
			foreach ($files_in_folder as $file) {
				if(!in_array(JFile::getName($file), $files_in_db))
				{
					JFile::delete($file);
				}
			}
		}

		exit();
	}*/

	public function sendAlert()
	{
		$this->_sendDaijest(1);
		$this->_sendDaijest(2);
		$this->_sendDaijest(3);
		$this->_sendDaijest(4);
		$this->_sendDaijest(5);

		exit();
	}

	private function _sendDaijest($type)
	{
		$db = JFactory::getDbo();

		switch($type)
		{
			case 1:
				$period = '1 MINUTE';
				break;
			case 2:
				$period = '1 DAY';
				break;
			case 3:
				$period = '1 WEEK';
				break;
			case 4:
				$period = '1 MONTH';
				break;
			case 5:
				$period = '3 MONTH';
				break;
		}


		if($type == 1)
		{
			$sql = "SELECT user_id FROM #__js_res_notifications WHERE alerted = 0 AND user_id NOT IN (
				SELECT user_id FROM #__js_res_user_options WHERE schedule != 1 OR lastsend > NOW() - INTERVAL {$period})";
			$db->setQuery($sql);
			$users = $db->loadColumn();
		}
		else
		{
			$sql = "SELECT id FROM #__users WHERE block = 0 AND id IN(
				SELECT user_id FROM #__js_res_user_options WHERE schedule = {$type} AND lastsend < NOW() - INTERVAL {$period})";
			$db->setQuery($sql);
			$users = $db->loadColumn();
		}

		if(empty($users))
		{
			return;
		}

		$sql = "SELECT * FROM #__js_res_notifications WHERE user_id IN (" . implode(',', $users) . ") AND alerted = 0 ORDER BY id DESC";
		$db->setQuery($sql);
		$notes = $db->loadObjectList();

		if(empty($notes))
		{
			return;
		}

		$config       = JFactory::getConfig();
		$sorted_notes = array();

		foreach($notes as $key => $note)
		{
			if(!is_object($note->params))
			{
				$note->params = new JRegistry($note->params);
			}
			$sorted_notes[$note->user_id][$note->ref_1][] = $note;
			unset($notes[$key]);
		}

		$main = JText::_('ALERTMAIN');
		$msg  = JText::_('ALERTDAIJEST');
		$link = '<a href="%s" target="_blank">%s</a>';

		foreach($sorted_notes as $user_id => $records)
		{
			$text = JString::str_ireplace('[MESSAGE_BODY]', $msg, $main);
			$num      = 0;
			$note_ids = $list = array();

			foreach($records AS $id => $notes)
			{
				$record = JTable::getInstance('Record', 'CobaltTable');
				$record->load($id);

				if(empty($record->id))
				{
					$db->setQuery("DELETE FROM #__js_res_notifications WHERE ref_1 = $id");
					$db->query();
					continue;
				}

				$url    = JRoute::_(Url::record($record), TRUE, -1);
				$scheme = JUri::getInstance()->toString(array('scheme'));
				$url    = str_replace('http://', $scheme, $url);


				$list[] = '<h4>' . sprintf($link, $url, $record->title) . '</h4>';

				$li = array();
				foreach($notes as $note)
				{
					$event = JText::_('ALERT_EVENT_' . strtoupper($note->type)) . ' <small>(' . JHtml::_('date', $note->ctime, 'd M Y H:i') . ')</small>';
					switch($note->type)
					{
						case 'comment_new':
						case 'comment_rated':
						case 'comment_approved':
						case 'comment_reply':
						case 'comment_deleted':
							if($comment = strip_tags($note->params->get('comment')))
							{
								if(JString::strlen($comment) > 50)
								{
									$comment = JString::substr($comment, 0, 50) . '...';
								}
								$event .= '<p>' . $comment . '<p>';
							}
							break;

						case 'record_approved':
						case 'record_wait_approve':
						case 'record_new':
						case 'record_edited':
						case 'record_rated':
						case 'record_expired':
						case 'record_featured_expired':
						case 'record_bookmarked':
						case 'record_tagged':
						case 'record_featured':
						case 'record_extended':
						case 'record_unpublished':
						case 'record_view':
						case 'record_deleted':
						case 'child_attached':
						case 'parent_attached':
							$section = ItemsStore::getSection($note->ref_2);
							$event = ucfirst(JText::sprintf('alert_event_'.$note->type, strtolower($section->params->get('general.item_label', 'item')))) . ' <small>(' . JHtml::_('date', $note->ctime, 'd M Y H:i') . ')</small>';
							break;

					}

					$li[] = $event;

					$num++;
					$note_ids[] = $note->id;
				}

				$list[] = '<ul><li>' . implode('</li><li>', $li) . '</li></ul>';
			}

			$subject = JText::sprintf('ALERT_SUBJECT_DAIJEST_' . $type, $num) . ' ' . $config->get('sitename');
			$text    = str_replace('[EVENTS]', implode("\n", $list), $text);
			$text    = str_replace(
				array(
					'[BRAND]', '[USER]', '[NUM]'
				),
				array(
					$config->get('sitename'), JFactory::getUser($user_id)->get('name'), $num
				), $text);

			$result = JFactory::getMailer()->sendMail($config->get('mailfrom'), $config->get('fromname'), JFactory::getUser($user_id)->get('email'), $subject, $text, TRUE);
			if($result)
			{
				if($note_ids)
				{
					$sql = "UPDATE #__js_res_notifications SET alerted = 1 WHERE id IN (" . implode(',', $note_ids) . ")";
					$db->setQuery($sql);
					$db->execute();
				}

				$sql = "UPDATE #__js_res_user_options SET lastsend = NOW() WHERE user_id = {$user_id}";
				$db->setQuery($sql);
				$db->execute();

			}
		}
	}
}
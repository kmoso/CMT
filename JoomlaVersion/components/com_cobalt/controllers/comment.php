<?php
/**
 * Cobalt by MintJoomla
 * a component for Joomla! 1.7 - 2.5 CMS (http://www.joomla.org)
 * Author Website: http://www.mintjoomla.com/
 * @copyright Copyright (C) 2012 MintJoomla (http://www.mintjoomla.com). All rights reserved.
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */

defined('_JEXEC') or die();

jimport('joomla.application.component.controllerform');

class CobaltControllerComment extends JControllerForm
{

	public function __construct($config = array())
	{
		parent::__construct($config);

		if(!$this->input)
		{
			$this->input = JFactory::getApplication()->input;
		}
	}
	public function save($key = null, $urlVar = null)
	{

		$this->view_list = 'record';
		$this->view_item = 'record';

		$form = $this->input->get('jform', array(), 'array');

		$user = JFactory::getUser();
		$record = JModelLegacy::getInstance('Record', 'CobaltModel')->getItem($form['record_id']);
		$section = JModelLegacy::getInstance('Section', 'CobaltModel')->getItem($record->section_id);
		$type = JModelLegacy::getInstance('Form', 'CobaltModel')->getRecordType($record->type_id);

		CEmeraldHelper::allowType('comment', $type, $user->id, $section, true, '', $record->user_id);

		if (!empty($form['id']) || $this->input->getCmd('view') == 'comment')
		{
			$this->view_list = 'comment';
			$this->view_item = 'comment';
			$this->input->set('id', $form['id']);
		}
		else
			$this->input->set('id', 0);

		parent::save($key, $urlVar);
	}

	public function postSaveHook(JModelLegacy $model, $validData = array())
	{

		$record = JTable::getInstance('Record', 'CobaltTable');
		$record->load($validData['record_id']);
		$validData['record'] = $record->getProperties();

		if ($this->input->getInt('is_new'))
		{
			CEventsHelper::notify('record', CEventsHelper::_COMMENT_NEW, $validData['record_id'], $this->input->getInt('section_id'), $this->input->getInt('cat_id'), $model->getState('comment.id'), 0, $validData);
			ATlog::log($record, ATlog::COM_NEW, $model->getState('comment.id'));

			//$type = ItemsStore::getType($validData['record']['type_id']);
			//CEmeraldHelper::countLimit('type', 'comment', $type, JFactory::getUser()->get('id'));
		}
		else if ($this->input->getInt('is_edited'))
		{
			CEventsHelper::notify('record', CEventsHelper::_COMMENT_EDITED, $validData['record_id'], $this->input->getInt('section_id'), $this->input->getInt('cat_id'), $model->getState('comment.id'), 0, $validData);
			ATlog::log($record, ATlog::COM_EDIT, $model->getState('comment.id'));
		}
		else
		{
			if ($validData['parent_id'])
			{
				$comment = JTable::getInstance('Cobcomments', 'CobaltTable');
				$comment->load($validData['parent_id']);

				if ($comment->user_id)
				{
					CEventsHelper::notify('record', CEventsHelper::_COMMENT_REPLY, $validData['record_id'], $this->input->getInt('section_id'), $this->input->getInt('cat_id'), $validData['parent_id'], 0, $validData, 2, $comment->user_id);
				}
			}
			ATlog::log($record, ATlog::COM_NEW, $model->getState('comment.id'));
		}

		if (isset($validData['subscribe']))
		{
			CSubscriptionsHelper::subscribe_record($validData['record_id']);
		}


		$model_record = JModelLegacy::getInstance('Record', 'CobaltModel');
		$model_record->onComment($validData['record_id'], $validData);

		// close popup and reoad page after reply/edit comment
		if($this->input->getCmd('view') == 'comment')
		{
			echo '<script type="text/javascript">parent.window.jQuery("#commentmodal").modal("toggle");parent.window.location.reload();</script>';
			JFactory::getApplication()->close();
		}
	}

	public function cancel($key = null)
	{
		$this->view_list = 'records';
		parent::cancel($key);
	}

	protected function allowSave($data = array(), $key = 'id')
	{
		return TRUE;
	}

	protected function allowAdd($data = array())
	{
		$user = JFactory::getUser();
		$allow = $user->authorise('core.create', 'com_cobalt.comment');

		if ($allow === null)
		{
			return parent::allowAdd($data);
		}
		else
		{
			return $allow;
		}
	}

	protected function allowEdit($data = array(), $key = 'id')
	{
		return JFactory::getUser()->authorise('core.edit', 'com_cobalt.comment');
	}

	protected function getRedirectToItemAppend($recordId = null, $urlVar = 'id')
	{
		return $this->getRedirectToListAppend($recordId = null, $urlVar = 'id');
	}

	protected function getRedirectToListAppend($recordId = null, $urlVar = 'id')
	{

		$tmpl = $this->input->getCmd('tmpl');
		$post_jform = $this->input->get('jform', array(), 'array');
		$record_id = $post_jform['record_id'];
		//		$secton_id		= $this->input->getCmd('section_id');
		$itemId = $this->input->getInt('Itemid');
		$append = '';

		if ($this->view_item == 'comment')
		{
			$append .= '&id=' . $post_jform['id'];
			$append .= '&record_id=' . $post_jform['record_id'];
		}
		else
		{
			$append .= '&id=' . $post_jform['record_id'];
		}

		if ($tmpl)
		{
			$append .= '&tmpl=' . $tmpl;
		}

		//		if ($type_id) {
		//			$append .= '&type_id='.$type_id;
		//		}
		//
		//		if ($secton_id) {
		//			$append .= '&section_id='.$secton_id;
		//		}


// 		if ($record_id)
// 		{
// 			$append .= '&id=' . $record_id;
// 		}

		if ($itemId)
		{
			$append .= '&Itemid=' . $itemId;
		}

		return $append;
	}

}

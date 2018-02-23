<?php
/**
 * Cobalt by MintJoomla
 * a component for Joomla! 1.7 - 2.5 CMS (http://www.joomla.org)
 * Author Website: http://www.mintjoomla.com/
 * @copyright Copyright (C) 2012 MintJoomla (http://www.mintjoomla.com). All rights reserved.
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */

defined('_JEXEC') or die();

jimport('joomla.application.component.modeladmin');

class CobaltModelComment extends JModelAdmin
{

	public function getTable($type = 'Cobcomments', $prefix = 'CobaltTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	public function getForm($data = array(), $loadData = true)
	{
		$form = $this->loadForm('com_cobalt.comment', 'comment', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form))
		{
			return false;
		}
		return $form;
	}

	protected function populateState()
	{
		parent::populateState();

		$app = JFactory::getApplication();

		// Load state from the request.
		if($app->input->getString('view', false) == 'comment')
		{
			$pk = $app->input->getInt('id');
			JFactory::getApplication()->setUserState('com_cobalt.edit.comment.id', $pk);
			$this->setState('com_cobalt.edit.comment.id', $pk);
		}
		else
		{
			$this->setState($this->getName() . '.id', null);
		}
		$return = $app->input->get('return', null, 'base64');
		$this->setState('return_page', CobaltFilter::base64($return));
	}

	protected function loadFormData()
	{
		$data = JFactory::getApplication()->getUserState('com_cobalt.edit.comment.data', array());
		$data2 = $this->getItem();
		if (empty($data))
		{
			$data = $data2;
		}
		else
		{
			if($data['id'] != $data2->id)
			{
				$data = $data2;
			}
		}
		return $data;
	}

	public function getItem($pk = null)
	{
		$app = JFactory::getApplication();
		$item = parent::getItem($pk);
		$user = JFactory::getUser();
		if (!isset($item->id) && !$item->id)
		{
			$type = ItemsStore::getType($app->input->getInt('type_id'));

			//$item = new stdClass();
			$item->id = 0;
			$item->section_id = $app->input->getInt('section_id');
			$item->record_id = ($app->input->get('view') == 'comment') ? $app->input->getInt('record_id') : $app->input->getInt('id');
			//var_dump($item->record_id);exit;
			//			$item->published = $type->params->get('submission.autopublish', 1);
			$record = ItemsStore::getRecord($item->record_id);
			$type = ItemsStore::getType($record->type_id);

			$item->type_id = $type->id;
			$item->access = $type->params->get('comments.comments_access_view', 1);
			$item->user_id = $user->get('id');
			$item->email = $user->get('email');
			$item->name = $user->get('name');
			$item->parent_id = $app->input->getInt('parent_id', 1);
			$item->published = 1;
			$item->private = 0;
			$item->follow = 1;
		}

		$type = ItemsStore::getType($item->type_id);
		$item = $this->_prepareComent($item, $type, $user);
		return $item;
	}

	protected function canDelete($record)
	{
		$model = JModelLegacy::getInstance('Form', 'CobaltModel');
		$cmodel = JModelLegacy::getInstance('Comment', 'CobaltModel');
		$item = $model->getItem(JFactory::getApplication()->input->getInt('record_id'));
		$type = $model->getRecordType($item->type_id);
		$cid = JFactory::getApplication()->input->get('cid');
		if(!isset($cid[0]))
		{
			return FALSE;
		}
		$comment = $cmodel->getItem($cid[0]);
		$user = JFactory::getUser();

		if (($item->user_id == $user->get('id')) && $type->params->get('comments.comments_approve_author'))
		{
			return TRUE;
		}

		if (($comment->user_id == $user->get('id')) && $type->params->get('comments.comments_comment_author_delete'))
		{
			return TRUE;
		}

		if (in_array($type->params->get('comments.comments_access_moderate'), $user->getAuthorisedViewLevels()))
		{
			return TRUE;
		}

		return FALSE;
		return $user->authorise('core.delete', 'com_cobalt.comment.' . (int)$record->id);
	}

	protected function canEditState($record)
	{
		$user = JFactory::getUser();
		return $user->authorise('core.edit.state', 'com_cobalt.comment.' . (int)$record->id);
	}



	public function subscribe($data)
	{
		$table = JTable::getInstance('Subscription', 'CobaltTable');
		$model_record = JModelLegacy::getInstance('Record', 'CobaltModel');
		$user = JFactory::getUser();

		$item = $model_record->getItem($data['record_id']);

		$array = array('type' => 'record', 'user_id' => $user->get('id'), 'record_id' => $item->id);

		$table->load($array);

		if (!$table->id)
		{
			$array['id'] = NULL;
			$array['section_id'] = $item->section_id;
			$array['type_id'] = $item->type_id;

			$table->reset();
			$table->save($array);
		}

	}

	private function _prepareComent($comment, $type, $user)
	{
        $comment->created = JFactory::getDate($comment->ctime);

        $comment->attachment = AttachmentHelper::getAttachments($comment->attachment, $type->params->get('comments.comments_attachment_hit', 0));

		/* if(!$comment->user_id)
		{
			$comment->name = $comment->name;
			$comment->username = $comment->name;
			$comment->email = $comment->email;
		}
		else
		{
			$comment->name = $comment->c_name;
			$comment->username = $comment->c_username;
			$comment->email = $comment->c_email;
		} */
		$comment->avatar = 0;
		$comment->candelete = 0;
		$comment->canmoderate = 0;
		$comment->canedit = 0;

		if($comment->user_id)
		{
			if($comment->user_id == $user->get('id') && $type->params->get('comments.comments_comment_author_delete'))
			{
				$comment->candelete = TRUE;
			}
			if($comment->user_id == $user->get('id') && $type->params->get('comments.comments_approve_author'))
			{
				$comment->candelete = TRUE;
			}
		    if($comment->user_id == $user->get('id') && $type->params->get('comments.comments_approve_author'))
			{
				$comment->canmoderate = TRUE;
			}
		    if($comment->user_id == $user->get('id') && $type->params->get('comments.comments_approve_author'))
			{
				$comment->canedit = TRUE;
			}
			if($comment->user_id == $user->get('id') && $type->params->get('comments.comments_comment_author_edit'))
			{
				$comment->canedit = TRUE;
			}
		}
		if(MECAccess::allowCommentModer($type, $comment->record_id))
		{
			$comment->candelete = TRUE;
			$comment->canmoderate = TRUE;
			$comment->canedit = TRUE;
		}

		$comment->canrate = RatingHelp::canRate('comment', $comment->user_id, $comment->id, $type->params->get('comments.comments_rate_rate', 1));

		return $comment;
	}
	public function validate($form, $data, $group = null)
	{
		if(JFactory::getUser()->get('id') )
		{
			$form->removeField('captcha');
		}

		return parent::validate($form, $data, $group);
	}
}
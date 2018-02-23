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

class CobaltViewRecord extends JViewLegacy
{
	function display($tpl = null)
	{
		$app = JFactory::getApplication();
		$doc = JFactory::getDocument();

		JFactory::getApplication()->input->set('limit', $app->getCfg('feed_limit'));
		$feedEmail	= (@$app->getCfg('feed_email')) ? $app->getCfg('feed_email') : 'author';

		$record = $this->get('Item');

		$app->input->set('section_id', $record->section_id);
		$app->input->set('type_id', $record->type_id);

        $type = ItemsStore::getType($record->type_id);

		$model_comments = JModelLegacy::getInstance('Comments', 'CobaltModel');
        $model_comments->item = $record;
        $model_comments->type = $type;
        $model_comments->getState();
        $model_comments->setState('record.type', $type);
        $model_comments->setState('record.item', $record);
        $model_comments->setState('record.id', $record->id);
        $model_comments->setState('list.limit', 20);
		$comments = $model_comments->getItems($record->id, $type, 20);

		$link = JURI::root(TRUE).'/index.php?option=com_cobalt&view=record&id='.$record->id;
		$doc->link = $this->escape(JRoute::_($link));
		$doc->title = $record->title;
		$doc->description = JText::sprintf('CRSSFEEDCOMMENT', $record->title);

		foreach ($comments as $row)
		{
			// strip html from feed item title
			$title = $this->escape(substr($row->comment, 0, 25));
			$title = html_entity_decode($title, ENT_COMPAT, 'UTF-8');

			$url = $this->escape($link.'#comment'.$row->id);

			$description	= $row->comment;
			$author			= CCommunityHelper::getName($row->user_id, $row->section_id, array('nohtml' => 1));
			$date			= $row->created->toISO8601();


			// load individual item creator class
			$item = new JFeedItem();
			$item->title		= $title;
			$item->link			= $url;
			$item->description	= $description;
			$item->date			= $date;

			$item->author		= $author;
			if ($feedEmail == 'site') {
				$item->authorEmail = $app->getCfg('mailfrom');
			}
			else {
				$item->authorEmail = $row->email;
			}
			//var_dump($item); exit;

			// loads item info into rss array
			$doc->addItem($item);
		}
	}
}
?>
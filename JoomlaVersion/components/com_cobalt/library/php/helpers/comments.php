<?php
/**
 * Cobalt by MintJoomla
 * a component for Joomla! 1.7 - 2.5 CMS (http://www.joomla.org)
 * Author Website: http://www.mintjoomla.com/
 * @copyright Copyright (C) 2012 MintJoomla (http://www.mintjoomla.com). All rights reserved.
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die();

include_once dirname(__DIR__).'/cobaltcomments.php';

class CommentHelper {
	public static function listComments($type, $item)
	{
		return self::_getclass($type->params->get('comments.comments'))->getComments($type, $item);
	}
	public static function numComments($type, $item)
	{
		return self::_getclass($type->params->get('comments.comments'))->getNum($type, $item);
	}
	public static function fullText($type, $item)
	{
		return self::_getclass($type->params->get('comments.comments'))->getIndex($type, $item);
	}

	private static function _getclass($name)
	{
		$file = JPATH_ROOT. '/components/com_cobalt/library/php/comments/'.$name. DIRECTORY_SEPARATOR .$name.'.php';
		if(JFile::exists($file))
		{
			include_once $file;
			$name = 'CobaltComments'.ucfirst($name);
			if(class_exists($name))
			{
				return new $name();
			}
		}

		return new CobaltComments();
	}
}
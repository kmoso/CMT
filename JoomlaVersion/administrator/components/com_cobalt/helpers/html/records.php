<?php
/**
 * Cobalt by MintJoomla
 * a component for Joomla! 1.7 - 2.5 CMS (http://www.joomla.org)
 * Author Website: http://www.mintjoomla.com/
 * @copyright Copyright (C) 2012 MintJoomla (http://www.mintjoomla.com). All rights reserved.
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */

defined('_JEXEC') or die;

/**
 * @package		Joomla.Administrator
 * @subpackage	com_content
 */
abstract class JHtmlRecords
{
	/**
	 * @param	int $value	The state value
	 * @param	int $i
	 */
	static function featured($value = 0, $i, $canChange = true)
	{
		// Array of image, task, title, action
		$states	= array(
			0	=> array('<i class="icon-star-empty"></i>',	'records.featured',	'COM_COBALT_TOGGLE_TO_FEATURE'),
			1	=> array('<i class="icon-star"></i>',		'records.unfeatured','COM_COBALT_TOGGLE_TO_UNFEATURE'),
		);
		$state	= JArrayHelper::getValue($states, (int) $value, $states[1]);

		if ($canChange) {
			$html	= '<a href="#" class="btn btn-micro" onclick="return listItemTask(\'cb'.$i.'\',\''.$state[1].'\')" rel="tooltip" data-original-title="'.JText::_($state[2]).'">'
					. $state[0].'</a>';
		}

		return $html;
	}
}

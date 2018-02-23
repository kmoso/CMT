<?php
/**
 * @package         Content Templater
 * @version         6.2.3
 * 
 * @author          Peter van Westen <info@regularlabs.com>
 * @link            http://www.regularlabs.com
 * @copyright       Copyright © 2016 Regular Labs All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');

/**
 * Item Controller
 */
class ContentTemplaterControllerItem extends JControllerForm
{
	/**
	 * @var        string    The prefix to use with controller messages.
	 */
	protected $text_prefix = 'RL';
	// Parent class access checks are sufficient for this controller.
}

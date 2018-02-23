<?php
/**
 * Cobalt by MintJoomla
 * a component for Joomla! 1.7 - 2.5 CMS (http://www.joomla.org)
 * Author Website: http://www.mintjoomla.com/
 * @copyright Copyright (C) 2012 MintJoomla (http://www.mintjoomla.com). All rights reserved.
 * @license   GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */

defined('_JEXEC') or die('Restricted access');

include_once dirname(dirname(__FILE__)) . '/com_cobalt/com_cobalt.php';

class CCommunityCom_jsn extends CCommunityCom_cobalt
{

	public function getRegistrationLink()
	{
		return 'index.php?option=com_users&view=registration';
	}

	public function getLoginLink()
	{
		return 'index.php?option=com_users&view=login';
	}

	public function getName($id, $name, $section)
	{
		$this->_load();
		$user = new JsnUser($id);

		$out[0]['url'] = $user->getLink();
		$out[0]['label'] = HTMLFormatHelper::icon('user-silhouette.png') . ' ' . JText::_('CUSERPOFILE');

		return $out;
	}

	public function getAvatar($user_id)
	{
		$this->_load();

		$user = new JsnUser($user_id);
		return $user->getValue('avatar');
	}

	private function _load()
	{
		$file = JPATH_ROOT . '/components/com_jsn/helpers/helper.php';

		if(!JFile::exists($file))
		{
			throw new Exception("You have enabled Easy Profile (com_jsn) in Cobalt as profile integration. But we could not find this extension.");
		}

		require_once $file;

	}
}
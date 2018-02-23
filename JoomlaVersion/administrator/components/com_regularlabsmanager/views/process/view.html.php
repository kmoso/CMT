<?php
/**
 * @package         Regular Labs Extension Manager
 * @version         6.1.1
 * 
 * @author          Peter van Westen <info@regularlabs.com>
 * @link            http://www.regularlabs.com
 * @copyright       Copyright © 2016 Regular Labs All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * View for the install processes
 */
class RegularLabsManagerViewProcess extends JViewLegacy
{
	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		$action = JFactory::getApplication()->input->get('action');
		if ($action)
		{
			$model = $this->getModel();

			switch ($action)
			{
				case 'uninstall':
					$model->uninstall(JFactory::getApplication()->input->get('id'));
					break;

				case 'install':
				default:
					$model->install(JFactory::getApplication()->input->get('id'), JFactory::getApplication()->input->getString('url'));
					break;
			}

			parent::display('empty');

			return;
		}

		$this->items = $this->get('Items');
		$this->getConfig();

		parent::display($tpl);
	}

	/**
	 * Function that gets the config settings
	 */
	protected function getConfig()
	{
		if (!isset($this->config))
		{
			require_once JPATH_LIBRARIES . '/regularlabs/helpers/parameters.php';
			$parameters   = RLParameters::getInstance();
			$this->config = $parameters->getComponentParams('regularlabsmanager');
		}

		return $this->config;
	}
}

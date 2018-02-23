<?php
/**
 * Cobalt by MintJoomla
 * a component for Joomla! 1.7 - 2.5 CMS (http://www.joomla.org)
 * Author Website: http://www.mintjoomla.com/
 * @copyright Copyright (C) 2012 MintJoomla (http://www.mintjoomla.com). All rights reserved.
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */

defined('_JEXEC') or die;
jimport('joomla.application.component.controlleradmin');


class CobaltControllerUsercategories extends JControllerAdmin
{

	public function __construct($config = array())
	{
		parent::__construct($config);
		
		if(!$this->input)
		{
			$this->input = JFactory::getApplication()->input;
		}
        $this->view_list = 'categories';
	}
    
    public function &getModel($name = 'Usercategory', $prefix = 'CobaltModel')
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}
	
	public function saveOrderAjax()
	{
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
	
		// Get the arrays from the Request
		$pks   = $this->input->post->get('cid', null, 'array');
		$order = $this->input->post->get('order', null, 'array');
		$originalOrder = explode(',', $this->input->getString('original_order_values'));
	
		// Make sure something has changed
		if (!($order === $originalOrder)) {
			// Get the model
			$model = $this->getModel();
			// Save the ordering
			$return = $model->saveorder($pks, $order);
			if ($return)
			{
				echo "1";
			}
		}
		// Close the application
		JFactory::getApplication()->close();
	
	}
}
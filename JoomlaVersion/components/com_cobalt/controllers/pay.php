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
class CobaltControllerPay extends JControllerAdmin
{

	public function __construct($config = array())
	{
		parent::__construct($config);

		if(!$this->input)
		{
			$this->input = JFactory::getApplication()->input;
		}
	}

	public function receivePayment()
	{
		$processor = $this->input->get('processor', false);

        if(!$processor)
        {
            return;
        }

		$gatewaypath = JPATH_COMPONENT.'/gateways/';
		$file = $gatewaypath.'/'.$processor.'/'.$processor.'.php';
        require_once $file;
		$classname = 'MintPay'.ucfirst($processor);
        $gateway = new $classname();

        $this->input->set('field_id', $gateway->getFieldId($this->input));
        $this->input->set('record_id', $gateway->getRecordId($this->input));
        $this->input->set('func', 'onReceivePayment');

        if(!class_exists('CobaltControllerField'))
        {
        	require_once JPATH_ROOT.'/components/com_cobalt/controllers/field.php';
        }

        $controller = new CobaltControllerField(array());
        $controller->call();
	}

    public function returnUrl()
    {
        $processor = $this->input->get('processor', false);
        $result = $this->input->get('result', 'success');

        if(!$processor)
        {
            return;
        }

        $gatewaypath = JPATH_COMPONENT.'/gateways/';
        $file = $gatewaypath.'/'.$processor.'/'.$processor.'.php';
        require_once $file;
        $classname = 'MintPay'.ucfirst($processor);
        $gateway = new $classname();

        $record_id = $gateway->getRecordId($this->input);

        $url = Url::record($record_id);
        $this->setRedirect($url, JText::_('RESULT_'.strtoupper($result)));
        $this->redirect();
    }
}

<?php
/**
 * Cobalt by MintJoomla
 * a component for Joomla! 1.7 - 2.5 CMS (http://www.joomla.org)
 * Author Website: http://www.mintjoomla.com/
 * @copyright Copyright (C) 2012 MintJoomla (http://www.mintjoomla.com). All rights reserved.
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */

defined('_JEXEC') or die();

jimport('joomla.application.component.controller');
jimport('joomla.application.component.controllerform');
jimport('joomla.utilities.simplexml');

require_once JPATH_COMPONENT_ADMINISTRATOR. DIRECTORY_SEPARATOR .'helpers'. DIRECTORY_SEPARATOR .'templates.php';

class CobaltControllerTemplates extends JControllerForm
{
	public function __construct($config = array())
	{
		parent::__construct($config);

		if(!$this->input)
		{
			$this->input = JFactory::getApplication()->input;
		}
		$this->registerTask('saveclose', 'save');
	}

	public function getModel($name = 'Templates', $prefix = 'CobaltBModel', $config = array())
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}

	public function install()
	{
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$app = JFactory::getApplication();
		$uri = JFactory::getURI();
		$uri->setVar('tab', $this->input->get('tab'));

		$model	= $this->getModel();

		if ($model->install()) {
			$cache = JFactory::getCache('mod_menu');
			$cache->clean();

			$app->enqueueMessage(JText::_('C_MSG_TMPLINSTALLOK'));
		}

		$app->redirect($uri->toString());
	}

	public function uninstall()
	{
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$app = JFactory::getApplication();
		$uri = JUri::getInstance();
		$uri->setVar('tab', $this->input->get('tab'));

		$tmpls = $this->input->get('cid', array(), 'array');

		foreach( $tmpls as $k => $tmpl ) {
			$matches = Array();
			preg_match ( "/^\[(.*)\]\,\[(.*)\]$/i", $tmpl, $matches );

			if( $matches[1] == 'default' ){
				JError::raiseWarning( 500, JText::_('C_MSG_DEAFULEUNINSTALL') );
				unset($tmpls[$k]);
			}
		}
		if(!$tmpls)
		{
			JError::raiseWarning(100, JText::_('C_MSG_CHOSETEMPL'));
			$app->redirect($uri->toString());
			return;
		}

		$model = $this->getModel();
		$model->uninstall($tmpls);

		$this->setRedirect($uri->toString(), JText::_('C_MSG_TMPLUNINSTALLOK'));
		$this->redirect();
	}

	public function rename()
	{
		$this->copy('rename');
	}
	public function copy($func = 'copy')
	{
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$app = JFactory::getApplication();
		$uri = JFactory::getURI();
		$uri->setVar('tab', $this->input->get('tab'));

		$new_name = $this->input->get('tmplname');
		$tmpls = $this->input->get('cid', array(), 'array');

		$model = $this->getModel();

		if(!$model->$func($tmpls[0], $new_name)){
			JError::raiseWarning(100, ($func == 'copy' ? JText::_('C_MSG_TMPLCOPYFAIL') : JText::_('C_MSG_TMPLRENAMEFAIL')));
			$app->redirect($uri->toString());
		}

		$this->setRedirect($uri->toString(), ($func == 'copy' ? JText::_('C_MSG_TMPLCOPYOK') : JText::_('C_MSG_TMPLRENAMEOK')));
		$this->redirect();
	}


	public function change_label()
	{
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$tmpls = $this->input->get('cid', array(), 'array');
		$uri = JFactory::getURI();
		$uri->setVar('tab', $this->input->get('tab'));
		$app = JFactory::getApplication();

		$matches = Array();
		preg_match ( "/^\[(.*)\]\,\[(.*)\]$/i", $tmpls[0], $matches );

		$new_name = $this->input->getString('tmpl_name');
		if (!$new_name)
		{
			JError::raiseWarning(100, JText::_('C_MSG_TMPLNO_NONAMEENTER'));
			$app->redirect($uri->toString());
			return;
		}

		$file = JPATH_ROOT. DIRECTORY_SEPARATOR .'components'. DIRECTORY_SEPARATOR .'com_cobalt'. DIRECTORY_SEPARATOR .'views'. DIRECTORY_SEPARATOR .'records'. DIRECTORY_SEPARATOR .'tmpl'. DIRECTORY_SEPARATOR .'default_list_'.$matches[1].'.xml';

		$model = $this->getModel();
		if(!$model->change_name($file, $new_name))
		{
			JError::raiseWarning(100, JText::_('C_MSG_TMPLSAVEFAIL'));
			$app->redirect($uri->toString());
			return;
		}
		$app->enqueueMessage(JText::_('C_MSG_TMPLLABELCHANGEOK'));
		$app->redirect($uri->toString());
	}
	public function save($key = null, $urlVar = null)
	{
		$uri = JFactory::getURI();
		$app = JFactory::getApplication();
		$task = $this->input->getCmd('task');
		$type = $this->input->get('type');
		$name = $this->input->get('name');
		$config = $this->input->get('config');

		$regestry = new JRegistry();
		$regestry->loadArray($this->input->get('jform', array(), 'array'));

		$file = MRtemplates::getTmplFile($type, $name, true).'.'.$config.'.json';

		$reg_string = $regestry->toString();
		JFile::write($file,  $reg_string);

		$msg = JText::_('C_MSG_TMPLPARAMS_SAVEOK');
		switch ( $task ) {
			case 'saveclose':
				if($this->input->get('return'))
				{
					$url = base64_decode($this->input->getBase64('return'));
				}
				else
				{
					$uri->setVar('close', 1);
					$url = $uri->toString();
				}
				break;
			case 'save':
			case 'apply':
			default:
				if($this->input->get('return'))
				{
					$uri->setVar('return', $this->input->getBase64('return'));
				}
				$url = $uri->toString();
			break;
		}

		$app->enqueueMessage($msg);
		$app->redirect($url);
	}
	public function cancel($key = null)
	{
		if($this->input->get('return'))
		{
			$url = base64_decode($this->input->getBase64('return'));
		}

		$this->setRedirect($url, JText::_('C_MSG_TMPLEDITCANCEL'));
		$this->redirect();
	}
}
?>
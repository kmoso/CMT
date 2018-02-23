<?php
/**
 * Cobalt by MintJoomla
 * a component for Joomla! 1.7 - 2.5 CMS (http://www.joomla.org)
 * Author Website: http://www.mintjoomla.com/
 * @copyright Copyright (C) 2012 MintJoomla (http://www.mintjoomla.com). All rights reserved.
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined( '_JEXEC' ) or die( 'Restricted access' );

class plgMintFormatter_joomlaupdate extends JPlugin
{
	private $tmpl_path;

	public function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);
		$this->loadLanguage();
		$this->tmpl_path = dirname(__FILE__).DIRECTORY_SEPARATOR.'tmpl'.DIRECTORY_SEPARATOR;
	}

	function onListFormat($view)
	{
		if(JFactory::getApplication()->input->get('formatter') != 'joomlaupdate') return;
		//$this->sendHeader();
		require $this->tmpl_path.'list'.DIRECTORY_SEPARATOR.$this->params->get('list_tpl', 'default.php');
	}

	function onRecordFormat($view)
	{
		if(JFactory::getApplication()->input->get('formatter') != 'joomlaupdate') return;
		$this->sendHeader();
		require $this->tmpl_path.'record'.DIRECTORY_SEPARATOR.$this->params->get('record_tpl', 'default.php');
	}

	function sendHeader()
	{
		JResponse::setHeader('Content-type', 'text/xml');
		//header('Content-Type: text/xml');
	}
}
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

require_once __DIR__ . '/script.install.helper.php';

class PlgSystemContentTemplaterInstallerScript extends PlgSystemContentTemplaterInstallerScriptHelper
{
	public $name           = 'CONTENT_TEMPLATER';
	public $alias          = 'contenttemplater';
	public $extension_type = 'plugin';

	public function uninstall($adapter)
	{
		$this->uninstallComponent($this->extname);
		$this->uninstallPlugin($this->extname, 'editors-xtd');
	}
}

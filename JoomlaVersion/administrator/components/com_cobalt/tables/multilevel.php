<?php
/**
 * Cobalt by MintJoomla
 * a component for Joomla! 1.7 - 2.5 CMS (http://www.joomla.org)
 * Author Website: http://www.mintjoomla.com/
 * @copyright Copyright (C) 2012 MintJoomla (http://www.mintjoomla.com). All rights reserved.
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */

defined('_JEXEC') or die('Restricted access');

jimport('joomla.database.tablenested');

class CobaltTableMultilevel extends JTableNested
{

	public function __construct(&$_db)
	{
		parent::__construct('#__js_res_field_multilevelselect', 'id', $_db);
	}

	
	public function delete($pk = null, $children = true)
	{
		$res = parent::delete($pk);
		if($res)
		{
			/*$db = $this->getDbo();
			$query = 'SELECT id,record_id FROM #__js_res_record_category WHERE catid = '.$pk;
			$db->setQuery($query);
			$result = $db->loadObjectList('id');*/
		}
		
		return $res;
	}
}
?>

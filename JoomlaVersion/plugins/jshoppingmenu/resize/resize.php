<?php
defined('_JEXEC') or die('Restricted access');
?>
<?php
class plgJshoppingMenuResize extends JPlugin
{
	function plgJshoppingOrderUpdateResize(&$subject, $config){
        JSFactory::loadExtLanguageFile('resize');
		parent::__construct($subject, $config);
		if (!isset($this->params)){
			$plugin =& JPluginHelper::getPlugin('jshoppingmenu', 'resize');
			$this->params = new JParameter( $plugin->params );
		}
    }
    
    function onBeforeAdminOptionPanelMenuDisplay(&$menu)
    	{JSFactory::loadExtLanguageFile('resize');
   		$menu['resize'] = array(_JSHOP_RESIZE, 'index.php?option=com_jshopping&controller=resize', 'refresh.png', 1);   	
    	}
    function onBeforeAdminOptionPanelIcoDisplay(&$menu)
    	{JSFactory::loadExtLanguageFile('resize');
   		$menu['resize'] = array(_JSHOP_RESIZE, 'index.php?option=com_jshopping&controller=resize', 'refresh.png', 1);    	
    	}
}
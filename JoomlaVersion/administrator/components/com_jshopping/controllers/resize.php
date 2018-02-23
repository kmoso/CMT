<?php
/**
* @version      1.1.1 06.10.2011
* @author       MAXXmarketing GmbH
* @package      Jshopping
* @copyright    Copyright (C) 2010 webdesigner-profi.de. All rights reserved.
* @license      GNU/GPL
*/

defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.controller');

class JshoppingControllerResize extends JControllerLegacy{
    
    function __construct( $config = array() ){
        JSFactory::loadExtLanguageFile('resize');
        parent::__construct( $config );
        addSubmenu("other");
    }

    function display($cachable = false, $urlparams = false){ 
        $db = JFactory::getDBO();
        $view=$this->getView("resize", 'html');      
        $view->display();
    }
   
    function resize(){
        $mainframe =& JFactory::getApplication(); 
        $jshopConfig = &JSFactory::getConfig();
        
        require_once ($jshopConfig->path.'lib/image.lib.php');
        
        JPluginHelper::importPlugin('jshoppingadmin');
        $dispatcher =& JDispatcher::getInstance();            
        
        $filelist =  glob($jshopConfig->image_product_path .'/'.'full_*');
        foreach($filelist as $key=>$value){                                               
            $url = $value;
            $url_parts = pathinfo($url);
            //filenames
            $name_full = $url_parts['basename'];
            $name_image = substr($url_parts['basename'],5);
            $name_thumb = "thumb_".$name_image;
            //file path
            $path_image = $jshopConfig->image_product_path .'/'.$name_image;
            $path_thumb = $jshopConfig->image_product_path .'/'.$name_thumb;
            $path_full = $jshopConfig->image_product_path .'/'.$name_full;
            //resize thumb
            $product_width_image = $jshopConfig->image_product_width;
            $product_height_image = $jshopConfig->image_product_height;            
            if (!ImageLib::resizeImageMagic($path_full, $product_width_image, $product_height_image, $jshopConfig->image_cut,$jshopConfig->image_fill, $path_thumb, $jshopConfig->image_quality, $jshopConfig->image_fill_color)) {
                JError::raiseWarning("",_JSHOP_ERROR_CREATE_THUMBAIL." ".$name_thumb);
                saveToLog("error.log", "Resize Product Image - Error create thumbail ".$name_thumb);
                $error = 1;
            }
            //resize image
            $product_full_width_image = $jshopConfig->image_product_full_width; 
            $product_full_height_image = $jshopConfig->image_product_full_height;            
            if (!ImageLib::resizeImageMagic($path_full, $product_full_width_image, $product_full_height_image, $jshopConfig->image_cut,$jshopConfig->image_fill, $path_image, $jshopConfig->image_quality, $jshopConfig->image_fill_color)) {
                JError::raiseWarning("",_JSHOP_ERROR_CREATE_THUMBAIL." ".$name_image);
                saveToLog("error.log", "Resize Product Image - Error create image ".$name_image);
                $error = 1;
            } 
            $dispatcher->trigger('onAfterSaveProductImage', array($product_id, $name_image));    
        }
         
        if (!JRequest::getInt("noredirect")){
            $mainframe->redirect("index.php?option=com_jshopping&controller=resize&task=view", _JSHOP_COMPLETED);
        }
    }
}
?>
<?php
/**
 * Torbara Maxx-Fitness Template for Joomla, exclusively on Envato Market: http://themeforest.net/user/torbara
 * @encoding     UTF-8
 * @version      1.0
 * @copyright    Copyright (C) 2015 Torbara (http://torbara.com). All rights reserved.
 * @license      GNU General Public License version 2 or later, see http://www.gnu.org/licenses/gpl-2.0.html
 * @author       Alexandr Khmelnytsky (support@torbara.com)
 */

$path = JURI::root().'templates/sport.ak';

?>
<p class="uk-text-small"><b>Announcement about working hours</b><br>
Torbara Support Team provides you with professional assistance in case you have some issues using our products or you need some advises in your work. Feel free to contact us through our Support Chat or by email <a href="mailto:support@torbara.com">support@torbara.com</a>. Please be aware we are working to the best of our abilities in order to reply to your request in earliest convenience, but due to busy workflow it might take 24-72 business hours to get reply from us.<br>
We are available from Monday to Friday 9 a.m. – 6 p.m. (GMT +2)<br>
We appreciate your patience and thank you for understanding.<br>
We are working on getting more staff for fast and convenient support 24/7.</p>

<!-- Start SiteHeart code -->
<script>
(function(){
var widget_id = 798973;
_shcp =[{widget_id : widget_id, w_type: 'embed', w_selector: '#tm-siteheart-box', side: 'top', position: 'left', callback : function(chat){ 
    // move sh_button to new palce
    var sh_button = jQuery("#sh_button");
    sh_button.detach();
    jQuery('#tm-siteheart-btn').append(sh_button);
    sh_button.css({'position':'relative','left':'0px','top':'0px'});
    
    setInterval(function (){
        // Logo replace
        if(jQuery(".sh_chat_logo").length){
            jQuery(".sh_chat_logo").after("<img src='<?php echo $path; ?>/warp/config/images/ChatLogo.png' style='margin: 8px;float:left' />");
            jQuery(".sh_chat_logo").remove();
        }
        
        if(jQuery(".sh_logo_img").length){
            jQuery(".sh_logo_img").after("<img src='<?php echo $path; ?>/warp/config/images/ChatLogo.png' style='margin: 6px;float:left' />");
            jQuery(".sh_logo_img").remove();
        }
    }, 200);
} }];
var lang =(navigator.language || navigator.systemLanguage 
|| navigator.userLanguage ||"en")
.substr(0,2).toLowerCase();
var url ="widget.siteheart.com/widget/sh/"+ widget_id +"/"+ lang +"/widget.js";
var hcc = document.createElement("script");
hcc.type ="text/javascript";
hcc.async =true;
hcc.src =("https:"== document.location.protocol ?"https":"http")
+"://"+ url;
var s = document.getElementsByTagName("script")[0];
s.parentNode.insertBefore(hcc, s.nextSibling);
})();
</script>
<!-- End SiteHeart code -->
<div id="tm-siteheart-btn" style="position: relative; z-index: 999;"></div>
<div id="tm-siteheart-box" style="position: relative; z-index: 999;"></div>

<iframe src="http://torbara.com/support-tab-iframe/joomla.html" width="100%" height="800px" id="support-tab-iframe" marginheight="0" frameborder="0"></iframe>

<?php
/**
 * @category  Joomla Component
 * @package   mod_teamdonate
 * @author    torabara.com
 * @copyright 2015 torabara.com
 * @copyright 2011, 2013 Open Source Training, LLC. All rights reserved
 * @contact   www.torabara.com, support@torabara.com
 * @license   http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @version   1.0
        <label class="active">$ 400<input type="radio" name="amount" value="425" /></label>
        <label>$ 350<input type="radio" name="amount" value="370" /></label>
        <label>$ 250<input type="radio" name="amount" value="265" /></label>
 */

// no direct access
defined('_JEXEC') or die();

//load css
$document = JFactory::getDocument();
$document->addStyleSheet(JURI::base() . 'modules/mod_teamdonate/css/style.css');
//Return the selected paypal language from the module parameters
//substr returns part of the string.
//In this case substr starts at the first character and returns 1 more (2 total)
//e.g. substr(en_US, 3, 2); //will return "US"
//instead of using substr, we could have set the local values to just the lower case code.
//e.g. "en_US" could be "US"
$langSite = substr($params->get('locale'), 3, 2);
//$langSite will never be null so if statement will always execute
if (!$langSite) {
    $langSite = 'US';
}

//get intro text if there is any
//need more comments when I have some time
$introtext = '';
if ($params->get('show_text', 1)) {
    $introtext = $params->get('intro_text', '');
}


//need more comments when I have some time
$target = '';
if ($params->get('open_new_window', 1)) {
    $target = 'target="paypal"';
}

$target = '';
if ($params->get('open_new_window', 1)) {
    $target = 'target="paypal"';
}



?>
<script>
    jQuery(document).ready(function() {
        'use strict';
        jQuery('.teamdonate-form label').click(function(){
            jQuery(this).addClass('active').siblings().removeClass('active');
            
        });
    });
</script>
<div class="donate-wrap">
<div class="donate-inner">    
    <h3><span>Pago de</span> Inscripciones</h3>
    <div class="uk-grid">
        <div class="uk-width-8-10 uk-push-1-10 intro-text">
            <?php echo $introtext; ?>
        </div>     
    </div>
<form class="teamdonate-form" action="https://www.paypal.com/cgi-bin/webscr"
      method="post" <?php echo $target; ?>>
    <input type="hidden" name="cmd" value="_xclick"/>
    <input type="hidden" name="business" value="<?php echo $params->get('business', ''); ?>"/>
    <input type="hidden" name="return" value="<?php echo $params->get('return', ''); ?>"/>
    <input type="hidden" name="undefined_quantity" value="0"/>
    <input type="hidden" name="item_name" value="<?php echo $params->get('item_name', ''); ?>"/>
    <input type="hidden" name="amount" value="12" />
    <div class="radio-wrap">
        <label class="active">$ 350<input type="radio" name="amount" value="370" /></label>
        <label>$ 350<input type="radio" name="amount" value="370" /></label>
        <label>$ 350<input type="radio" name="amount" value="370" /></label>
    </div>
    <input type="hidden" name="currency_code" value="MXN" />
    <input type="hidden" name="rm" value="2"/>
    <input type="hidden" name="charset" value="utf-8"/>
    <input type="hidden" name="no_shipping" value="1"/>
    <input type="hidden" name="image_url" value="<?php echo $params->get('image_url', ''); ?>"/>
    <input type="hidden" name="cancel_return" value="<?php echo $params->get('cancel_return', ''); ?>"/>
    <input type="hidden" name="no_note" value="0"/><br/><br/>
    <button class="donate-btn" type="submit" name="submit"><span>Pagar con Paypal</span></button>
   
    <input type="hidden" name="lc" value="<?php echo $langSite; ?>">
</form>
</div>
</div>

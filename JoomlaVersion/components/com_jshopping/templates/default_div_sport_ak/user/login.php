<?php defined( '_JEXEC' ) or die(); ?>
<div class="jshop" id="comjshop">    
	<h1><?php echo _JSHOP_LOGIN ?></h1>

	<?php if ($this->config->shop_user_guest && $this->show_pay_without_reg) {?>
	  <span class="text_pay_without_reg"><?php echo _JSHOP_ORDER_WITHOUT_REGISTER_CLICK ?> <a href="<?php print SEFLink('index.php?option=com_jshopping&controller=checkout&task=step2',1,0, $this->config->use_ssl);?>"><?php echo _JSHOP_HERE ?></a></span>
	<?php } ?>
	<?php echo $this->tmpl_login_html_1?>
	
	<div class="login">
		<div class="login_block">
			<?php echo $this->tmpl_login_html_2?>
			<span class="small_header"><?php echo _JSHOP_HAVE_ACCOUNT ?>.</span>
			<span><?php echo _JSHOP_PL_LOGIN ?></span>
			<form method = "post" action = "<?php print SEFLink('index.php?option=com_jshopping&controller=user&task=loginsave', 0,0, $this->config->use_ssl)?>" name = "jlogin">
				<div id="usernamel">
					<label><?php echo _JSHOP_USERNAME ?>: </label>
					<span><input type = "text" name = "username" value = "" class = "inputbox" /></span>
				</div>
				<div id="passwordl">
					<label><?php echo _JSHOP_PASSWORT ?>: </label>
					<span><input type = "password" name = "passwd" value = "" class = "inputbox" /></span>
				</div>
				<div id="lost_password">
					<label for="remember_me"><?php echo _JSHOP_REMEMBER_ME ?></label><input type="checkbox" name="remember" id="remember_me" value="yes" /><br />
					<input type="submit" class="button" value="<?php echo _JSHOP_LOGIN ?>" /><br />                        
					<a href = "<?php print $this->href_lost_pass ?>"><?php echo _JSHOP_LOST_PASSWORD ?></a>
				</div>
				<input type = "hidden" name = "return" value = "<?php print $this->return ?>" />
				<?php echo JHtml::_('form.token');?>
				<?php echo $this->tmpl_login_html_3?>
			</form>   
		</div>

		<div class="register_block">
			<?php echo $this->tmpl_login_html_4?>
			<span class="small_header"><?php echo _JSHOP_HAVE_NOT_ACCOUNT ?>?</span>
			<span><?php echo _JSHOP_REGISTER ?></span>
			<?php if (!$this->config->show_registerform_in_logintemplate){?>
				<div class="regbutton"><input type="button" class="button" value="<?php echo _JSHOP_REGISTRATION ?>" onclick="location.href='<?php print $this->href_register ?>';" /></div>
			<?php }else{?>
				<?php $hideheaderh1 = 1; include(dirname(__FILE__)."/register.php"); ?>
			<?php }?>
			<?php echo $this->tmpl_login_html_5?>
		</div>        
    </div>
	<?php echo $this->tmpl_login_html_6?>
	<div class="nvg_clear"></div>
</div>    
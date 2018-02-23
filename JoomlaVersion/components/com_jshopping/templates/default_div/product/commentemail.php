<?php defined( '_JEXEC' ) or die(); ?>
<?php echo _JSHOP_PRODUCT?>: <?php echo $this->product_name;?><br/>
<?php echo _JSHOP_REVIEW_USER_NAME?>: <?php echo $this->user_name;?><br/>
<?php echo _JSHOP_REVIEW_USER_EMAIL?>: <?php echo $this->user_email;?><br/>
<?php echo _JSHOP_REVIEW_MARK_PRODUCT?>: <?php echo $this->mark;?><br/>
<?php echo _JSHOP_COMMENT?>:<br/>
<?php print nl2br($this->review)?>
<?php defined( '_JEXEC' ) or die(); ?>
<div class="jshop order_menu" id="jshop_menu_order">
	<?php $i=0; ?>
	<?php foreach($this->steps as $key => $step){?>
		<div class="jshop_order_step <?php print $this->cssclass[$k]?>">
			<div class="num_step_<?php echo $i?>">
				<?php echo $step;?>
			</div>
		</div>
	<?php $i++; ?>
	<?php }?>
</div>
<div class="clear"></div>
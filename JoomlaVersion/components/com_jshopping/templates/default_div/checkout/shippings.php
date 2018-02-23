<?php defined( '_JEXEC' ) or die(); ?>
<div id="comjshop">
	<?php echo $this->checkout_navigator?>
	<?php echo $this->small_cart?>
	<div class="jshop">
		<form id="shipping_form" name="shipping_form" action="<?php echo $this->action ?>" method="post" onsubmit="return validateShippingMethods()">
			<?php echo $this->_tmp_ext_html_shipping_start?>
			<div id="table_shippings">
			<?php foreach($this->shipping_methods as $shipping){?>
				<div class="padiv">
					<input type="radio" name="sh_pr_method_id" id="shipping_method_<?php print $shipping->sh_pr_method_id?>" value="<?php print $shipping->sh_pr_method_id ?>" <?php if ($shipping->sh_pr_method_id==$this->active_shipping){ ?>checked = "checked"<?php } ?> onclick="showShippingForm(<?php print $shipping->shipping_id?>)" />
					<label for="shipping_method_<?php echo $shipping->sh_pr_method_id ?>">
						<?php if ($shipping->image){ ?>
						<span class="shipping_image"><img src="<?php echo $shipping->image?>" alt="<?php echo htmlspecialchars($shipping->name)?>" /></span>
						<?php } ?>
						<?php echo $shipping->name?> (<?php echo formatprice($shipping->calculeprice); ?>)</label>
						<?php if ($this->config->show_list_price_shipping_weight && count($shipping->shipping_price)){ ?>
						<div class="shipping_weight_to_price">
						<?php foreach($shipping->shipping_price as $price){?>
							<div class="weight">
								<?php if ($price->shipping_weight_to!=0){?>
									<?php echo formatweight($price->shipping_weight_from);?> - <?php echo formatweight($price->shipping_weight_to);?>
								<?php }else{ ?>
									<?php echo _JSHOP_FROM." ".formatweight($price->shipping_weight_from);?>
								<?php } ?>
							</div>
							<div class="price">
								<?php echo formatprice($price->shipping_price); ?>
							</div>
						<?php } ?>
						</div>
					<?php } ?>
					<div class="shipping_descr"><?php echo $shipping->description?></div>
					<div id="shipping_form_<?php print $shipping->shipping_id?>" class="shipping_form <?php if ($shipping->sh_pr_method_id==$this->active_shipping) print 'shipping_form_active'?>"><?php print $shipping->form?></div>
					<?php if ($shipping->delivery){?>
					<div class="shipping_delivery"><?php echo _JSHOP_DELIVERY_TIME.": ".$shipping->delivery?></div>
					<?php }?>
					<?php if ($shipping->delivery_date_f){?>
					<div class="shipping_delivery_date"><?php echo _JSHOP_DELIVERY_DATE.": ".$shipping->delivery_date_f?></div>
					<?php }?>       	
				</div>
			<?php } ?>
			</div>
			<?php echo $this->_tmp_ext_html_shipping_end?>
			<input type="submit" class="button" value="<?php echo _JSHOP_NEXT?>" />
		</form>
	</div>
</div>
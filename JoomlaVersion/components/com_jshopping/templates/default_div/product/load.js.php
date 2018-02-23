<?php defined( '_JEXEC' ) or die(); ?>
<script type="text/javascript">
    <?php if ($this->product->product_quantity >0){?>
    var translate_not_available = "<?php echo _JSHOP_PRODUCT_NOT_AVAILABLE_THIS_OPTION?>";
    <?php }else{?>
    var translate_not_available = "<?php echo _JSHOP_PRODUCT_NOT_AVAILABLE?>";
    <?php }?>
    var translate_zoom_image = "<?php echo _JSHOP_ZOOM_IMAGE?>";
    var product_basic_price_volume = <?php echo $this->product->weight_volume_units;?>;
    var product_basic_price_unit_qty = <?php echo $this->product->product_basic_price_unit_qty;?>;
    var currency_code = "<?php echo $this->config->currency_code;?>";
    var format_currency = "<?php echo $this->config->format_currency[$this->config->currency_format];?>";
    var decimal_count = <?php echo $this->config->decimal_count;?>;
    var decimal_symbol = "<?php echo $this->config->decimal_symbol;?>";
    var thousand_separator = "<?php echo $this->config->thousand_separator;?>";
    var attr_value = new Object();
    var attr_list = new Array();
    var attr_img = new Object();
    <?php if (count($this->attributes)){?>
        <?php $i=0;foreach($this->attributes as $attribut){?>
        attr_value["<?php echo $attribut->attr_id?>"] = "<?php echo $attribut->firstval?>";
        attr_list[<?php echo $i++;?>] = "<?php echo $attribut->attr_id?>";
        <?php } ?>
    <?php } ?>
    <?php foreach($this->all_attr_values as $attrval){ if ($attrval->image){?>attr_img[<?php echo $attrval->value_id?>] = "<?php echo $attrval->image?>";<?php } }?>
    var liveurl = '<?php echo JURI::root()?>';
    var liveattrpath = '<?php echo $this->config->image_attributes_live_path;?>';
    var liveproductimgpath = '<?php echo $this->config->image_product_live_path;?>';
    var liveimgpath = '<?php echo $this->config->live_path."images";?>';
    var urlupdateprice = '<?php echo $this->urlupdateprice;?>';
    <?php echo $this->_tmp_product_ext_js;?>
	
jQuery.fn.nvgtooltip = function(options) {		
	var options = jQuery.extend({
		txt: '', 
		maxWidth: 300,
		effect: 'fadeIn',
		duration: 300
	},options);
	
	var helper,effect={},el_tips={};
	if(!jQuery("div.tooltip").length) 
	jQuery(function() {helper = jQuery('<div class="tooltip"></div>').appendTo(document.body).hide();});
	else helper = jQuery("div.nvgtooltip").hide();
	
	effect.show = options.effect;
	switch(options.effect) {
		case 'fadeIn': 		effect.hide = 'fadeOut'; 	break;
		case 'show': 		effect.hide = 'hide'; 		break;
		case 'slideDown': 	effect.hide = 'slideUp'; 	break;
		default: 			effect.hide = 'fadeOut'; 	break;
	}
			
	return this.each(function() {
		if(options.txt) el_tips[jQuery.data(this)] = options.txt;
			else el_tips[jQuery.data(this)] = this.title;
			this.title = '';
			this.alt = '';
		}).mouseover(
			function () {
				if(el_tips[jQuery.data(this)] != '') {
					helper.css('width','');
					helper.html(el_tips[jQuery.data(this)]);
					if(helper.width() > options.maxWidth) helper.width(options.maxWidth);
					eval('helper.'+effect.show+'('+options.duration+')');
					jQuery(this).bind('mousemove', update);
				}
			}
		).mouseout(
			function () {
				jQuery(this).unbind('mousemove', update);	
				eval('helper.'+effect.hide+'('+options.duration+')');
			}		
		);
			

		function update(e) {		
			if (e.pageX + helper.width() + 40 > jQuery(document).scrollLeft() + window.screen.availWidth) 
			helper.css({left: e.pageX - helper.width() - 25 + "px"});
			else helper.css({left: e.pageX + 5 + "px"});
		
			if (e.pageY - helper.height() - 25 < jQuery(document).scrollTop()) helper.css({top: e.pageY + 25 + "px"});
			else helper.css({top: e.pageY - helper.height() - 25 + "px"});
		};
};	
	
</script>
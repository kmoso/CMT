<?php defined( '_JEXEC' ) or die(); ?>

<?php echo $product->_tmp_var_start?>
<div class="product productitem_<?php echo $product->product_id?>">
    <div class="image">
        <?php if ($product->image){?>
        <div class="image_block">
		<?php print $product->_tmp_var_image_block;?>
            <?php if ($product->label_id){?>
                <div class="product_label">
                    <?php if ($product->_label_image){?>
                        <img src="<?php echo $product->_label_image?>" alt="<?php echo htmlspecialchars($product->_label_name)?>" />
                    <?php }else{?>
                        <span class="label_name"><?php echo $product->_label_name;?></span>
                    <?php }?>
                </div>
            <?php }?>
            <a href="<?php echo $product->product_link?>">
                <img class="jshop_img" src="<?php echo $product->image?>" alt="<?php echo htmlspecialchars($product->name);?>" title="<?php print htmlspecialchars($product->name);?>" />
            </a>
            <div class="name">
            <h2 class="product_title"><a href="<?php echo $product->product_link?>"><?php echo $product->name?></a></h2>
                <?php if ($this->config->product_list_show_product_code){?><span class="jshop_code_prod">(<?php echo _JSHOP_EAN?>: <span><?php echo $product->product_ean;?></span>)</span><?php }?>
            </div>
        </div>
        <?php }?>


    </div>
    <div class="mainblock">
        
       <div class="price-wrap">
        <?php if ($product->product_old_price > 0){?>
            <div class="old_price"><?php if ($this->config->product_list_show_price_description) echo _JSHOP_OLD_PRICE.": ";?><span>
			<?php echo formatprice($product->product_old_price)?></span></div>
        <?php }?>
		<?php echo $product->_tmp_var_bottom_old_price;?>
        <?php if ($product->product_price_default > 0 && $this->config->product_list_show_price_default){?>
            <div class="default_price"><?php echo _JSHOP_DEFAULT_PRICE.": ";?><span><?php echo formatprice($product->product_price_default)?></span></div>
        <?php }?>
        <?php if ($product->_display_price){?>
            <div class = "jshop_price">
                <?php if ($this->config->product_list_show_price_description) echo _JSHOP_PRICE.": ";?>
                <?php if ($product->show_price_from) echo _JSHOP_FROM." ";?>
                <span><?php echo formatprice($product->product_price);?><?php echo $product->_tmp_var_price_ext;?></span>
            </div>
        </div>
        <?php }?>
        <?php echo $product->_tmp_var_bottom_price;?>
        <?php if ($this->config->show_tax_in_product && $product->tax > 0){?>
            <span class="taxinfo"><?php echo productTaxInfo($product->tax);?></span>
        <?php }?>
        <?php if ($this->config->show_plus_shipping_in_product){?>
            <span class="plusshippinginfo"><?php echo sprintf(_JSHOP_PLUS_SHIPPING, $this->shippinginfo);?></span>
        <?php }?>
        <?php if ($product->basic_price_info['price_show']){?>
            <div class="base_price"><?php print _JSHOP_BASIC_PRICE?>: <?php if ($product->show_price_from && !$this->config->hide_from_basic_price) print _JSHOP_FROM;?> <span><?php print formatprice($product->basic_price_info['basic_price'])?> / <?php print $product->basic_price_info['name'];?></span></div>
        <?php }?>
        <?php if ($this->config->product_list_show_weight && $product->product_weight > 0){?>
            <div class="productweight"><?php echo _JSHOP_WEIGHT?>.: <span><?php echo formatweight($product->product_weight)?></span></div>
        <?php }?>
        <?php if ($product->delivery_time != ''){?>
            <div class="deliverytime"><?php echo _JSHOP_DELIVERY_TIME?>: <span><?php echo $product->delivery_time?></span></div>
        <?php }?>
        <?php if (is_array($product->extra_field)){?>
            <div class="extra_fields">
            <?php foreach($product->extra_field as $extra_field){?>
                <div><?php echo $extra_field['name'];?>: <?php echo $extra_field['value']; ?></div>
            <?php }?>
            </div>
        <?php }?>
        <?php if ($product->vendor){?>
            <div class="vendorinfo"><?php echo _JSHOP_VENDOR?>: <a href="<?php echo $product->vendor->products?>"><?php echo $product->vendor->shop_name?></a></div>
        <?php }?>
        <?php if ($this->config->product_list_show_qty_stock){?>
            <div class="qty_in_stock"><?php echo _JSHOP_QTY_IN_STOCK?>: <span><?php echo sprintQtyInStock($product->qty_in_stock)?></span></div>
        <?php }?>
        <?php echo $product->_tmp_var_top_buttons;?>
        <div class="buttons">
            <?php if ($product->buy_link){?>
            <a class="button_buy" href="<?php echo $product->buy_link?>"><?php echo _JSHOP_BUY?></a> &nbsp;
            <?php }?>
            
            <?php echo $product->_tmp_var_buttons;?>
        </div>
        <?php echo $product->_tmp_var_bottom_buttons;?>
    </div>
</div>
<?php echo $product->_tmp_var_end?>
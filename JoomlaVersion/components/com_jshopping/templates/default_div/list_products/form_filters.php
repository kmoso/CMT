<?php defined( '_JEXEC' ) or die(); ?>
<form action="<?php echo $this->action;?>" method="post" name="sort_count" id="sort_count">
<?php if ($this->config->show_sort_product || $this->config->show_count_select_products){?>
    <div class="block_sorting_count_to_page">
    <?php if ($this->config->show_sort_product){?>
        <span class="box_products_sorting"><?php echo _JSHOP_ORDER_BY.": ".$this->sorting?><img src="<?php echo $this->path_image_sorting_dir?>" alt="orderby" onclick="submitListProductFilterSortDirection()" /></span>
    <?php }?>
    <?php if ($this->config->show_count_select_products){?>
        <span class="box_products_count_to_page"><?php echo _JSHOP_DISPLAY_NUMBER.": ".$this->product_count?></span>
    <?php }?>
    </div>
<?php }?>

<?php if ($this->config->show_product_list_filters && $this->filter_show){?>
    <?php if ($this->config->show_sort_product || $this->config->show_count_select_products){?>
    <div class="margin_filter"></div>
    <?php }?>
		<div class="jshop filters">
			<?php if ($this->filter_show_category){?>
			<span class="box_category"><?php echo _JSHOP_CATEGORY.": ".$this->categorys_sel?></span>
			<?php }?>
			<?php if ($this->filter_show_manufacturer){?>
			<span class="box_manufacrurer"><?php echo _JSHOP_MANUFACTURER.": ".$this->manufacuturers_sel?></span>
			<?php }?>
			<?php echo $this->_tmp_ext_filter_box;?>
			
			<?php if (getDisplayPriceShop()){?>
			<span class="filter_price"><?php echo _JSHOP_PRICE?>:
				<span class="box_price_from"><?php echo _JSHOP_FROM?> <input type="text" class="inputbox" name="fprice_from" id="price_from" size="7" value="<?php if ($this->filters['price_from']>0) echo $this->filters['price_from']?>" /></span>
				<span class="box_price_to"><?php echo _JSHOP_TO?> <input type="text" class="inputbox" name="fprice_to"  id="price_to" size="7" value="<?php if ($this->filters['price_to']>0) echo $this->filters['price_to']?>" /></span>
				<?php echo $this->config->currency_code?>
			</span>
			<?php }?>
			
			<?php echo $this->_tmp_ext_filter;?>
			<input type="button" class="button" value="<?php echo _JSHOP_GO?>" onclick="submitListProductFilters();" />
			<span class="clear_filter"><a href="#" onclick="clearProductListFilter();return false;"><?php echo _JSHOP_CLEAR_FILTERS?></a></span>
		</div>
<?php }?>
<input type="hidden" name="orderby" id="orderby" value="<?php echo $this->orderby?>" />
<input type="hidden" name="limitstart" value="0" />
</form>
<?php defined( '_JEXEC' ) or die(); ?>
<script type="text/javascript">var liveurl = '<?php echo JURI::root()?>';</script>
<div class="jshop" id="comjshop">
    <h1><?php echo  _JSHOP_SEARCH ?></h1>
    
    <form action="<?php echo $this->action?>" name="form_ad_search" method="post" onsubmit="return validateFormAdvancedSearch('form_ad_search')">
    <input type="hidden" name="setsearchdata" value="1">
    <div class = "js_search_gen">
      <?php print $this->_tmp_ext_search_html_start;?>  
      <div class="js_serch_text">
  	    <div class="js_name">
  		    <?php echo  _JSHOP_SEARCH_TEXT?>
	    </div>
        <div class="js_field">
          <input type = "text" name = "search" class = "inputbox" style = "width:300px" />
        </div>
      </div>
	  
      <div>
         <div class="js_name">
              <?php echo  _JSHOP_SEARCH_FOR?>
        </div>
         <div class="js_field">
          <input type="radio" name="search_type" value="any" id="search_type_any" checked="checked" /> <label for="search_type_any"><?php echo _JSHOP_ANY_WORDS?></label>
          <input type="radio" name="search_type" value="all" id="search_type_all" /> <label for="search_type_all"><?php echo _JSHOP_ALL_WORDS?></label>
          <input type="radio" name="search_type" value="exact" id="search_type_exact" /> <label for="search_type_exact"><?php echo _JSHOP_EXACT_WORDS?></label>
        </div>
      </div>
	  
      <div>
        <div class="js_name">
          <?php echo _JSHOP_SEARCH_CATEGORIES?>
        </div>
         <div class="js_field">
          <?php echo $this->list_categories ?><br />
          <input type = "checkbox" name = "include_subcat" id = "include_subcat" value = "1" />
          <label for = "include_subcat"><?php echo _JSHOP_SEARCH_INCLUDE_SUBCAT?></label>
        </div>
      </div>
	  
      <div>
        <span class="js_name"> <?php echo _JSHOP_SEARCH_MANUFACTURERS?> </span>
        <span> <?php echo $this->list_manufacturers ?> </span>
      </div>
	  
      <?php if (getDisplayPriceShop()){?>
      <div class="js_search_price">
        <span class="js_name"><?php echo _JSHOP_SEARCH_PRICE_FROM?> </span>   
        <span><input type = "text" class = "inputbox" name = "price_from" id = "price_from" /> <?php echo $this->config->currency_code?></span>
		<span class="js_name"><?php echo _JSHOP_SEARCH_PRICE_TO?></span>
        <span><input type = "text" class = "inputbox" name = "price_to" id = "price_to" /> <?php echo $this->config->currency_code?></span>
      </div>
      <?php }?>
      
        <div class="js_search_date">
			<span class="js_name"><?php echo _JSHOP_SEARCH_DATE_FROM ?> </span>      
			<span><?php echo JHTML::_('calendar','', 'date_from', 'date_from', '%Y-%m-%d', array('class'=>'inputbox', 'size'=>'25', 'maxlength'=>'19')); ?></span>
			<span class="js_name"><?php echo _JSHOP_SEARCH_DATE_TO ?></span>
			<span><?php echo JHTML::_('calendar','', 'date_to', 'date_to', '%Y-%m-%d', array('class'=>'inputbox', 'size'=>'25', 'maxlength'=>'19')); ?></span>
        </div>
     
      <div>
        <div id="list_characteristics"><?php echo $this->characteristics?></div>
      </div>
      <?php echo $this->_tmp_ext_search_html_end;?>
    </div>
	<div class="clear"></div>
    <div class="nvg_padd">
    <input type = "submit" class="button" value = "<?php echo _JSHOP_SEARCH ?>" />  
    </div>
    </form>
</div>
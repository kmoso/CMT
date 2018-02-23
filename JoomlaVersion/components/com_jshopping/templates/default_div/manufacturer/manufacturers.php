<?php defined( '_JEXEC' ) or die(); ?>
<?php if ($this->params->get('show_page_heading') && $this->params->get('page_heading')) {?>    
<div class="shophead<?php echo $this->params->get('pageclass_sfx');?>"><h1><?php echo $this->params->get('page_heading')?></h1></div>
<?php }?>
<div class="jshop" id="comjshop">
<?php echo $this->manufacturer->description?>

<?php if (count($this->rows)){?>
<div class="jshop_list_manufacturer">
<div class = "jshop">
    <?php foreach($this->rows as $k=>$row){?>
        <?php if ($k%$this->count_manufacturer_to_row==0) echo '<div class="clear">';?>
        <div class = "jshop_categ jswidth<?php echo round(100/$this->count_manufacturer_to_row, 0)?>">
          <div class = "manufacturer">
             <div>
               <div class="image">
                    <a href = "<?php echo $row->link;?>"><img class = "jshop_img" src = "<?php echo $this->image_manufs_live_path;?>/<?php if ($row->manufacturer_logo) echo $row->manufacturer_logo; else echo $this->noimage;?>" alt="<?php echo htmlspecialchars($row->name);?>" /></a>
               </div>
               <div>
                   <a class = "product_link" href = "<?php echo $row->link?>"><?php echo $row->name?></a>
                   <p class = "manufacturer_short_description"><?php echo $row->short_description?></p>
                   <?php if ($row->manufacturer_url!=""){?>
                   <div class="manufacturer_url">
                        <a target="_blank" href="<?php echo $row->manufacturer_url?>"><?php  echo _JSHOP_MANUFACTURER_INFO ?></a>
                   </div>
                   <?php }?>
               </div>
             </div>
           </div>
        </div>    
        <?php if ($k%$this->count_manufacturer_to_row==$this->count_manufacturer_to_row-1) echo "</div>";?>
	 <?php } ?>
     <?php if ($k%$this->count_manufacturer_to_row!=$this->count_manufacturer_to_row-1) echo "</div>";?>
</div>
</div>
<?php } ?>
</div>
<div class="clear"></div>
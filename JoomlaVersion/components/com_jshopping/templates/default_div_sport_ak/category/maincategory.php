<?php defined( '_JEXEC' ) or die(); ?>
<?php if ($this->params->get('show_page_heading') && $this->params->get('page_heading')) {?>    
<div class="jshop <?php print $this->params->get('pageclass_sfx');?> jshophead" id="comjshop">
<h1><?php print $this->params->get('page_heading')?></h1>
<?php }?><?php echo $this->category->description?>

<div class="jshop_list_category">
<?php if (count($this->categories)){?>

<div class="jshop">
    <?php foreach($this->categories as $k=>$category){?>
        <?php if ($k%$this->count_category_to_row==0) echo '<div class="nvg_clear"></div><div class="str_category uk-grid" data-uk-grid-match="{target:\'.uk-panel\'}">'; ?>
        <div class = "uk-width-medium-1-4 uk-panel jshop_categ jswidth<?php echo round(100/$this->count_category_to_row, 0)?>">
          <div class="category">
               <div class="image">
                    <a href = "<?php echo $category->category_link;?>"><img class = "jshop_img" src = "<?php echo $this->image_category_path;?>/<?php if ($category->category_image) echo $category->category_image; else echo $this->noimage;?>" alt="<?php echo htmlspecialchars($category->name);?>" title="<?php echo htmlspecialchars($category->name);?>" /></a>
               </div>
               <div>
                   <h2 class="category_title"><a class = "product_link" href = "<?php echo $category->category_link?>"><?php echo $category->name?></a></h2>
                   <p class = "category_short_description"><?php echo $category->short_description?></p>
               </div>
           </div>
        </div>    
        <?php if ($k%$this->count_category_to_row==$this->count_category_to_row-1) echo '</div>'; ?>    
    <?php } ?>
        <?php if ($k%$this->count_category_to_row!=$this->count_category_to_row-1) echo '</div>'; ?>    
</div>


<?php } ?>
</div>
<?php defined( '_JEXEC' ) or die(); ?>
<?php
if (count ($this->demofiles)){?>
<div class="list_product_demo">
<div>
    <?php foreach($this->demofiles as $demo){?>
    <div>
        <span class="descr"><?php echo $demo->demo_descr?></span>            
        <?php if ($this->config->demo_type == 1) { ?>
            <span class="download">
				<a target="_blank" href="<?php echo $this->config->demo_product_live_path."/".$demo->demo;?>" onClick="popupWin = window.open('<?php echo SEFLink("index.php?option=com_jshopping&controller=product&task=showmedia&media_id=".$demo->id);?>', 'video', 'width=<?php echo $this->config->video_product_width;?>,height=<?php echo $this->config->video_product_height;?>,top=0,resizable=no,location=no'); popupWin.focus(); return false;">
					<img src = "<?php print $this->config->live_path.'images/play.gif'; ?>" alt = "play" title = "play"/>
				</a>
			</span>
        <?php } else { ?>
            <span class="download">
				<a target="_blank" href="<?php echo $this->config->demo_product_live_path."/".$demo->demo;?>">
					<?php echo _JSHOP_DOWNLOAD ?>
				</a>
			</span>
        <?php }?>
    </div>
    <?php }?>
</div>
</div>
<?php } ?>
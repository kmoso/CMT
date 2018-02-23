<?php defined( '_JEXEC' ) or die(); ?>
<?php if ($this->allow_review || $this->config->show_hits){?>
<div class="ratinghits">
    <?php if ($this->config->show_hits){?>
    <span><?php echo _JSHOP_HITS?>: </span>
    <span><?php echo $this->product->hits;?></span>
    <?php } ?>
    
    <?php if ($this->allow_review && $this->config->show_hits){?>
    <span> | </span>
    <?php } ?>
    
    <?php if ($this->allow_review){?>
    <span><?php echo _JSHOP_RATING?>: </span>
    <span><?php echo showMarkStar($this->product->average_rating);?></span>
    <?php } ?>
</div>
<?php } ?>
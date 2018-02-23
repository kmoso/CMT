<?php defined( '_JEXEC' ) or die(); ?>
<div class="jshop" id="comjshop">
    <h1><?php echo _JSHOP_USER_GROUPS_INFO ?></h1>
    
    <table class="groups_list">
    	<tr>
        	<th class="title"><?php echo _JSHOP_TITLE ?></th> 
        	<th class="discount"><?php echo _JSHOP_DISCOUNT ?></th> 
    	</tr>
    <?php foreach($this->rows as $row){?>
    	<tr>
        	<td class="title"><?php print $row->name?></td> 
        	<td class="discount"><?php print floatval($row->usergroup_discount)?>%</td>
    	</tr>
    <?php }?>
    </table>
</div>
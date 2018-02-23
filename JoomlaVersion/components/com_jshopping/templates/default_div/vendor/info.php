<?php defined( '_JEXEC' ) or die(); ?>
<div class="jshop vendordetailinfo" id="comjshop">

    <?php if ($this->header){?>
    <h1><?php print $this->header ?></h1>
    <?php }?>
    
    <div class="vendor_logo">
        <?php if ($this->vendor->logo!=""){?>
        <img src="<?php print $this->vendor->logo?>" alt="<?php print htmlspecialchars($this->vendor->shop_name);?>" />
        <?php }?>
    </div>
    <div>
        <div>
          <label class="name">
            <?php echo _JSHOP_F_NAME?>:&nbsp;&nbsp; 
          </label>
          <span>
            <?php print $this->vendor->f_name ?>
          </span>
        </div>
        
        <div>
          <label class="name">
            <?php echo _JSHOP_L_NAME?>:&nbsp;&nbsp; 
          </label>
          <span>
            <?php print $this->vendor->l_name ?>
          </span>
        </div>        
        <div>
          <label class="name">
            <?php echo _JSHOP_FIRMA_NAME ?>:&nbsp;&nbsp; 
          </label>
          <span>
            <?php print $this->vendor->company_name ?>
          </span>
        </div>
        
        <div>
          <label class="name">
            <?php echo _JSHOP_EMAIL?>:&nbsp;&nbsp; 
          </label>
          <span>
            <?php print $this->vendor->email ?>
          </span>
        </div>        
        <div>
          <label  class="name">
            <?php echo _JSHOP_STREET_NR?>:&nbsp;&nbsp; 
          </label>
          <span>
            <?php print $this->vendor->adress ?>
          </span>
        </div>
        
        <div>
          <label class="name">
            <?php echo _JSHOP_ZIP ?>:&nbsp;&nbsp; 
          </label>
          <span>
            <?php print $this->vendor->zip ?>
          </span>
        </div>        
        <div>
          <label class="name">
            <?php echo _JSHOP_CITY?>:&nbsp;&nbsp; 
          </label>
          <span>
            <?php print $this->vendor->city ?>
          </span>
        </div>        
        <div>
          <label class="name">
            <?php echo _JSHOP_STATE ?>:&nbsp;&nbsp; 
          </label>
          <span>
            <?php print $this->vendor->state ?>
          </span>
        </div>
        
        <div>
          <label class="name">
            <?php echo _JSHOP_COUNTRY?>:&nbsp;&nbsp; 
          </label>
          <span>
            <?php print $this->vendor->country ?>
          </span>
        </div>
        
        <div>
          <label class="name">
            <?php echo _JSHOP_TELEFON?>:&nbsp;&nbsp; 
          </label>
          <span>
            <?php print $this->vendor->phone ?>
          </span>
        </div>
        
        <div>
          <label class="name">
            <?php echo _JSHOP_FAX?>:&nbsp;&nbsp; 
          </label>
          <span>
            <?php print $this->vendor->fax ?>
          </span>
        </div>
    </div>
</div>    
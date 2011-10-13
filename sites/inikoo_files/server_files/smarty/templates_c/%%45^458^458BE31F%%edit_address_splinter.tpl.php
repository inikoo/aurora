<?php /* Smarty version 2.6.22, created on 2011-10-12 15:12:53
         compiled from edit_address_splinter.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 't', 'edit_address_splinter.tpl', 9, false),array('modifier', 'lower', 'edit_address_splinter.tpl', 170, false),)), $this); ?>
<tbody id="<?php echo $this->_tpl_vars['address_identifier']; ?>
address_form" style="<?php if (! $this->_tpl_vars['show_form']): ?>display:none<?php endif; ?>">
    <input type="hidden" id="<?php echo $this->_tpl_vars['address_identifier']; ?>
address_key" value="" ovalue="" />
    <input type="hidden" id="<?php echo $this->_tpl_vars['address_identifier']; ?>
address_fuzzy" value="Yes" ovalue="Yes" />
    <input id="<?php echo $this->_tpl_vars['address_identifier']; ?>
address_country_code" value="" type="hidden"/>
    <input id="<?php echo $this->_tpl_vars['address_identifier']; ?>
address_country_2acode" value="" type="hidden"/>
    
    <tr id="<?php echo $this->_tpl_vars['address_identifier']; ?>
tr_address_type" <?php if ($this->_tpl_vars['hide_type']): ?>style="display:none"<?php endif; ?>>
    <td class="label">
      <span id="<?php echo $this->_tpl_vars['address_identifier']; ?>
show_description" onclick="show_description()" class="small_button" style="padding:0 1px;font-size:50%;position:relative;top:-2px;">+</span> <?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Address Type<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>:</td><td  style="text-align:left"   id="<?php echo $this->_tpl_vars['address_identifier']; ?>
address_type" value="" ovalue=""  >
      <span id="<?php echo $this->_tpl_vars['address_identifier']; ?>
address_type_Office" label="Office" onclick="toggle_address_type(this)" class="small_button address_type <?php if ($this->_tpl_vars['address_type'] == 'Office'): ?>selected<?php endif; ?>" style="margin:0"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Office<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span>
      <span id="<?php echo $this->_tpl_vars['address_identifier']; ?>
address_type_Shop" label="Shop" onclick="toggle_address_type(this)" class="small_button  address_type <?php if ($this->_tpl_vars['address_type'] == 'Shop'): ?>selected<?php endif; ?>" style="margin-left:3px"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Shop<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span>
      <span id="<?php echo $this->_tpl_vars['address_identifier']; ?>
address_type_Warehouse" label="Warehouse" onclick="toggle_address_type(this)" class="small_button  address_type <?php if ($this->_tpl_vars['address_type'] == 'Warehouse'): ?>selected<?php endif; ?>" style="margin-left:3px"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Warehouse<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span>
      <span id="<?php echo $this->_tpl_vars['address_identifier']; ?>
address_type_Other" label="Other" onclick="toggle_address_type(this)" class="small_button  address_type <?php if ($this->_tpl_vars['address_type'] == 'Other'): ?>selected<?php endif; ?>" style="margin-left:3px"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Other<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span>
    </td>
	    </tr>
    <tr id="<?php echo $this->_tpl_vars['address_identifier']; ?>
tr_address_function" style="<?php if ($this->_tpl_vars['hide_description']): ?>display:none;<?php endif; ?>" >
    <td class="label"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Address Function<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>:</td><td  style="text-align:left"   id="<?php echo $this->_tpl_vars['address_identifier']; ?>
address_function" value="<?php echo $this->_tpl_vars['function_value']; ?>
" ovalue=""  >
      <span id="<?php echo $this->_tpl_vars['address_identifier']; ?>
address_function_Contact" label="Contact" onclick="toggle_address_function(this)" class="small_button address_function <?php if ($this->_tpl_vars['address_function'] == 'Contact'): ?>selected<?php endif; ?>" style="margin:0"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Contact<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span>
      <span id="<?php echo $this->_tpl_vars['address_identifier']; ?>
address_function_Billing" label="Billing" onclick="toggle_address_function(this)" class="small_button address_function <?php if ($this->_tpl_vars['address_function'] == 'Billing'): ?>selected<?php endif; ?>" style="margin-left:3px"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Billing<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span>
      <span id="<?php echo $this->_tpl_vars['address_identifier']; ?>
address_function_Shipping" label="Shipping" onclick="toggle_address_function(this)" class="small_button address_function <?php if ($this->_tpl_vars['address_function'] == 'Shipping'): ?>selected<?php endif; ?>" style="margin-left:3px"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Shipping<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span>
      <span id="<?php echo $this->_tpl_vars['address_identifier']; ?>
address_function_Other" label="Other" onclick="toggle_address_function(this)" class="small_button address_function <?php if ($this->_tpl_vars['address_function'] == 'Other'): ?>selected<?php endif; ?>" style="margin-left:3px"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Other<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span>
    </td>
  </tr>
    <tr id="<?php echo $this->_tpl_vars['address_identifier']; ?>
tr_address_description" style="display:none"> 
    <td class="label">
        <span id="<?php echo $this->_tpl_vars['address_identifier']; ?>
hide_description" onclick="hide_description()" class="small_button" style="padding:0 1px;font-size:50%;position:relative;top:-2px;">x</span> <?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Description<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>:
        </td>
    <td  style="text-align:left">
        <input style="text-align:left" id="<?php echo $this->_tpl_vars['address_identifier']; ?>
address_description" value="" ovalue="" />
    </td>
  </tr>
    <tr id="<?php echo $this->_tpl_vars['address_identifier']; ?>
tr_address_description" <?php if ($this->_tpl_vars['hide_type'] && $this->_tpl_vars['hide_description']): ?>style="display:none"<?php endif; ?>  >
    <td ></td><td style="text-align:left"><div  style="text-align:left;float:left;height:10px;width:210px;border-top:1px solid #ddd"></div></td>
  </tr>
</tbody>

<tbody id="<?php echo $this->_tpl_vars['address_identifier']; ?>
address_components"  style="<?php if (! $this->_tpl_vars['show_components']): ?>display:none<?php endif; ?>">

    <tr id="<?php echo $this->_tpl_vars['address_identifier']; ?>
tr_address_contact"  style="<?php if (! $this->_tpl_vars['show_contact']): ?>display:none<?php endif; ?>">
    <td class="label" ><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Contact<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>:</td>
    <td  style="text-align:left">
    <div>
        <input type="hidden" id="<?php echo $this->_tpl_vars['address_identifier']; ?>
address_use_contact" value="<?php echo $this->_tpl_vars['show_contact']; ?>
"   ovalue="<?php echo $this->_tpl_vars['show_contact']; ?>
"  />
        <input style="text-align:left;width:100%" id="<?php echo $this->_tpl_vars['address_identifier']; ?>
address_contact" value="" ovalue="" />
        <div id="<?php echo $this->_tpl_vars['address_identifier']; ?>
address_contact_container"  ></div>
	</div>
    </td>
  </tr>
    <tr id="<?php echo $this->_tpl_vars['address_identifier']; ?>
tr_address_telephone"  style="<?php if (! $this->_tpl_vars['show_tel']): ?>display:none<?php endif; ?>">
    <td class="label" ><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Telephone<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>:</td>
    <td  style="text-align:left">
    <div>
        <input type="hidden" id="<?php echo $this->_tpl_vars['address_identifier']; ?>
address_use_tel" value="<?php echo $this->_tpl_vars['show_tel']; ?>
"   ovalue="<?php echo $this->_tpl_vars['show_tel']; ?>
"  />
        <input style="text-align:left;width:100%" id="<?php echo $this->_tpl_vars['address_identifier']; ?>
address_telephone" value="" ovalue="" />
        <div id="<?php echo $this->_tpl_vars['address_identifier']; ?>
address_telephone_container"  ></div>
	</div>
    </td>
  </tr>
    <tr id="<?php echo $this->_tpl_vars['address_identifier']; ?>
tr_address_internal">
    <td class="label"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Internal<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>:</td>
    <td  style="text-align:left">
        <input style="text-align:left;width:100%" id="<?php echo $this->_tpl_vars['address_identifier']; ?>
address_internal" value="" ovalue="" />
    </td>
  </tr>
    <tr id="<?php echo $this->_tpl_vars['address_identifier']; ?>
tr_address_building">
    <td class="label" ><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Building<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>:</td><td  style="text-align:left"><input style="text-align:left;width:100%" id="<?php echo $this->_tpl_vars['address_identifier']; ?>
address_building" value="" ovalue="" /></td>
  </tr>
    <tr id="<?php echo $this->_tpl_vars['address_identifier']; ?>
tr_address_street">
    <td class="label" ><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Street<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>:</td>
    <td  style="text-align:left"><input style="text-align:left;width:100%" id="<?php echo $this->_tpl_vars['address_identifier']; ?>
address_street" value="" ovalue="" /></td>
    </tr>
    <tr id="<?php echo $this->_tpl_vars['address_identifier']; ?>
tr_address_town_d2" style="display:none">
    <td class="label" ><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>City 2nd Div<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>:</td><td  style="text-align:left"><input style="text-align:left;width:100%" id="<?php echo $this->_tpl_vars['address_identifier']; ?>
address_town_d2" value="" ovalue="" /></td>
   </tr>
    <tr id="<?php echo $this->_tpl_vars['address_identifier']; ?>
tr_address_town_d1" style="display:none">
    <td class="label"  ><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>City 1st Div<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>:</td>
    <td  style="text-align:left"><input style="text-align:left;width:100%" id="<?php echo $this->_tpl_vars['address_identifier']; ?>
address_town_d1" value="" ovalue="" /></td>
  </tr>
    <tr id="<?php echo $this->_tpl_vars['address_identifier']; ?>
tr_address_town">
    <td class="label" >
      <span id="<?php echo $this->_tpl_vars['address_identifier']; ?>
show_town_subdivisions" onclick="show_town_subdivisions('<?php echo $this->_tpl_vars['address_identifier']; ?>
')" style="cursor:pointer">&oplus;</span> <?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>City<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>:</td>
    <td  style="text-align:left">
     <div >
    <input style="text-align:left;width:100%" id="<?php echo $this->_tpl_vars['address_identifier']; ?>
address_town" value="" ovalue="" />
      <input id="<?php echo $this->_tpl_vars['address_identifier']; ?>
address_town_code" value="" type="hidden"/>
<div id="<?php echo $this->_tpl_vars['address_identifier']; ?>
address_town_container"  ></div>
	      </div>
    </td>
  </tr>
    <tr id="<?php echo $this->_tpl_vars['address_identifier']; ?>
tr_address_postal_code">
    <td    id="<?php echo $this->_tpl_vars['address_identifier']; ?>
label_address_postal_code"     class="label" ><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Postal Code<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>:<img  id="<?php echo $this->_tpl_vars['address_identifier']; ?>
address_postal_code_warning" title=""  src="art/icons/exclamation.png" style="margin-left:5px;visibility:hidden" />
    </td>
    <td  style="text-align:left">
    <div >
    <input style="text-align:left;width:100%" id="<?php echo $this->_tpl_vars['address_identifier']; ?>
address_postal_code" value="" ovalue=""  />
     <input id="<?php echo $this->_tpl_vars['address_identifier']; ?>
address_postal_code_code" value="" type="hidden"/>
        <div id="<?php echo $this->_tpl_vars['address_identifier']; ?>
address_postal_code_container"  ></div>
	      </div>
    </td>
  </tr>
    <tr id="<?php echo $this->_tpl_vars['address_identifier']; ?>
tr_address_country_d5" style="display:none">
	  <td class="label" ><span id="label_address_country_d5"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>5rd Division<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span>:</td><td  style="text-align:left">
	    <div  >
	      <input id="address_country_d5_code" value="" type="hidden"/>
	      <input style="text-align:left;width:100%" id="address_country_d5" value="" ovalue="" />
	      <div id="address_country_d5_container"  ></div>
	    </div>
	  </td>
	</tr>
    <tr id="<?php echo $this->_tpl_vars['address_identifier']; ?>
tr_address_country_d4" style="display:none">
	  <td class="label" ><span id="label_address_country_d4"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>4rd Division<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span>:</td><td  style="text-align:left">
	    <div  >
	      <input id="address_country_d4_code" value="" type="hidden"/>
	      <input style="text-align:left;width:100%" id="address_country_d4" value="" ovalue="" />
	      <div id="address_country_d4_container"  ></div>
	    </div>
	  </td>
	</tr>
    <tr id="<?php echo $this->_tpl_vars['address_identifier']; ?>
tr_address_country_d3" style="display:none">
	  <td class="label" ><span id="label_address_country_d3"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>3rd Division<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span>:</td><td  style="text-align:left">
	    <div   >
	      <input id="address_country_d3_code" value="" type="hidden"/>
	      <input style="text-align:left;width:100%" id="address_country_d3" value="" ovalue="" />
	      <div id="address_country_d3_container"  ></div>
	    </div>
	  </td>
	</tr>
    <tr id="<?php echo $this->_tpl_vars['address_identifier']; ?>
tr_address_country_d2">
    <td class="label" ><span id="<?php echo $this->_tpl_vars['address_identifier']; ?>
label_address_country_d2"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Subregion<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span>:</td>
        <td  style="text-align:left">
        <div  >
	    <input style="text-align:left;width:100%" id="<?php echo $this->_tpl_vars['address_identifier']; ?>
address_country_d2" value="" ovalue="" />
	    	      <input id="<?php echo $this->_tpl_vars['address_identifier']; ?>
address_country_d2_code" value="" type="hidden"/>
            <div id="<?php echo $this->_tpl_vars['address_identifier']; ?>
address_country_d2_container"  ></div>
	      </div>
	    </td>
  </tr>
    <tr id="<?php echo $this->_tpl_vars['address_identifier']; ?>
tr_address_country_d1">
	    <td class="label" >
	    
	    <span id="<?php echo $this->_tpl_vars['address_identifier']; ?>
label_address_country_d1"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Region<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span>:</td>
	    <td  style="text-align:left">
	     <div  >
	      <input style="text-align:left;width:100%" id="<?php echo $this->_tpl_vars['address_identifier']; ?>
address_country_d1" value="" ovalue="" />
	      <input id="<?php echo $this->_tpl_vars['address_identifier']; ?>
address_country_d1_code" value="" type="hidden"/>
	    <div id="<?php echo $this->_tpl_vars['address_identifier']; ?>
address_country_d1_container"  ></div>
	      </div>
	      </td>
  </tr>
</tbody>

<tr id="<?php echo $this->_tpl_vars['address_identifier']; ?>
tr_address_country"  class="first">
        <td class="label"  style="width:120px">
<span 
	        id="<?php echo $this->_tpl_vars['address_identifier']; ?>
show_country_subregions" 
	        onclick="show_country_subregions('<?php echo $this->_tpl_vars['address_identifier']; ?>
')" 
	        
	        style="cursor:pointer;display:none">&oplus;</span> 
            <?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Country<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>:</td>
        <td  >
        <div id="<?php echo $this->_tpl_vars['address_identifier']; ?>
myAutoComplete" >
	        <input id="<?php echo $this->_tpl_vars['address_identifier']; ?>
address_country" style="text-align:left;width:100%" type="text"/>
	        <div id="<?php echo $this->_tpl_vars['address_identifier']; ?>
address_country_container" ></div>
        </div>
        
    </td>
    <td  style="width:70px">
   
        <?php if ($this->_tpl_vars['default_country_2alpha']): ?>
        <span style="margin-left:0px;;float:none" id="<?php echo $this->_tpl_vars['address_identifier']; ?>
default_country_selector" onClick="select_default_country('<?php echo $this->_tpl_vars['address_identifier']; ?>
','<?php echo $this->_tpl_vars['default_country_2alpha']; ?>
')"  ><img style="cursor:pointer;vertical-align:-1px;"  src="art/flags/<?php echo ((is_array($_tmp=$this->_tpl_vars['default_country_2alpha'])) ? $this->_run_mod_handler('lower', true, $_tmp) : smarty_modifier_lower($_tmp)); ?>
.gif" alt="(<?php echo $this->_tpl_vars['default_country_2alpha']; ?>
)"/></span>	
	    <span  id="<?php echo $this->_tpl_vars['address_identifier']; ?>
default_country_selector"></span>
	    <?php else: ?>
	    <?php endif; ?>
	    <span  style="margin-left:0px;;float:none"   id="<?php echo $this->_tpl_vars['address_identifier']; ?>
browse_countries"  onClick="show_countries_list(this,'<?php echo $this->_tpl_vars['address_identifier']; ?>
')"   class="state_details"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>List<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span>
   
  

</td>
       
    </tr>  
<tr <?php if ($this->_tpl_vars['hide_buttons'] == true): ?>style="display:none"<?php endif; ?>>
  <td colspan=2 style="text-align:right"><button close_if_reset="<?php if ($this->_tpl_vars['close_if_reset']): ?>Yes<?php else: ?>No<?php endif; ?>" address_key="" style="<?php if (! $this->_tpl_vars['close_if_reset']): ?>visibility:hidden<?php endif; ?>" id="<?php echo $this->_tpl_vars['address_identifier']; ?>
reset_address_button"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Cancel<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></button><button address_key="" style="visibility:hidden;margin-left:10px"id="<?php echo $this->_tpl_vars['address_identifier']; ?>
save_address_button"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Save<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></button></td>
  </tr>
  

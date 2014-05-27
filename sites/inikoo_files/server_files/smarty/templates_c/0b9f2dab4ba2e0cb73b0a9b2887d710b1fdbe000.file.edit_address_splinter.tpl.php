<?php /* Smarty version Smarty-3.1.5, created on 2013-10-07 18:29:36
         compiled from "templates/edit_address_splinter.tpl" */ ?>
<?php /*%%SmartyHeaderCode:8902134245252e170015a17-22940177%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '0b9f2dab4ba2e0cb73b0a9b2887d710b1fdbe000' => 
    array (
      0 => 'templates/edit_address_splinter.tpl',
      1 => 1326188700,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '8902134245252e170015a17-22940177',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'address_identifier' => 0,
    'show_form' => 0,
    'address_type' => 0,
    'hide_description' => 0,
    'function_value' => 0,
    'address_function' => 0,
    'hide_type' => 0,
    'show_components' => 0,
    'show_contact' => 0,
    'show_tel' => 0,
    'country_list' => 0,
    'item' => 0,
    'close_if_reset' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.5',
  'unifunc' => 'content_5252e1705a23c',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5252e1705a23c')) {function content_5252e1705a23c($_smarty_tpl) {?><?php if (!is_callable('smarty_block_t')) include '/Users/raul/dw/sites/inikoo_files/external_libs/Smarty/plugins/block.t.php';
?><tbody id="<?php echo $_smarty_tpl->tpl_vars['address_identifier']->value;?>
address_form" style="<?php if (!$_smarty_tpl->tpl_vars['show_form']->value){?>display:none<?php }?>">
    <input type="hidden" id="<?php echo $_smarty_tpl->tpl_vars['address_identifier']->value;?>
address_key" value="" ovalue="" />
    <input type="hidden" id="<?php echo $_smarty_tpl->tpl_vars['address_identifier']->value;?>
address_fuzzy" value="Yes" ovalue="Yes" />
    <input id="<?php echo $_smarty_tpl->tpl_vars['address_identifier']->value;?>
address_country_code_" value="" type="hidden"/>
    <input id="<?php echo $_smarty_tpl->tpl_vars['address_identifier']->value;?>
address_country_2acode" value="" type="hidden"/>
    
    <tr id="<?php echo $_smarty_tpl->tpl_vars['address_identifier']->value;?>
tr_address_type" style="display:none">
    <td class="label">
      <span id="<?php echo $_smarty_tpl->tpl_vars['address_identifier']->value;?>
show_description" onclick="show_description()" class="small_button" style="padding:0 1px;font-size:50%;position:relative;top:-2px;">+</span> <?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Address Type<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
:</td><td  style="text-align:left"   id="<?php echo $_smarty_tpl->tpl_vars['address_identifier']->value;?>
address_type" value="" ovalue=""  >
      <span id="<?php echo $_smarty_tpl->tpl_vars['address_identifier']->value;?>
address_type_Office" label="Office" onclick="toggle_address_type(this)" class="small_button address_type <?php if ($_smarty_tpl->tpl_vars['address_type']->value=='Office'){?>selected<?php }?>" style="margin:0"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Office<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</span>
      <span id="<?php echo $_smarty_tpl->tpl_vars['address_identifier']->value;?>
address_type_Shop" label="Shop" onclick="toggle_address_type(this)" class="small_button  address_type <?php if ($_smarty_tpl->tpl_vars['address_type']->value=='Shop'){?>selected<?php }?>" style="margin-left:3px"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Shop<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</span>
      <span id="<?php echo $_smarty_tpl->tpl_vars['address_identifier']->value;?>
address_type_Warehouse" label="Warehouse" onclick="toggle_address_type(this)" class="small_button  address_type <?php if ($_smarty_tpl->tpl_vars['address_type']->value=='Warehouse'){?>selected<?php }?>" style="margin-left:3px"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Warehouse<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</span>
      <span id="<?php echo $_smarty_tpl->tpl_vars['address_identifier']->value;?>
address_type_Other" label="Other" onclick="toggle_address_type(this)" class="small_button  address_type <?php if ($_smarty_tpl->tpl_vars['address_type']->value=='Other'){?>selected<?php }?>" style="margin-left:3px"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Other<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</span>
    </td>
	    </tr>
    <tr id="<?php echo $_smarty_tpl->tpl_vars['address_identifier']->value;?>
tr_address_function" style="<?php if ($_smarty_tpl->tpl_vars['hide_description']->value){?>display:none;<?php }?>" >
    <td class="label"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Address Function<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
:</td><td  style="text-align:left"   id="<?php echo $_smarty_tpl->tpl_vars['address_identifier']->value;?>
address_function" value="<?php echo $_smarty_tpl->tpl_vars['function_value']->value;?>
" ovalue=""  >
      <span id="<?php echo $_smarty_tpl->tpl_vars['address_identifier']->value;?>
address_function_Contact" label="Contact" onclick="toggle_address_function(this)" class="small_button address_function <?php if ($_smarty_tpl->tpl_vars['address_function']->value=='Contact'){?>selected<?php }?>" style="margin:0"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Contact<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</span>
      <span id="<?php echo $_smarty_tpl->tpl_vars['address_identifier']->value;?>
address_function_Billing" label="Billing" onclick="toggle_address_function(this)" class="small_button address_function <?php if ($_smarty_tpl->tpl_vars['address_function']->value=='Billing'){?>selected<?php }?>" style="margin-left:3px"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Billing<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</span>
      <span id="<?php echo $_smarty_tpl->tpl_vars['address_identifier']->value;?>
address_function_Shipping" label="Shipping" onclick="toggle_address_function(this)" class="small_button address_function <?php if ($_smarty_tpl->tpl_vars['address_function']->value=='Shipping'){?>selected<?php }?>" style="margin-left:3px"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Shipping<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</span>
      <span id="<?php echo $_smarty_tpl->tpl_vars['address_identifier']->value;?>
address_function_Other" label="Other" onclick="toggle_address_function(this)" class="small_button address_function <?php if ($_smarty_tpl->tpl_vars['address_function']->value=='Other'){?>selected<?php }?>" style="margin-left:3px"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Other<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</span>
    </td>
  </tr>
    <tr id="<?php echo $_smarty_tpl->tpl_vars['address_identifier']->value;?>
tr_address_description" style="display:none"> 
    <td class="label">
        <span id="<?php echo $_smarty_tpl->tpl_vars['address_identifier']->value;?>
hide_description" onclick="hide_description()" class="small_button" style="padding:0 1px;font-size:50%;position:relative;top:-2px;">x</span> <?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Description<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
:
        </td>
    <td  style="text-align:left">
        <input style="text-align:left" id="<?php echo $_smarty_tpl->tpl_vars['address_identifier']->value;?>
address_description" value="" ovalue="" />
    </td>
  </tr>
    <tr id="<?php echo $_smarty_tpl->tpl_vars['address_identifier']->value;?>
tr_address_description" <?php if ($_smarty_tpl->tpl_vars['hide_type']->value&&$_smarty_tpl->tpl_vars['hide_description']->value){?>style="display:none"<?php }?>  >
    <td ></td><td style="text-align:left"><div  style="text-align:left;float:left;height:10px;width:210px;border-top:1px solid #ddd"></div></td>
  </tr>
</tbody>

<tbody id="<?php echo $_smarty_tpl->tpl_vars['address_identifier']->value;?>
address_components"  style="<?php if (!$_smarty_tpl->tpl_vars['show_components']->value){?>display:none<?php }?>">

    <tr id="<?php echo $_smarty_tpl->tpl_vars['address_identifier']->value;?>
tr_address_contact"  style="<?php if ($_smarty_tpl->tpl_vars['show_contact']->value){?>display:none<?php }?>">
    <td class="label" ><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Contact<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
:</td>
    <td  style="text-align:left">
    <div>
        <input type="hidden" id="<?php echo $_smarty_tpl->tpl_vars['address_identifier']->value;?>
address_use_contact" value="<?php echo $_smarty_tpl->tpl_vars['show_contact']->value;?>
"   ovalue="<?php echo $_smarty_tpl->tpl_vars['show_contact']->value;?>
"  />
        <input style="text-align:left;width:100%" id="<?php echo $_smarty_tpl->tpl_vars['address_identifier']->value;?>
address_contact" value="" ovalue="" />
        <div id="<?php echo $_smarty_tpl->tpl_vars['address_identifier']->value;?>
address_contact_container"  ></div>
	</div>
    </td>
  </tr>
    <tr id="<?php echo $_smarty_tpl->tpl_vars['address_identifier']->value;?>
tr_address_telephone"  style="<?php if (!$_smarty_tpl->tpl_vars['show_tel']->value){?>display:none<?php }?>">
    <td class="label" ><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Telephone<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
:</td>
    <td  style="text-align:left">
    <div>
        <input type="hidden" id="<?php echo $_smarty_tpl->tpl_vars['address_identifier']->value;?>
address_use_tel" value="<?php echo $_smarty_tpl->tpl_vars['show_tel']->value;?>
"   ovalue="<?php echo $_smarty_tpl->tpl_vars['show_tel']->value;?>
"  />
        <input style="text-align:left;width:100%" id="<?php echo $_smarty_tpl->tpl_vars['address_identifier']->value;?>
address_telephone" value="" ovalue="" />
        <div id="<?php echo $_smarty_tpl->tpl_vars['address_identifier']->value;?>
address_telephone_container"  ></div>
	</div>
    </td>
  </tr>
    <tr id="<?php echo $_smarty_tpl->tpl_vars['address_identifier']->value;?>
tr_address_internal">
    <td class="label"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Internal<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
:</td>
    <td  style="text-align:left">
        <input style="text-align:left;width:100%" id="<?php echo $_smarty_tpl->tpl_vars['address_identifier']->value;?>
address_internal" value="" ovalue="" />
    </td>
  </tr>
    <tr id="<?php echo $_smarty_tpl->tpl_vars['address_identifier']->value;?>
tr_address_building">
    <td class="label" ><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Building<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
:</td><td  style="text-align:left"><input style="text-align:left;width:100%" id="<?php echo $_smarty_tpl->tpl_vars['address_identifier']->value;?>
address_building" value="" ovalue="" /></td>
  </tr>
    <tr id="<?php echo $_smarty_tpl->tpl_vars['address_identifier']->value;?>
tr_address_street">
    <td class="label" ><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Street<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
:</td>
    <td  style="text-align:left"><input style="text-align:left;width:100%" id="<?php echo $_smarty_tpl->tpl_vars['address_identifier']->value;?>
address_street" value="" ovalue="" /></td>
    </tr>
    <tr id="<?php echo $_smarty_tpl->tpl_vars['address_identifier']->value;?>
tr_address_town_d2" style="display:none">
    <td class="label" ><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
City 2nd Div<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
:</td><td  style="text-align:left"><input style="text-align:left;width:100%" id="<?php echo $_smarty_tpl->tpl_vars['address_identifier']->value;?>
address_town_d2" value="" ovalue="" /></td>
   </tr>
    <tr id="<?php echo $_smarty_tpl->tpl_vars['address_identifier']->value;?>
tr_address_town_d1" style="display:none">
    <td class="label"  ><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
City 1st Div<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
:</td>
    <td  style="text-align:left"><input style="text-align:left;width:100%" id="<?php echo $_smarty_tpl->tpl_vars['address_identifier']->value;?>
address_town_d1" value="" ovalue="" /></td>
  </tr>
    <tr id="<?php echo $_smarty_tpl->tpl_vars['address_identifier']->value;?>
tr_address_town">
    <td class="label" >
      <span id="<?php echo $_smarty_tpl->tpl_vars['address_identifier']->value;?>
show_town_subdivisions" donclick="show_town_subdivisions('<?php echo $_smarty_tpl->tpl_vars['address_identifier']->value;?>
')" style="cursor:pointer">&oplus;</span> <?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
City<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
:</td>
    <td  style="text-align:left">
     <div >
    <input style="text-align:left;width:100%" id="<?php echo $_smarty_tpl->tpl_vars['address_identifier']->value;?>
address_town" value="" ovalue="" />
      <input id="<?php echo $_smarty_tpl->tpl_vars['address_identifier']->value;?>
address_town_code" value="" type="hidden"/>
<div id="<?php echo $_smarty_tpl->tpl_vars['address_identifier']->value;?>
address_town_container"  ></div>
	      </div>
    </td>
  </tr>
    <tr id="<?php echo $_smarty_tpl->tpl_vars['address_identifier']->value;?>
tr_address_postal_code">
    <td    id="<?php echo $_smarty_tpl->tpl_vars['address_identifier']->value;?>
label_address_postal_code"     class="label" ><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Postal Code<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
:<img  id="<?php echo $_smarty_tpl->tpl_vars['address_identifier']->value;?>
address_postal_code_warning" title=""  src="art/icons/exclamation.png" style="margin-left:5px;visibility:hidden" />
    </td>
    <td  style="text-align:left">
    <div >
    <input style="text-align:left;width:100%" id="<?php echo $_smarty_tpl->tpl_vars['address_identifier']->value;?>
address_postal_code" value="" ovalue=""  />
     <input id="<?php echo $_smarty_tpl->tpl_vars['address_identifier']->value;?>
address_postal_code_code" value="" type="hidden"/>
        <div id="<?php echo $_smarty_tpl->tpl_vars['address_identifier']->value;?>
address_postal_code_container"  ></div>
	      </div>
    </td>
  </tr>
    <tr id="<?php echo $_smarty_tpl->tpl_vars['address_identifier']->value;?>
tr_address_country_d5" style="display:none">
	  <td class="label" ><span id="label_address_country_d5"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
5rd Division<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</span>:</td><td  style="text-align:left">
	    <div  >
	      <input id="address_country_d5_code" value="" type="hidden"/>
	      <input style="text-align:left;width:100%" id="address_country_d5" value="" ovalue="" />
	      <div id="address_country_d5_container"  ></div>
	    </div>
	  </td>
	</tr>
    <tr id="<?php echo $_smarty_tpl->tpl_vars['address_identifier']->value;?>
tr_address_country_d4" style="display:none">
	  <td class="label" ><span id="label_address_country_d4"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
4rd Division<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</span>:</td><td  style="text-align:left">
	    <div  >
	      <input id="address_country_d4_code" value="" type="hidden"/>
	      <input style="text-align:left;width:100%" id="address_country_d4" value="" ovalue="" />
	      <div id="address_country_d4_container"  ></div>
	    </div>
	  </td>
	</tr>
    <tr id="<?php echo $_smarty_tpl->tpl_vars['address_identifier']->value;?>
tr_address_country_d3" style="display:none">
	  <td class="label" ><span id="label_address_country_d3"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
3rd Division<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</span>:</td><td  style="text-align:left">
	    <div   >
	      <input id="address_country_d3_code" value="" type="hidden"/>
	      <input style="text-align:left;width:100%" id="address_country_d3" value="" ovalue="" />
	      <div id="address_country_d3_container"  ></div>
	    </div>
	  </td>
	</tr>
    <tr id="<?php echo $_smarty_tpl->tpl_vars['address_identifier']->value;?>
tr_address_country_d2">
    <td class="label" ><span id="<?php echo $_smarty_tpl->tpl_vars['address_identifier']->value;?>
label_address_country_d2"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Subregion<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</span>:</td>
        <td  style="text-align:left">
        <div  >
	    <input style="text-align:left;width:100%" id="<?php echo $_smarty_tpl->tpl_vars['address_identifier']->value;?>
address_country_d2" value="" ovalue="" />
	    	      <input id="<?php echo $_smarty_tpl->tpl_vars['address_identifier']->value;?>
address_country_d2_code" value="" type="hidden"/>
            <div id="<?php echo $_smarty_tpl->tpl_vars['address_identifier']->value;?>
address_country_d2_container"  ></div>
	      </div>
	    </td>
  </tr>
    <tr id="<?php echo $_smarty_tpl->tpl_vars['address_identifier']->value;?>
tr_address_country_d1">
	    <td class="label" >
	    
	    <span id="<?php echo $_smarty_tpl->tpl_vars['address_identifier']->value;?>
label_address_country_d1"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Region<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</span>:</td>
	    <td  style="text-align:left">
	     <div  >
	      <input style="text-align:left;width:100%" id="<?php echo $_smarty_tpl->tpl_vars['address_identifier']->value;?>
address_country_d1" value="" ovalue="" />
	      <input id="<?php echo $_smarty_tpl->tpl_vars['address_identifier']->value;?>
address_country_d1_code" value="" type="hidden"/>
	    <div id="<?php echo $_smarty_tpl->tpl_vars['address_identifier']->value;?>
address_country_d1_container"  ></div>
	      </div>
	      </td>
  </tr>
</tbody>

<tr id="<?php echo $_smarty_tpl->tpl_vars['address_identifier']->value;?>
tr_address_country"  class="first">
        <td class="label"  style="width:120px">
<span 
	        id="<?php echo $_smarty_tpl->tpl_vars['address_identifier']->value;?>
show_country_subregions" 
	        onclick="show_country_subregions('<?php echo $_smarty_tpl->tpl_vars['address_identifier']->value;?>
')" 
	        
	        style="cursor:pointer;display:none">&oplus;</span> 
            <?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Country<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
:</td>
        <td  >
        <div id="<?php echo $_smarty_tpl->tpl_vars['address_identifier']->value;?>
myAutoComplete" >
<input id="<?php echo $_smarty_tpl->tpl_vars['address_identifier']->value;?>
address_country" value="" type="hidden"/>
	       <select size="1" id="<?php echo $_smarty_tpl->tpl_vars['address_identifier']->value;?>
address_country_code" >
<option value="XX">Select One</option>
<?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['country_list']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value){
$_smarty_tpl->tpl_vars['item']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['item']->key;
?>
<option value="<?php echo $_smarty_tpl->tpl_vars['item']->value['code'];?>
"><?php echo $_smarty_tpl->tpl_vars['item']->value['country'];?>
</option>
<?php } ?>
</select>


	        <div id="<?php echo $_smarty_tpl->tpl_vars['address_identifier']->value;?>
address_country_container" ></div>
        </div>
        
    </td>

       
    </tr>  
<tr >
  <td colspan=2 style="text-align:right">
<button close_if_reset="<?php if ($_smarty_tpl->tpl_vars['close_if_reset']->value){?>Yes<?php }else{ ?>No<?php }?>" address_key="" style="<?php if (!$_smarty_tpl->tpl_vars['close_if_reset']->value){?>visibility:hidden<?php }?>" id="<?php echo $_smarty_tpl->tpl_vars['address_identifier']->value;?>
reset_address_button"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Cancel<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</button>
<button address_key="" style="visibility:'';margin-left:10px"id="<?php echo $_smarty_tpl->tpl_vars['address_identifier']->value;?>
save_address_button"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Save<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</button></td>

</tr>
  

<?php }} ?>
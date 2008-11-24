{include file='header.tpl'}
<div id="bd" >
  <div id="yui-main">
    <div style="width:300px;float:right;padding:10px;text-align:right">
      <span class="but new edit" id="add_user">Add User</span>
    </div>
<div class="data_table" style="margin-top:25px">
  <span class="clean_table_title">{t}Users{/t}</span>
  <div  class="clean_table_caption"  style="clear:both;">
    <div style="float:left;"><div class="clean_table_info">{$table_info} <span class="filter_msg"  id="filter_msg0"></span></div></div>
    <div class="clean_table_filter" style="display:none"><div class="clean_table_info">{$filter_name}: <input style="border-bottom:none" id='f_input0' value="{$filter_value}" size=10/><div id='f_container0'></div></div></div>
    <div class="clean_table_controls" style="" ><div><span  style="margin:0 5px" id="paginator0"></span></div></div>
  </div>
  <div  id="table0"   class="data_table_container dtable btable "> </div>
</div>
<div class="data_table" style="margin-top:25px;width:600px">
  <span class="clean_table_title">{t}Groups{/t}</span>
  <div  class="clean_table_caption"  style="clear:both;">
    <div style="float:left;"><div class="clean_table_info">{$table_info} <span class="filter_msg"  id="filter_msg1"></span></div></div>
    <div class="clean_table_filter" style="display:none"><div class="clean_table_info">{$filter_name}: <input style="border-bottom:none" id='f_input1' value="{$filter_value}" size=10/><div id='f_container1'></div></div></div>
    <div class="clean_table_controls" style="" ><div><span  style="margin:0 5px" id="paginator1"></span></div></div>
  </div>
  <div  id="table1"   class="data_table_container dtable btable "> </div>
</div>

  </div>
</div> 
<div id="add_user_supplier">

</div>
<div id="add_user_customer">

</div>
<div id="add_user_staff">
<div class="bd">

    <table border=1 class="staff_list"  style="margin:10px auto">
      {foreach from=$staff item=_staff name=foo}
      {if $_staff.mod==0}<tr>{/if}
	<td   staff_id="{$_staff.id}" id="staff{$_staff.id}" {if $_staff.is_user}class="selected" is_in="1" {else} onClick="select_staff(this)"  is_in="0"{/if} >{$_staff.alias}</td>
     {if $_staff.mod==$staff_cols}</tr>{/if}
      {/foreach}
    </table>

 <table class="edit" >
   <tr style="display:none" id="staff_handle_container"  ><td style="text-align:left" colspan=2>{t}Handle{/t}: <span style="font-weight:800" id="staff_handle" ></span></td></tr>
   <tr style="display:none" id="staff_v_handle_container"  ><td style="text-align:left" colspan=2>{t}Handle{/t}: <input id="staff_v_handle" value=""  /></td></tr>

   <tr style="display:none;border-bottom:1px solid black" style="" id="staff_choose_method"><td style="text-align:center" colspan=2><span id="staff_auto_pwd_but" class="but small" onClick="auto_pwd()">{t}Auto Password{/t}</span><span id="staff_user_defined_pwd_but"  onClick="user_defined_pwd()" class="but small" style="margin-left:20px ">{t}User Defined Password{/t}</span></td></tr>
   <tbody id="staff_auto_dialog" style="display:none">
   <tr><td>{t}Password{/t}:</td><td style="text-align:left"><span style="font-weight:800" id="staff_passwd" ></span></td></tr>
    </tbody>
   <tbody id="staff_user_definded_dialog" style="display:none">
   <tr><td colspan=2 id="password_meter" style="padding:0 30px"><div style="float:right" id="password_meter_str"></div><div id="password_meter_bar" style="visibility:hidden;;height:12px;border:1px solid #555; background:#bd0e00;width:0%;font-size:10px;text-align:left;">&nbsp;</div></td></tr>
   <tr><td>{t}Password{/t}:</td><td style="text-align:left"><input onKeyup="change_meter(this.value)" style="width:6em" type="password" id="staff_passwd1" value=""/></td></tr>
   <tr id="staff_repeat_password"><td><img  id="error_staff_passwd2" style="display:none" src="art/icons/exclamation.png" alt="!"/> {t}Repeat Password{/t}:</td><td style="text-align:left"><input onKeyup="match_passwd(this.value,'staff_passwd1','staff')" style="width:6em" type="password" id="staff_passwd2"  value=""/></td></tr>
   </tbody>
   <tr><td style="text-align:left;height:40px"><span style="cursor:pointer;margin-left:30px">{t}Cancel{/t} <img src="art/icons/cross.png"/></span></td><td><span  onclick="staff_new_user()" id="staff_new_save" style="visibility:hidden;cursor:pointer;margin-right:30px">{t}Save{/t} <img src="art/icons/disk.png" ></span></td></tr>


 </table>
 

</div>
</div>
<div id="add_user_other">
  <div class="hd">{t}New user{/t}</div>
  <div class="bd">
    <div class="resp" ></div>
    <form action="ar_users.php">
      <table>
      <input type="hidden" name="tipo" value="add_user"/>
      <input type="hidden" id="ep" name="ep" value=""/>
      <tr><td><label for="handle">{t}Handle{/t}:</label></td><td><input class="text"  type="text" value="" name="handle"/></td></tr>
      <tr><td><label for="name"  >{t}Name{/t}  :</label></td><td><input  class="text" type="text" value="" name="name"/></td></tr>
      <tr><td><label for="surname">{t}Surname{/t}:</label></td><td><input class="text" type="text" value="" name="surname"/></td></tr>
      <tr><td><label for="email">{t}Email{/t}:</label></td><td><input  class="text" type="text" value="" name="email"/></td></tr>
      <tr><td>
      <label for="lang[]">{t}Language{/t}:</label></td><td>
      <select name="lang[]">
	{foreach from=$newuser_langs item=lang key=lang_id}
	<option value="{$lang_id}">{$lang}</option>
	{/foreach}
      </select> 
      </td></tr>
      <tr><td>
      <label for="isactive">{t}Activate Account{/t}:</label></td><td>
      <input type="radio" value="1" name="isactive[]" checked="checked"  />{t}Yes{/t}
      </td></tr>
      <tr><td><label style="visibility:hidden">isactive:</label> </td><td>
      <input type="radio" value="0" name="isactive[]"  />{t}No{/t}
      </td></tr>
     <tr><td>
      <label for="group">{t}Groups{/t}:</label></td><td>
      {foreach from=$newuser_groups item=group key=group_id}
      <tr><td><label style="visibility:hidden">g</label></td><td><input type="checkbox" name="group[]" value="{$group_id}" />{$group}</td></tr>
      {/foreach}
      </table>
    </form>
  </div>
</div>


<div id="add_user_dialog">
  
  <div class="bd">
    <span>{t}Choose kind of user{/t}</span>
    <ul>
      <li><span style="cursor:pointer" onCLick="add_user_dialog.cfg.setProperty('visible', false);add_user_dialog_staff.show()">Staff</span></li>
      <li><span style="cursor:pointer"onCLick="add_user_dialog.cfg.setProperty('visible', false);add_user_dialog_supplier.show()">Supplier</span></li>
      <li><span style="cursor:pointer"onCLick="add_user_dialog.cfg.setProperty('visible', false);add_user_dialog_customer.show()">Customer</span></li>
      <li><span style="cursor:pointer"onCLick="add_user_dialog.cfg.setProperty('visible', false);add_user_dialog_others.show()">Other</span></li>
    </ul>
  </div>
</div>






{include file='footer.tpl'}


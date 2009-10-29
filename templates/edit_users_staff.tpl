{include file='header.tpl'}
<div id="bd" >
<span class="nav2 onright"><a href="users.php">{t}Exit Edit{/t}</a></span>

  <div id="yui-main">
    <div style="width:300px;float:right;padding:10px;text-align:right">
    
      
    </div>
    <div class="data_table" style="margin-top:25px">
      <span class="clean_table_title">{t}Users{/t}</span>
      <div  class="clean_table_caption"  style="clear:both;">
	<div style="float:left;"><div id="table_info0" class="clean_table_info"><span id="rtext0"></span> <span class="rtext_rpp" id="rtext_rpp0"></span> <span class="filter_msg"  id="filter_msg0"></span></div></div>
	<div class="clean_table_filter" id="clean_table_filter0"><div class="clean_table_info"><span id="filter_name0">{$filter_name0}</span>: <input style="border-bottom:none" id='f_input0' value="{$filter_value0}" size=10/><div id='f_container0'></div></div></div>
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

{include file='footer.tpl'}

<div id="change_staff_password" style="display:nonex;position:absolute;left:-100px;top:-150px;background:#fff;padding:20px;border:1px solid#777;font-size:90%">
  <div class="bd" >
    <span style="text-weight:800">{t}Change Password for{/t} <span user_id='' id="change_staff_password_alias"></span></span>
  <table class="edit inbox" >
    
    <tr class="tabs"  id="change_staff_choose_method">
      <td  colspan=2 >
	<span  id="change_staff_auto_pwd_but" class="tab unselectable_text" onClick="auto_pwd('change_staff')">{t}Change (Random){/t}</span>
	<span id="change_staff_user_defined_pwd_but"  onClick="user_defined_pwd('change_staff')" class="tab selected unselectable_text" style="margin-left:20px ">{t}Change (User Defined){/t}</span>
      </td>
    </tr>
     <tr style="height:30px"><td colspan=2 id="change_staff_password_meter" style="padding:0 30px"><div style="float:right" id="change_staff_password_meter_str"></div><div id="change_staff_password_meter_bar" style="visibility:hidden;;height:12px;border:1px solid #555; background:#bd0e00;width:0%;font-size:10px;text-align:left;">&nbsp;</div></td></tr>
    <tbody id="change_staff_auto_dialog" style="display:none">
      <tr class="bottom"><td>{t}Password{/t}:</td><td style="text-align:left"><span style="font-weight:800" id="change_staff_passwd" ></span></td></tr>
    </tbody>

    <tbody id="change_staff_user_defined_dialog" >
      <tr><td>{t}Password{/t}:</td><td style="text-align:left"><input onKeyup="change_meter(this.value,'change_staff')" style="width:11em" type="password" id="change_staff_passwd1" value=""/></td></tr>
      <tr id="change_staff_repeat_password" class="bottom"><td style="vertical-align:top" ><img  id="change_staff_error_passwd2" style="visibility:hidden" src="art/icons/exclamation.png" alt="!"/> {t}Repeat Password{/t}:</td><td style="text-align:left"><input onKeyup="match_passwd(this.value,'change_staff_passwd1','change_staff')" style="width:11em" type="password" id="change_staff_passwd2"  value=""/></td></tr>
    </tbody>
    <tr class="buttons" ><td style="text-align:left"><span id="change_staff_cancel" style="margin-left:30px" class="unselectable_text button" onClick="close_change_password_dialog()">{t}Cancel{/t} <img src="art/icons/cross.png"/></span></td><td><span  onclick="change_staff_pwd()" id="change_staff_save"   class="unselectable_text button"     style="visibility:hidden;margin-right:30px">{t}Save{/t} <img src="art/icons/disk.png" ></span></td></tr>
  </table>
  </div>
</div>

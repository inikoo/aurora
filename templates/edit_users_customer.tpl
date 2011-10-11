{include file='header.tpl'}
<div id="bd" >
<div>
<span class="nav2 onleft"><a class="selected" href="users_staff.php">Staff Users</a></span>
  <span class="nav2 onleft"><a href="users_customer.php">Customer Users</a></span>
  <span class="nav2 onleft"><a href="users_supplier.php">Supplier Users</a></span>
</div>
<div class="search_box" align='right'>
  <div class="general_options">
<span id="exit">{t}Exit Edit{/t}</span>
</div>  
         
</div>  



  <div id="yui-main">
    
    <div class="data_table" >
      <span class="clean_table_title">{t}Customer Users{/t}</span>
      
      
     
  {*     <table  style="float:left;margin:0 0 0 0px ;margin-left:0;padding:0;clear:left"  class="options_mini" >
     <tr  id="orders_show_only"   style="margin-left:0;padding:0"  >
       <td  style="margin-left:20px;xmargin:5px 15px 0 0px ;padding:0;border:none;color:#555"  >{t}show only{/t}:</td>       
       <td   {if $display=='active'}class="selected"{/if}  id="active"  >{t}Active (Employees){/t}</td>
       <td   {if $dispply=='inactive_current'}class="selected"{/if}  id="inactive_current"  >{t}Inactive (Employees){/t}</td>
       <td   {if $display=='inactive_ex'}class="selected"{/if}  id="inactive_ex"  >{t}Inactive (Ex-employees){/t}</td>
     </tr>
       </table>
 *}    
      
      
      
      
      <div  class="clean_table_caption"  style="clear:both;">
	<div style="float:left;"><div id="table_info0" class="clean_table_info"><span id="rtext0"></span> <span class="rtext_rpp" id="rtext_rpp0"></span> <span class="filter_msg"  id="filter_msg0"></span></div></div>
	<div class="clean_table_filter" id="clean_table_filter0"><div class="clean_table_info"><span id="filter_name0" class="filter_name" >{$filter_name0}</span>: <input style="border-bottom:none" id='f_input0' value="{$filter_value0}" size=10/><div id='f_container0'></div></div></div>
	<div class="clean_table_controls"  ><div><span  style="margin:0 5px" id="paginator0"></span></div></div>
      </div>
      <div  id="table0"   class="data_table_container dtable btable "> </div>
    </div>
 
    
  </div>
</div> 

{include file='footer.tpl'}

<div id="change_staff_password" style="display:nonex;position:absolute;xleft:-100px;xtop:-150px;background:#fff;padding:10px 20px 20px 20px;border:1px solid#777;font-size:90%">
  <div class="bd" >
    <h2 >{t}Change Password for{/t} <span user_id='' id="change_staff_password_alias"></span></h2>

<div style="margin-top:20px"> 
 	<span stype="position:relative;bottom:3px" id="change_staff_auto_pwd_but" class="tab unselectable_text" onClick="auto_pwd('change_staff')">{t}Change (Random){/t}</span>
	<span id="change_staff_user_defined_pwd_but"  onClick="user_defined_pwd('change_staff')" class="tab selected unselectable_text" style="margin-left:20px">{t}Change (User Defined){/t}</span>
 </div>
 <table class="edit inbox" border=0 >
    
   
    
     <tr style="height:30px;border-top:1px solid #777"><td colspan=2 id="change_staff_password_meter" style="padding:0 40px 8px 40px;;vertical-align:bottom"><div style="float:right;" id="change_staff_password_meter_str"></div><div id="change_staff_password_meter_bar" style="visibility:hidden;;height:12px;border:1px solid #555; background:#bd0e00;width:0%;font-size:10px;text-align:left;">&nbsp;</div></td></tr>
    <tbody id="change_staff_auto_dialog" style="display:none" >
      <tr style="height:50px" class="bottom"><td>{t}Password{/t}:</td><td style="text-align:left"><span style="font-weight:800" id="change_staff_passwd" ></span></td></tr>
    </tbody>

    <tbody id="change_staff_user_defined_dialog" >
      <tr style="height:20px" > <td>{t}Password{/t}:</td><td style="text-align:left"><input onKeyup="change_meter(this.value,'change_staff')" style="width:11em" type="password" id="change_staff_passwd1" value=""/></td></tr>
      <tr style="height:30px"  id="change_staff_repeat_password" class="bottom"><td style="vertical-align:top;text-align:left" >{t}Repeat Password{/t}:<img  id="change_staff_error_passwd2" style="visibility:hidden" src="art/icons/exclamation.png" alt="!"/></td><td style="text-align:left"><input onKeyup="match_passwd(this.value,'change_staff_passwd1','change_staff')" style="width:11em" type="password" id="change_staff_passwd2"  value=""/></td></tr>
    </tbody>
    <tr class="buttons" ><td style="text-align:left"><span id="change_staff_cancel" style="margin-left:30px" class="unselectable_text button" onClick="close_change_password_dialog()">{t}Cancel{/t} <img src="art/icons/cross.png"/></span></td><td><span  onclick="change_staff_pwd()" id="change_staff_save"   class="unselectable_text button"   style="visibility:hidden;margin-right:30px">{t}Save{/t} <img src="art/icons/disk.png" ></span></td></tr>
  </table>
  </div>
</div>

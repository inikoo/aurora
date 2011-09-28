{include file='header.tpl'}
<div id="bd" style="padding:0" >

<div style="padding:0 20px">
{include file='users_navigation.tpl'}
<div > 
  <span   class="branch"><a  href="users.php">{t}Users{/t}</a> &rarr; <a  href="users_staff.php">{t}Staff Users{/t}</a> &rarr; {$user_class->get('User Alias')}</span>
</div>

<input id="user_key" value="{$user_class->id}" type="hidden"/>


    <h1>{t}Staff User{/t}: {$user_class->get('User Alias')}</h1>


<div style="clear:both"></div>
<div style="width:230px;margin-top:0px;float:left">
	<table    class="show_info_product">
		  <td class="aright">
		    
		     <tr >
		       <td>{t}Login{/t}:</td>
		        <td>{$user_class->get('User Handle')}</td>
			</tr>
		     <tr>
		       <td>{t}Alias{/t}:</td>
		        <td>{$user_class->get('User Alias')}</td>
		     </tr>
		</table>
		
</div>
<div style="width:310px;margin-top:0px;float:left;margin-left:20px">
	<table    class="show_info_product">
		  <td class="aright">
		    
		     <tr >
		       <td>{t}Login Count{/t}:</td>
		        <td>{$user_class->get('Login Count')}</td>
			</tr>
		     <tr>
		       <td>{t}Last Login{/t}:</td>
		        <td>{$user_class->get('Last Login')}</td>
		     </tr>
		</table>
		
</div>
<div style="width:310px;margin-top:0px;float:left;margin-left:20px">
	<table    class="show_info_product">
		  <td class="aright">
		    
		     <tr >
		       <td>{t}Failed Login Count{/t}:</td>
		        <td>{$user_class->get('Failed Login Count')}</td>
			</tr>
		     <tr style="{if $user_class->get('Failed Login Count')==0}visibility:hidden{/if}">
		       <td>{t}Failed Last Login{/t}:</td>
		        <td>{$user_class->get('Last Failed Login')}</td>
		     </tr>
		</table>
		
</div>  
	<div style="clear:both"></div>
		</div>
		
		
		
		<ul class="tabs" id="chooser_ul" style="clear:both;margin-top:25px">
    <li> <span class="item {if $block_view=='login_history'}selected{/if}"  id="login_history">  <span> {t}Login History{/t}</span></span></li>
    <li> <span class="item {if $block_view=='access'}selected{/if}"  id="access">  <span> {t}System Permissions{/t}</span></span></li>
    <li> <span class="item {if $block_view=='email'}selected{/if}"  id="email">  <span> {t}Email Account{/t}</span></span></li>
  
  </ul>
<div  style="clear:both;width:100%;border-bottom:1px solid #ccc"></div>

<div style="padding:0 20px">
		
		
 <div id="block_details" style="{if $block_view!='details'}display:none;{/if}clear:both;margin:10px 0 40px 0">
</div>
	
 <div id="block_login_history" style="{if $block_view!='login_history'}display:none;{/if}clear:both;margin:10px 0 40px 0">

  
      <span class="clean_table_title">{t}Login History{/t}</span>
         {include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0  }
      <div  id="table0"   class="data_table_container dtable btable "> </div>
    </div>    
 
 <div id="block_access" style="{if $block_view!='access'}display:none;{/if}clear:both;margin:10px 0 40px 0">
</div>
 <div id="block_email" style="{if $block_view!='email'}display:none;{/if}clear:both;margin:10px 0 40px 0">


  

</div>
</div>
</div>
{include file='footer.tpl'}



<div id="change_staff_password" style="display:none;position:absolute;xleft:-100px;xtop:-150px;background:#fff;padding:10px 20px 20px 20px;border:1px solid#777;font-size:90%">
  <div class="bd" >
	<input type="hidden" name="change_staff_password_alias" id="change_staff_password_alias" value="{$user_id}">
    <h2 >{t}Change Password for{/t} <span>{$user_name}</span></h2>

<div style="margin-top:20px"> 
 	<span stype="position:relative;bottom:3px" id="change_staff_auto_pwd_but" class="tab unselectable_text" onClick="auto_pwd('change_staff')">{t}Change (Random){/t}</span>
	<span id="change_staff_user_defined_pwd_but"  onClick="user_defined_pwd('change_staff')" class="tab selected unselectable_text" style="margin-left:20px">{t}Change (User Defined){/t}</span>
 </div>
 <table class="edit inbox" border=0 >
    
   
    
     <tr style="height:30px;border-top:1px solid #777">
	<td colspan=2 id="change_staff_password_meter" style="padding:0 40px 8px 40px;;vertical-align:bottom">
		<div style="float:right;" id="change_staff_password_meter_str"></div><div id="change_staff_password_meter_bar" style="visibility:hidden;;height:12px;border:1px solid #555; background:#bd0e00;width:0%;font-size:10px;text-align:left;">&nbsp;</div>
	</td>
    </tr>

    <tbody id="change_staff_auto_dialog" style="display:none" >
      <tr style="height:50px" class="bottom">
	<td>{t}Password{/t}:</td>
	<td style="text-align:left">
		<span style="font-weight:800" id="change_staff_passwd" ></span>
	</td>
      </tr>
    </tbody>

    
	<tbody id="change_staff_user_defined_dialog" >
      		<tr style="height:20px" > 
			<td>{t}Password{/t}:</td>
			<td style="text-align:left">
	<input onKeyup="change_meter(this.value,'change_staff')" style="width:11em" type="password" id="change_staff_passwd1" value=""/>
			</td>
		</tr>
		
		<tr style="height:30px"  id="change_staff_repeat_password" class="bottom">
			<td style="vertical-align:top;text-align:left" >{t}Repeat Password{/t}:<img  id="change_staff_error_passwd2" style="visibility:hidden" src="art/icons/exclamation.png" alt="!"/></td>
			<td style="text-align:left">
	<input onKeyup="match_passwd(this.value,'change_staff_passwd1','change_staff')" style="width:11em" type="password" id="change_staff_passwd2"  value=""/>
			</td>
		</tr>
    	</tbody>


    		<tr class="buttons" >
			<td style="text-align:left">
				<span id="change_staff_cancel" style="margin-left:30px" class="unselectable_text button" onClick="close_change_password_dialog()">{t}Cancel{/t} <img src="art/icons/cross.png"/></span></td>
			<td><span  onclick="change_staff_pwd()" id="change_staff_save"   class="unselectable_text button"   style="visibility:hidden;margin-right:30px">{t}Save{/t} <img src="art/icons/disk.png" ></span>
			</td>
		</tr>
  </table>
</div>
</div>


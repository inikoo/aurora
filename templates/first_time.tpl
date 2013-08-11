{include file='header.tpl'}
<input type="hidden" id="User_Active" value="Yes">
<input type="hidden" id="User_Alias" value="superuser">
<input type="hidden" id="User_Created" value="2011-06-24 14:25:07">
<input type="hidden" id="User_Type" value="Administrator">

<span>xxxxxxxx</span>
<div id="bd" class="no_padding">

	<div id="wrapper">
	<input type="hidden" value='{$store_keys}' id="store_keys"/>
	
		<div id="wid_menu" >
			<img style="position:relative;top:3px;display:none" src="art/icons/previous.png" alt="" id="previous"/>
			<ul id="buttons">
				<li id="splinter_but_create_superuser" key="create_superuser" class="splinter_buttons active" onClick="change_block(this)">Create Superuser</li>
				<li id="splinter_but_new_company" key="new_company" class="splinter_buttons " onClick="change_block(this)">Add Company</li>
				<li id="splinter_but_new_store" key="new_store" class="splinter_buttons " onClick="change_block(this)">Add Store</li>
			</ul>
			<img style="position:relative;top:3px;display:none" src="art/icons/next.png" alt="" id="next"/>
		</div>

		

		<div id="panes">
			<div id="content">
			
			<div class="pane"  id="pane_create_superuser" {if $display_block!='create_superuser'}style="display:none"{/if}>
			<h1>{t}Create Super User{/t}</h1>
		<table class="edit"  border=0 style="width:100%;margin-bottom:0px" >
		<tbody id="company_section">	
			<tr class="first">
			<td style="width:120px" class="label">{t}User Handle{/t}:</td>
			  <td  style="text-align:left;width:350px">
				<div   >
				  <input style="text-align:left;" id="User_Handle" value="" ovalue="" valid="0">
				  <div id="User_Handle_Container"  ></div>
				</div>
			  </td>
			  <td style="width:70px"></td>
			  
			</tr>
			<tr>
				<td style="width:120px" class="label">{t}User Password{/t}:</td>
			  <td  style="text-align:left;width:350px">
				<div   >
				  <input style="text-align:left;" id="User_Password" value="" ovalue="" valid="0">
				  <div id="User_Password_Container"  ></div>
				</div>
			  </td>
			  <td style="width:70px"></td>
			  
			</tr>
		</tbody>
		</table>
		<table class="options" border=0 style="font-size:120%;margin-top:20px;;float:right;padding:0">
			<tr>
				<td  id="creating_message" style="border:none;display:none">{t}Creating Contact{/t}</td>
				<td  class="disabled" id="save_new_super_user">{t}Save{/t}</td>
				<td  id="cancel_new_super_user">{t}Cancel{/t}</td>
			</tr>
		</table>

			</div>
			
			<div class="pane"  id="pane_new_company" {if $display_block!='new_company'}style="display:none"{/if}>
			<h1>{t}Add new Company{/t}</h1>
			</div>
			
			<div class="pane"  id="pane_new_store" {if $display_block!='new_store'}style="display:none"{/if}>
			<h1>{t}Add new Store{/t}</h1>
			</div>

			</div>
			
			
		</div>

	</div>
	{literal}
	<script type="text/javascript" charset="utf-8">
	
	
	
	
	</script>
{/literal}

</div>
{include file='footer.tpl'}

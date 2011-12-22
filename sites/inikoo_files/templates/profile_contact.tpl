<input type="hidden" id="user_key" value="{$user->id}" />
<input type="hidden" id="store_key" value="{$store->id}" />
<input type="hidden" id="site_key" value="{$site->id}" />
<input type="hidden" id="customer_key"  value="{$page->customer->id}"/>

<div class="top_page_menu" style="padding:0px 20px 5px 20px">
<div class="buttons" style="float:left">
<button onclick="window.location='profile.php?view=change_password'" ><img src="art/icons/chart_organisation.png" alt=""> {t}Change Password{/t}</button>
<button onclick="window.location='profile.php?view=address_book'" ><img src="art/icons/chart_organisation.png" alt=""> {t}Address Book{/t}</button>
<button onclick="window.location='profile.php?view=orders'" ><img src="art/icons/table.png" alt=""> {t}Orders{/t}</button>
<button class="selected"  onclick="window.location='profile.php?view=contact'" ><img src="art/icons/chart_pie.png" alt=""> {t}My Account{/t}</button>


</div>


<div style="clear:both">

</div>
</div>



<div id="contact_block" {if $view!='contact'}style="display:none"{/if}>
<div style="border:0px solid #ccc;padding:0px 20px;width:890px;font-size:15px;margin:0px auto;margin-top:20px">
<div style="float:left;;border:1px solid #ccc;;height:60px;width:100px;;padding:5px 20px">Thank you form trading with us!</div>

{include file='customer_badges.tpl' customer=$page->customer}

<div style="clear:both"></div>
</div>

<div style="clear:both"></div>
<div style="padding:0px 20px;float:left">
<h2>{t}Contact Details{/t}</h2>
<div style="border:1px solid #ccc;padding:20px;width:400px;font-size:15px">
<h3>{$page->customer->get('Customer Name')} ({$page->customer->get_formated_id()})</h3> 

<table id="customer_data" border=0 style="width:100%;margin-top:20px">
    <tr >
        <td >{t}Company{/t}:</td>
        <td><img  id="show_edit_name" style="cursor:pointer"  src="art/edit.gif" alt="{t}Edit{/t}"/></td>
        <td  class="aright">{$page->customer->get('Customer Company Name')}</td >
    </tr>

<tr><td>{t}Name{/t}:</td><td><img style="cursor:pointer" src="art/edit.gif" alt="{t}Edit{/t}"/></td><td  class="aright">{$page->customer->get('Customer Main Contact Name')}</td ></tr>

{if $page->customer->get('Customer Main Email Key')}
<tr id="main_email_tr" >
<td>{t}Email{/t}</td>
<td><img src="art/lock.png"></td>
<td id="main_email"class="aright">{$page->customer->get('customer main plain email')}</td >

{/if}

{foreach from=$page->customer->get_other_emails_data() item=other_email key=key name=foo}
    <tr id="other_email_tr">
    <td>{t}Email{/t}</td>
    <td id="email{$key}"    class="aright">{$other_email.plain}</td >
    </tr>
{/foreach}


<tr><td>{t}Telephone{/t}:</td><td><img src="art/edit.gif" alt="{t}Edit{/t}"/></td><td  class="aright">{$page->customer->get('Customer Main Contact Name')}</td ></tr>


<tr><td>
<div class="buttons">
<button style="display:none"  onclick="window.location='client.php'" ><img src="art/icons/chart_pie.png" alt=""> {t}Edit Profile{/t}</button>

</div>
</td></tr>
</table>

</div>
</div>
<div style="padding:0px 20px;float:right">
<h2>{t}Notes{/t}</h2>
<div style="border:1px solid #ccc;padding:20px;width:400px;font-size:15px"></div>
</div>


</div>
       











<div style="top:180px;left:490px;position:absolute;display:none;background-image:url('art/background_badge_info.jpg');width:200px;height:223px;" id="gold_reward_badge_info">
<p style="padding:40px 20px;font-size:20px;margin:20px auto">
bla bla bla
<br/>
<a href="" >More Info</a>
<p>
</div>


<div id="dialog_quick_edit_Customer_Name" style="padding:10px">
	<table style="margin:10px">
	
	<tr>
	<td>{t}Customer Name:{/t}</td>
	<td>
	<div style="width:220px">
	<input type="text" id="Customer_Name" value="{$page->customer->get('Customer Company Name')}" ovalue="{$page->customer->get('Customer Company Name')}" valid="0">
	<div id="Customer_Name_Container"  ></div>
	</div>	
	</td>

	</tr>
	<tr><td colspan=2>
	<div class="buttons" style="margin-top:10px">
	<span id="Customer_Name_msg" ></span>
	<button class="positive" onClick="save_quick_edit_name()">{t}Save{/t}</button>
	<button class="negative" id="close_quick_edit_name">{t}Cancel{/t}</button>

	</div>
	</td></tr>
	</table>

</div>




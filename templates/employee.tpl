{include file='header.tpl'} 
<div id="bd" class="no_padding">
	<div style="padding:0px 20px">
		<div class="branch">
			<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /></a> &rarr; <a href="hr.php">{$account_label}</a> &rarr; {$employee->get('Staff Name')} </span> 
		</div>
		<div id="top_page_menu" class="top_page_menu" style="margin-top:10px">
			{if isset($parent_list)}<img onmouseover="this.src='art/previous_button.gif'" onmouseout="this.src='art/previous_button.png'" title="{t}Previous Customer{/t} {$prev.name}" onclick="window.location='customer.php?{$parent_info}id={$prev.id}{if $parent_list}&p={$parent_list}{/if}'" src="art/previous_button.png" alt="<" style="margin-right:10px;float:left;height:22px;cursor:pointer;position:relative;top:2px" />{/if} 
			<div class="buttons" style="float:left">
				<h1 style="padding-bottom:0px">
					{$employee->get('Staff Name')}, <span style="color:SteelBlue"> {$employee->get('Staff Alias')} <i>({$employee->get('Staff ID')})</i></span> 
				</h1>
			</div>
			{if isset($parent_list)}<img onmouseover="this.src='art/next_button.gif'" onmouseout="this.src='art/next_button.png'" title="{t}Next Customer{/t} {$next.name}" onclick="window.location='customer.php?{$parent_info}id={$next.id}{if $parent_list}&p={$parent_list}{/if}'" src="art/next_button.png" alt=">" style="float:right;height:22px;cursor:pointer;position:relative;top:2px" />{/if} 
			<div class="buttons" style="float:right">
				<button onclick="window.location='edit_employee.php?id={$employee->id}{if isset($parent_list)}&p={$parent_list}{/if}'"><img src="art/icons/vcard_edit.png" alt=""> {t}Edit{/t}</button> 
			</div>
			<div style="clear:both">
			</div>
		</div>
		
		
		<table id="customer_data" border="0" style="width:100%;border-collapse: collapse;">
				<tr>
					<td colspan="2"> 
					<div style="border:0px solid red;float:left;margin-right:20px;position:relative">
						{if $employee->get('Staff Main Image')} <img id="avatar" src="{$employee->get('Staff Main Image')}" style="cursor:pointer;border:1px solid #eee;height:45px;max-width:100px"> {else} <img id="avatar" src="art/avatar.jpg" style="cursor:pointer;"> {/if} {if $employee->get('Customer Level Type')=='VIP'}<img src="art/icons/shield.png" style="position:absolute;xtop:-36px;left:40px">{/if} {if $employee->get('Customer Level Type')=='Partner'}<img src="art/icons/group.png" style="position:absolute;xtop:-36px;left:40px">{/if} 
					</div>
					<h1 style="padding-bottom:0px;width:300px">
						<span id="customer_name_heading" style="padding:2px 7px;padding-left:0;border:1px dotted #fff" onmouseover="Dom.setStyle('quick_edit_name_edit','visibility','visible')" onmouseout="Dom.setStyle('quick_edit_name_edit','visibility','hidden')"><span id="customer_name">{$employee->get('Customer Name')}</span> <img onmouseover="Dom.addClass('customer_name_heading','edit_over')" onmouseout="Dom.removeClass('customer_name_heading','edit_over')" id="quick_edit_name_edit" style="cursor:pointer;visibility:hidden;padding-bottom:3px" src="art/icons/edit.gif"></span> 
					</h1>
					<table class="customer_show_data">
						{if $employee->get('Customer Main Contact Key')} 
						<tr id="main_contact_name_tr" onmouseover="Dom.setStyle('quick_edit_main_contact_name_edit','visibility','visible')" onmouseout="Dom.setStyle('quick_edit_main_contact_name_edit','visibility','hidden')">
							<td id="main_contact_name" colspan="2" class="aright">{$employee->get('Customer Main Contact Name')}</td>
							<td><img alt="{t}Name{/t}" title="{t}Name{/t}" src="art/icons/user_suit.png" /></td>
							<td><img onmouseover="Dom.addClass('main_contact_name_tr','edit_over')" onmouseout="Dom.removeClass('main_contact_name_tr','edit_over')" id="quick_edit_main_contact_name_edit" style="cursor:pointer;visibility:hidden" src="art/icons/edit.gif"></td>
						</tr>
						{/if} 
					</table>
					</td>
					<td> </td>
				</tr>
				<tr>
					{if $employee->get('Customer Main Address Key')} 
					<td id="main_address_td" style="border:1px dotted #fff" onmouseover="Dom.setStyle('quick_edit_main_address','visibility','visible')" onmouseout="Dom.setStyle('quick_edit_main_address','visibility','hidden')"> <img onmouseover="Dom.addClass('main_address_td','edit_over')" onmouseout="Dom.removeClass('main_address_td','edit_over')" id="quick_edit_main_address" style="float:right;cursor:pointer;visibility:hidden" src="art/icons/edit.gif"> 
					<div id="main_address">
						{$employee->get('Customer Main XHTML Address')} 
					</div>
					<div style="margin-top:3px" class="buttons small left">
						<button onclick="window.open('customers_address_label.pdf.php?type=customer&id={$employee->id}&label=99012')"><img style="height:12px" src="art/icons/printer.png" alt=""> {t}Label{/t}</button> 
					</div>
					</td>
					{/if} 
					<td valign="top"> 
					<table class="customer_show_data">
						{if $employee->get('Customer Registration Number')} 
						<tr id="registration_number_tr" onmouseover="Dom.setStyle('quick_edit_registration_number','visibility','visible')" onmouseout="Dom.setStyle('quick_edit_registration_number','visibility','hidden')">
							<td id="registration_number" colspan="2" class="aright">{$employee->get('Customer Registration Number')}</td>
							<td><img alt="{t}Registration Number{/t}" title="{t}Registration Number{/t}" src="art/icons/certificate.png" /></td>
							<td><img onmouseover="Dom.addClass('registration_number_tr','edit_over')" onmouseout="Dom.removeClass('registration_number_tr','edit_over')" id="quick_edit_registration_number" style="cursor:pointer;visibility:hidden" src="art/icons/edit.gif"></td>
						</tr>
						{/if} {if $employee->get('Customer Tax Number')} 
						<tr id="tax_tr" onmouseover="Dom.setStyle('quick_edit_tax','visibility','visible')" onmouseout="Dom.setStyle('quick_edit_tax','visibility','hidden')">
							<td id="tax" colspan="2" class="aright">{$employee->get('Customer Tax Number')}</td>
							<td> <img id="check_tax_number" onclick="check_tax_number" alt="{t}Tax Number{/t}" title="{t}Tax Number{/t}" style="width:16px;cursor:pointer" src="{if $employee->get('Customer Tax Number Valid')=='No'}art/icons/taxation_error.png{elseif $employee->get('Customer Tax Number Valid')=='Yes' and $employee->get('Customer Tax Number Details Match')=='No' }art/icons/taxation_yellow.png{elseif $employee->get('Customer Tax Number Valid')=='Yes'}art/icons/taxation_green.png{else}art/icons/taxation.png{/if}" /> </td>
							<td><img onmouseover="Dom.addClass('tax_tr','edit_over')" onmouseout="Dom.removeClass('tax_tr','edit_over')" id="quick_edit_tax" style="cursor:pointer;visibility:hidden" src="art/icons/edit.gif"></td>
						</tr>
						{/if} {if $employee->get('Customer Main Email Key')!=''} 
						<tr id="main_email_tr" onmouseover="Dom.setStyle('quick_edit_email','visibility','visible')" onmouseout="Dom.setStyle('quick_edit_email','visibility','hidden')">
							<td id="main_email" colspan="2" class="aright">{if $employee->get_main_email_user_key()}<a href="site_user.php?id={$employee->get_main_email_user_key()}"><img src="art/icons/world.png" style="width:12px" title="{t}Register User{/t}" alt="{t}Register User{/t}"></a>{/if} {$employee->get('Customer Main XHTML email')}</td>
							<td><img alt="{t}Email{/t}" title="{t}Email{/t}" src="art/icons/email.png" /></td>
							<td id="email_label{$employee->get('Customer Main Email Key')}" style="color:#777;font-size:80%">{$employee->get_principal_email_comment()} <img onmouseover="Dom.addClass('main_email_tr','edit_over')" onmouseout="Dom.removeClass('main_email_tr','edit_over')" id="quick_edit_email" style="cursor:pointer;visibility:hidden" src="art/icons/edit.gif"></td>
						</tr>
						{/if} {foreach from=$employee->get_other_emails_data() item=other_email key=key} 
						<tr id="other_email_tr" onmouseover="Dom.setStyle('quick_edit_other_email{$key}','visibility','visible')" onmouseout="Dom.setStyle('quick_edit_other_email{$key}','visibility','hidden')">
							<td id="email{$key}" colspan="2" class="aright">{if $other_email.user_key}<a href="site_user.php?id={$other_email.user_key}"><img src="art/icons/world.png" style="width:12px" title="{t}Register User{/t}" alt="{t}Register User{/t}"></a>{/if} {$other_email.xhtml}</td>
							<td><img alt="{t}Email{/t}" title="{t}Email{/t}" src="art/icons/email.png" /></td>
							<td id="email_label{$key}" style="color:#777;font-size:80%">{$other_email.label} <img onmouseover="Dom.addClass('other_email_tr','edit_over')" onmouseout="Dom.removeClass('other_email_tr','edit_over')" id="quick_edit_other_email{$key}" style="cursor:pointer;visibility:hidden" src="art/icons/edit.gif"></td>
						</tr>
						{/foreach} {if $employee->get('Customer Main Telephone Key')} 
						<tr id="main_telephone_tr" onmouseover="Dom.setStyle('quick_edit_main_telephone','visibility','visible')" onmouseout="Dom.setStyle('quick_edit_main_telephone','visibility','hidden')">
							<td id="main_telephone" colspan="2" class="aright" style="{if $employee->get('Customer Main XHTML Mobile') and $employee->get('Customer Preferred Contact Number')=='Telephone'}font-weight:800{/if}">{$employee->get('Customer Main XHTML Telephone')}</td>
							<td><img alt="{t}Main Telephone{/t}" title="{t}Main Telephone{/t}" src="art/icons/telephone.png" /></td>
							<td id="telephone_label{$employee->get('Customer Main Telephone Key')}" style="color:#777;font-size:80%">{$employee->get_principal_telecom_comment('Telephone')} <img onmouseover="Dom.addClass('main_telephone_tr','edit_over')" onmouseout="Dom.removeClass('main_telephone_tr','edit_over')" id="quick_edit_main_telephone" style="cursor:pointer;visibility:hidden" src="art/icons/edit.gif"></td>
						</tr>
						{/if} {foreach from=$employee->get_other_telephones_data() item=other_tel key=key} 
						<tr id="other_telephone_tr" onmouseover="Dom.setStyle('quick_edit_other_telephone{$key}','visibility','visible')" onmouseout="Dom.setStyle('quick_edit_other_telephone{$key}','visibility','hidden')">
							<td id="telephone{$key}" colspan="2" class="aright">{$other_tel.xhtml}</td>
							<td><img alt="{t}Telephone{/t}" title="{t}Telephone{/t}" src="art/icons/telephone.png" /></td>
							<td id="telephone_label{$key}" style="color:#777;font-size:80%">{$other_tel.label} <img onmouseover="Dom.addClass('other_telephone_tr','edit_over')" onmouseout="Dom.removeClass('other_telephone_tr','edit_over')" id="quick_edit_other_telephone{$key}" style="cursor:pointer;visibility:hidden" src="art/icons/edit.gif"></td>
						</tr>
						{/foreach} {if $employee->get('Customer Main Mobile Key')} 
						<tr id="main_mobile_tr" onmouseover="Dom.setStyle('quick_edit_main_mobile','visibility','visible')" onmouseout="Dom.setStyle('quick_edit_main_mobile','visibility','hidden')">
							<td id="main_mobile" colspan="2" class="aright" style="{if $employee->get('Customer Main XHTML Telephone') and $employee->get('Customer Preferred Contact Number')=='Mobile'}font-weight:800{/if}">{$employee->get('Customer Main XHTML Mobile')}</td>
							<td><img alt="{t}Mobile{/t}" title="{t}Mobile{/t}" src="art/icons/phone.png" /></td>
							<td id="mobile_label{$employee->get('Customer Main Mobile Key')}" style="color:#777;font-size:80%">{$employee->get_principal_telecom_comment('Mobile')} <img onmouseover="Dom.addClass('main_mobile_tr','edit_over')" onmouseout="Dom.removeClass('main_mobile_tr','edit_over')" id="quick_edit_main_mobile" style="cursor:pointer;visibility:hidden" src="art/icons/edit.gif"></td>
						</tr>
						{/if} {foreach from=$employee->get_other_mobiles_data() item=other_tel key=key} 
						<tr id="other_mobile_tr" onmouseover="Dom.setStyle('quick_edit_other_mobile{$key}','visibility','visible')" onmouseout="Dom.setStyle('quick_edit_other_mobile{$key}','visibility','hidden')">
							<td id="mobile{$key}" colspan="2" class="aright">{$other_tel.xhtml}</td>
							<td><img alt="{t}Mobile{/t}" title="{t}Mobile{/t}" src="art/icons/phone.png" /></td>
							<td id="mobile_label{$key}" style="color:#777;font-size:80%">{$other_tel.label} <img onmouseover="Dom.addClass('other_mobile_tr','edit_over')" onmouseout="Dom.removeClass('other_mobile_tr','edit_over')" id="quick_edit_other_mobile{$key}" style="cursor:pointer;visibility:hidden" src="art/icons/edit.gif"></td>
						</tr>
						{/foreach} {if $employee->get('Customer Main FAX Key')} 
						<tr id="main_fax_tr" onmouseover="Dom.setStyle('quick_edit_main_fax','visibility','visible')" onmouseout="Dom.setStyle('quick_edit_main_fax','visibility','hidden')">
							<td id="main_fax" colspan="2" class="aright">{$employee->get('Customer Main XHTML FAX')}</td>
							<td><img alt="{t}Fax{/t}" title="{t}Fax{/t}" src="art/icons/printer.png" /></td>
							<td id="fax_label{$employee->get('Customer Main FAX Key')}" style="color:#777;font-size:80%">{$employee->get_principal_telecom_comment('FAX')} <img onmouseover="Dom.addClass('main_fax_tr','edit_over')" onmouseout="Dom.removeClass('main_fax_tr','edit_over')" id="quick_edit_main_fax" style="cursor:pointer;visibility:hidden" src="art/icons/edit.gif"></td>
						</tr>
						{/if} {foreach from=$employee->get_other_faxes_data() item=other_tel key=key} 
						<tr id="other_fax_tr" onmouseover="Dom.setStyle('quick_edit_other_fax{$key}','visibility','visible')" onmouseout="Dom.setStyle('quick_edit_other_fax{$key}','visibility','hidden')">
							<td id="fax{$key}" colspan="2" class="aright">{$other_tel.xhtml}</td>
							<td><img alt="{t}Fax{/t}" title="{t}Fax{/t}" src="art/icons/printer.png" /></td>
							<td id="fax_label{$key}" style="color:#777;font-size:80%">{$other_tel.label} <img onmouseover="Dom.addClass('other_fax_tr','edit_over')" onmouseout="Dom.removeClass('other_fax_tr','edit_over')" id="quick_edit_other_fax{$key}" style="cursor:pointer;visibility:hidden" src="art/icons/edit.gif"></td>
						</tr>
						{/foreach} {if $employee->get('Customer Website')} 
						<tr id="website_tr" onmouseover="Dom.setStyle('quick_edit_website','visibility','visible')" onmouseout="Dom.setStyle('quick_edit_website','visibility','hidden')">
							<td id="website" colspan="2" class="aright">{$employee->get('Customer Website')}</td>
							<td><img alt="{t}Fax{/t}" title="{t}Website{/t}" src="art/icons/world.png" /></td>
							<td id="website_label{$employee->get('Customer Main FAX Key')}" style="color:#777;font-size:80%"><img onmouseover="Dom.addClass('website_tr','edit_over')" onmouseout="Dom.removeClass('website_tr','edit_over')" id="quick_edit_website" style="cursor:pointer;visibility:hidden" src="art/icons/edit.gif"></td>
						</tr>
						{/if} {foreach from=$show_case key=name item=value} {if $value!=''} 
						<tr>
							<td colspan="2" class="aright">{$value}</td>
							<td 
							<td colspan="2" class="aleft" style="color:#777;font-size:80%">{$name}</td>
						</tr>
						{/if} {/foreach} 
					</table>
					</td>
				</tr>
				<tbody>
					<tr style="font-size:90%;height:30px;vertical-align:bottom">
						<td style=";vertical-align:bottom">{t}Billing{/t}:</td>
						<td style=";vertical-align:bottom">{t}Delivery{/t}:</td>
					</tr>
					<tr style="font-size:90%;border-top:1px solid #ccc">
						<td> <span>{$employee->get('Customer Fiscal Name')}</span><br />
						<div id="billing_current_address">
							{if ($employee->get('Customer Billing Address Link')=='Contact') } <span style="font-weight:600">{t}Same as contact address{/t}</span> {else} {$employee->billing_address_xhtml()} {/if} 
						</div>
						</td>
						<td> 
						<div id="delivery_current_address">
							{if ($employee->get('Customer Delivery Address Link')=='Contact') or ( $employee->get('Customer Delivery Address Link')=='Billing' and ($employee->get('Customer Main Address Key')==$employee->get('Customer Billing Address Key')) ) } <span style="font-weight:600">{t}Same as contact address{/t}</span> {elseif $employee->get('Customer Delivery Address Link')=='Billing'} <span style="font-weight:600">{t}Same as billing address{/t}</span> {else} {$employee->delivery_address_xhtml()} {/if} 
						</div>
						</td>
					</tr>
				</tbody>
			</table>
		
	</div>
	
	
	
	
	<input type='hidden' id="staff_key" value="{$employee->id}" />
	<ul class="tabs" id="chooser_ul" style="clear:both;xmargin-top:25px">
		<li> <span class="item {if $block_view=='details'}selected{/if}" id="details"> <span> {t}Details{/t}</span></span></li>
		<li> <span class="item {if $block_view=='history'}selected{/if}" id="history"> <span> {t}History Notes{/t}</span></span></li>
		<li> <span class="item {if $block_view=='working_hours'}selected{/if}" id="working_hours"> <span> {t}Working Hours{/t}</span></span></li>
	</ul>
	<div style="clear:both;width:100%;border-bottom:1px solid #ccc">
	</div>
	<div id="block_details" class="data_table" style="{if $block_view!='details'}display:none;{/if}margin:20px 0 40px 0;padding:0 20px">
		<div style="width:350px">
			<table class="show_info_product" border="0" style="padding:0">
				{if $employee->get('Staff Name')}
				<tr>
					<td valign="top" class="aleft">{t}Name{/t}:</td>
					<td valign="top" colspan="2" class="aleft">{$employee->get('Staff Name')}</td>
				</tr>
				{/if} {if $employee->get('Staff Alias')}
				<tr>
					<td valign="top" class="aleft">{t}Code{/t}:</td>
					<td valign="top" colspan="2" class="aleft">{$employee->get('Staff Alias')}</td>
				</tr>
				{/if} {if $employee->get('Staff ID')}
				<tr>
					<td valign="top" class="aleft">{t}Staff ID{/t}:</td>
					<td colspan="2" class="aleft">{$employee->get('Staff ID')}</td>
				</tr>
				{/if} {if $employee->get('Staff Type')}
				<tr>
					<td valign="top" class="aleft">{t}Staff Type{/t}:</td>
					<td colspan="2" class="aleft">{$employee->get('Type')}</td>
				</tr>
				{/if} {if $employee->get('Staff Valid From')}
				<tr>
					<td valign="top" class="aleft">{t}Employed since{/t}:</td>
					<td colspan="2" class="aleft">{$employee->get('Staff Valid From')}</td>
				</tr>
				{/if} {if $employee->get('Staff Currently Working')!='Yes'}
				<tr>
					<td valign="top" class="aleft">{t}Employed Until{/t}:</td>
					<td colspan="2" class="aleft">{$employee->get('Staff Valid To')}</td>
				</tr>
				{/if} 
			</table>
		</div>
	</div>
	<div id="block_history" class="data_table" style="{if $block_view!='history'}display:none;{/if}margin:20px 0 40px 0;padding:0 20px;">
		<span class="clean_table_title">{t}History/Notes{/t}</span> {include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0 } 
		<div id="table0" class="data_table_container dtable btable">
		</div>
	</div>
	<div id="block_working_hours" class="data_table" style="{if $block_view!='working_hours'}display:none;{/if}margin:20px 0 40px 0;padding:0 20px">
		<span class="clean_table_title">{t}Working Hours Details{/t}</span> {include file='table_splinter.tpl' table_id=1 filter_name=$filter_name1 filter_value=$filter_value1 } 
		<div id="table1" class="data_table_container dtable btable">
		</div>
	</div>
</div>
{include file='footer.tpl'} 
	<div style="padding:0px 20px;">

		jinnicha
	
		<div style="width:520px;float:left;padding-top:0px">
		
				<table id="customer_data" border="0" style="width:100%;border-collapse: collapse;">
					<tr>
						<td > 
							<div style="border:0px solid red;float:left;margin-right:20px;position:relative">
								{if $customer->get_image_src()} <img id="avatar" src="{$customer->get_image_src()}" style="cursor:pointer;border:1px solid #eee;height:45px;max-width:100px"> {else} <img id="avatar" src="/art/avatar.jpg" style="cursor:pointer;"> {/if} {if $customer->get('Customer Level Type')=='VIP'}<img src="/art/icons/shield.png" style="position:absolute;xtop:-36px;left:40px">{/if} {if $customer->get('Customer Level Type')=='Partner'}<img src="/art/icons/group.png" style="position:absolute;xtop:-36px;left:40px">{/if} 
							</div>
							
						</td>
						<td> 
						<table>
							<tr id="main_contact_name_tr" onmouseover="Dom.setStyle('quick_edit_main_contact_name_edit','visibility','visible')" onmouseout="Dom.setStyle('quick_edit_main_contact_name_edit','visibility','hidden')">
																		<td></td>

									<td id="customer_name" colspan="2" class="aright id">{$customer->get('Customer Name')}</td>
									<td><img onmouseover="Dom.addClass('main_contact_name_tr','edit_over')" onmouseout="Dom.removeClass('main_contact_name_tr','edit_over')" id="quick_edit_main_contact_name_edit" style="cursor:pointer;visibility:hidden" src="/art/icons/edit.gif"></td>
								</tr>
							
							{if $customer->get('Customer Main Contact Key')} 
								<tr id="main_contact_name_tr" onmouseover="Dom.setStyle('quick_edit_main_contact_name_edit','visibility','visible')" onmouseout="Dom.setStyle('quick_edit_main_contact_name_edit','visibility','hidden')">
																		<td><i class="fa fa-smile-o" alt="" title=""  /></i></td>

									<td id="main_contact_name" colspan="2" class="aright">{$customer->get('Customer Main Contact Name')}</td>
									<td><img onmouseover="Dom.addClass('main_contact_name_tr','edit_over')" onmouseout="Dom.removeClass('main_contact_name_tr','edit_over')" id="quick_edit_main_contact_name_edit" style="cursor:pointer;visibility:hidden" src="/art/icons/edit.gif"></td>
								</tr>
								{/if} 
						</table>
						
						</td>
					</tr>
					<tr>
						{if $customer->get('Customer Main Address Key')} 
						<td id="main_address_td" style="border:1px dotted #fff" onmouseover="Dom.setStyle('quick_edit_main_address','visibility','visible')" onmouseout="Dom.setStyle('quick_edit_main_address','visibility','hidden')"> <img onmouseover="Dom.addClass('main_address_td','edit_over')" onmouseout="Dom.removeClass('main_address_td','edit_over')" id="quick_edit_main_address" style="float:right;cursor:pointer;visibility:hidden" src="/art/icons/edit.gif"> 
							<div id="main_address">
								{$customer->get('Customer Main XHTML Address')} 
							</div>
							<div style="margin-top:3px" class="buttons small left">
								<button onclick="window.open('customers_address_label.pdf.php?type=customer&id={$customer->id}&label=99012')"><img style="height:12px" src="/art/icons/printer.png" alt=""> {t}Label{/t}</button> 
							</div>
						</td>
						{/if} 
						<td valign="top"> 
							<table class="customer_show_data" border=0>
							
							
							
								{if $customer->get('Customer Registration Number')} 
								<tr id="registration_number_tr" onmouseover="Dom.setStyle('quick_edit_registration_number','visibility','visible')" onmouseout="Dom.setStyle('quick_edit_registration_number','visibility','hidden')">
									<td id="registration_number" colspan="2" class="aright">{$customer->get('Customer Registration Number')}</td>
									<td><img alt="{t}Registration Number{/t}" title="{t}Registration Number{/t}" src="/art/icons/certificate.png" /></td>
									<td><i class="fa fa-pencil" onmouseover="Dom.addClass('registration_number_tr','edit_over')" onmouseout="Dom.removeClass('registration_number_tr','edit_over')" id="quick_edit_registration_number" style="cursor:pointer;visibility:hidden" src="/art/icons/edit.gif"></i></td>
								</tr>
								{/if} {if $customer->get('Customer Tax Number')} 
								<tr id="tax_tr" onmouseover="Dom.setStyle('quick_edit_tax','visibility','visible')" onmouseout="Dom.setStyle('quick_edit_tax','visibility','hidden')">
									
									
									
									
									<td id="tax" colspan="2" class="aright">{$customer->get('Customer Tax Number')}</td>
									<td>
										<img id="check_tax_number" onclick="show_dialog_check_tax_number(Dom.get('tax').innerHTML)" alt="{t}Tax Number{/t}" title="{t}Tax Number{/t}; {$customer->get('Tax Number Valid')}" style="width:16px;cursor:pointer" 
										src="{if $customer->get('Customer Tax Number Valid')=='No' or $customer->get('Customer Tax Number Valid')=='Unknown'}art/icons/taxation_error.png
										{elseif $customer->get('Customer Tax Number Valid')=='Yes' and $customer->get('Customer Tax Number Details Match')=='No' }art/icons/taxation_yellow.png
										{elseif $customer->get('Customer Tax Number Valid')=='Yes'}art/icons/taxation_green.png{else}art/icons/taxation.png{/if}" /> </td>
										<td> <img onmouseover="Dom.addClass('tax_tr','edit_over')" onmouseout="Dom.removeClass('tax_tr','edit_over')" id="quick_edit_tax" style="cursor:pointer;visibility:hidden" src="/art/icons/edit.gif"></td>
									</tr>
									{/if} {if $customer->get('Customer Main Email Key')!=''} 
									<tr id="main_email_tr" onmouseover="Dom.setStyle('quick_edit_email','visibility','visible')" onmouseout="Dom.setStyle('quick_edit_email','visibility','hidden')">
									    {assign var="main_email_user_key" value=$customer->get_main_email_user_key()}
										<td><i {if $main_email_user_key} onclick="change_view('users/website/user/{$main_email_user_key}')"{/if} class="fa fa-envelope{if !$main_email_user_key}-o{/if}"></i></td>

										<td id="main_email" colspan="2" class="aright">
										
										{$customer->get('Customer Main XHTML email')}</td>
										<td id="email_label{$customer->get('Customer Main Email Key')}" style="color:#777;font-size:80%">
										{$customer->get_principal_email_comment()} <img onmouseover="Dom.addClass('main_email_tr','edit_over')" onmouseout="Dom.removeClass('main_email_tr','edit_over')" id="quick_edit_email" style="cursor:pointer;visibility:hidden" src="/art/icons/edit.gif"></td>
									
									
									</tr>
									{/if} {foreach from=$customer->get_other_emails_data() item=other_email key=key} 
									<tr id="other_email_tr" onmouseover="Dom.setStyle('quick_edit_other_email{$key}','visibility','visible')" onmouseout="Dom.setStyle('quick_edit_other_email{$key}','visibility','hidden')">
										<td id="email{$key}" colspan="2" class="aright">{if $other_email.user_key}<a href="site_user.php?id={$other_email.user_key}"><img src="/art/icons/world.png" style="width:12px" title="{t}Register User{/t}" alt="{t}Register User{/t}"></a>{/if} {$other_email.xhtml}</td>
										<td><img alt="{t}Email{/t}" title="{t}Email{/t}" src="/art/icons/email.png" /></td>
										<td id="email_label{$key}" style="color:#777;font-size:80%">{$other_email.label} <img onmouseover="Dom.addClass('other_email_tr','edit_over')" onmouseout="Dom.removeClass('other_email_tr','edit_over')" id="quick_edit_other_email{$key}" style="cursor:pointer;visibility:hidden" src="/art/icons/edit.gif"></td>
									</tr>
									{/foreach} {if $customer->get('Customer Main Telephone Key')} 
									<tr id="main_telephone_tr" onmouseover="Dom.setStyle('quick_edit_main_telephone','visibility','visible')" onmouseout="Dom.setStyle('quick_edit_main_telephone','visibility','hidden')">
																			<td><i class="fa fa-phone"  alt="{t}Main Telephone{/t}" title="{t}Main Telephone{/t}" src="/art/icons/telephone.png"></i></td>
	<td id="main_telephone" colspan="2" class="aright" style="{if $customer->get('Customer Main XHTML Mobile') and $customer->get('Customer Preferred Contact Number')=='Telephone'}font-weight:800{/if}">{$customer->get('Customer Main XHTML Telephone')}</td>
										<td id="telephone_label{$customer->get('Customer Main Telephone Key')}" style="color:#777;font-size:80%">{$customer->get_principal_telecom_comment('Telephone')} <img onmouseover="Dom.addClass('main_telephone_tr','edit_over')" onmouseout="Dom.removeClass('main_telephone_tr','edit_over')" id="quick_edit_main_telephone" style="cursor:pointer;visibility:hidden" src="/art/icons/edit.gif"></td>
									</tr>
									{/if} {foreach from=$customer->get_other_telephones_data() item=other_tel key=key} 
									<tr id="other_telephone_tr" onmouseover="Dom.setStyle('quick_edit_other_telephone{$key}','visibility','visible')" onmouseout="Dom.setStyle('quick_edit_other_telephone{$key}','visibility','hidden')">
										<td id="telephone{$key}" colspan="2" class="aright">{$other_tel.xhtml}</td>
										<td><img alt="{t}Telephone{/t}" title="{t}Telephone{/t}" src="/art/icons/telephone.png" /></td>
										<td id="telephone_label{$key}" style="color:#777;font-size:80%">{$other_tel.label} <img onmouseover="Dom.addClass('other_telephone_tr','edit_over')" onmouseout="Dom.removeClass('other_telephone_tr','edit_over')" id="quick_edit_other_telephone{$key}" style="cursor:pointer;visibility:hidden" src="/art/icons/edit.gif"></td>
									</tr>
									{/foreach} {if $customer->get('Customer Main Mobile Key')} 
									<tr id="main_mobile_tr" onmouseover="Dom.setStyle('quick_edit_main_mobile','visibility','visible')" onmouseout="Dom.setStyle('quick_edit_main_mobile','visibility','hidden')">
										<td id="main_mobile" colspan="2" class="aright" style="{if $customer->get('Customer Main XHTML Telephone') and $customer->get('Customer Preferred Contact Number')=='Mobile'}font-weight:800{/if}">{$customer->get('Customer Main XHTML Mobile')}</td>
										<td><img alt="{t}Mobile{/t}" title="{t}Mobile{/t}" src="/art/icons/phone.png" /></td>
										<td id="mobile_label{$customer->get('Customer Main Mobile Key')}" style="color:#777;font-size:80%">{$customer->get_principal_telecom_comment('Mobile')} <img onmouseover="Dom.addClass('main_mobile_tr','edit_over')" onmouseout="Dom.removeClass('main_mobile_tr','edit_over')" id="quick_edit_main_mobile" style="cursor:pointer;visibility:hidden" src="/art/icons/edit.gif"></td>
									</tr>
									{/if} {foreach from=$customer->get_other_mobiles_data() item=other_tel key=key} 
									<tr id="other_mobile_tr" onmouseover="Dom.setStyle('quick_edit_other_mobile{$key}','visibility','visible')" onmouseout="Dom.setStyle('quick_edit_other_mobile{$key}','visibility','hidden')">
										<td id="mobile{$key}" colspan="2" class="aright">{$other_tel.xhtml}</td>
										<td><img alt="{t}Mobile{/t}" title="{t}Mobile{/t}" src="/art/icons/phone.png" /></td>
										<td id="mobile_label{$key}" style="color:#777;font-size:80%">{$other_tel.label} <img onmouseover="Dom.addClass('other_mobile_tr','edit_over')" onmouseout="Dom.removeClass('other_mobile_tr','edit_over')" id="quick_edit_other_mobile{$key}" style="cursor:pointer;visibility:hidden" src="/art/icons/edit.gif"></td>
									</tr>
									{/foreach} {if $customer->get('Customer Main FAX Key')} 
									<tr id="main_fax_tr" onmouseover="Dom.setStyle('quick_edit_main_fax','visibility','visible')" onmouseout="Dom.setStyle('quick_edit_main_fax','visibility','hidden')">
										<td id="main_fax" colspan="2" class="aright">{$customer->get('Customer Main XHTML FAX')}</td>
										<td><img alt="{t}Fax{/t}" title="{t}Fax{/t}" src="/art/icons/printer.png" /></td>
										<td id="fax_label{$customer->get('Customer Main FAX Key')}" style="color:#777;font-size:80%">{$customer->get_principal_telecom_comment('FAX')} <img onmouseover="Dom.addClass('main_fax_tr','edit_over')" onmouseout="Dom.removeClass('main_fax_tr','edit_over')" id="quick_edit_main_fax" style="cursor:pointer;visibility:hidden" src="/art/icons/edit.gif"></td>
									</tr>
									{/if} {foreach from=$customer->get_other_faxes_data() item=other_tel key=key} 
									<tr id="other_fax_tr" onmouseover="Dom.setStyle('quick_edit_other_fax{$key}','visibility','visible')" onmouseout="Dom.setStyle('quick_edit_other_fax{$key}','visibility','hidden')">
										<td id="fax{$key}" colspan="2" class="aright">{$other_tel.xhtml}</td>
										<td><img alt="{t}Fax{/t}" title="{t}Fax{/t}" src="/art/icons/printer.png" /></td>
										<td id="fax_label{$key}" style="color:#777;font-size:80%">{$other_tel.label} <img onmouseover="Dom.addClass('other_fax_tr','edit_over')" onmouseout="Dom.removeClass('other_fax_tr','edit_over')" id="quick_edit_other_fax{$key}" style="cursor:pointer;visibility:hidden" src="/art/icons/edit.gif"></td>
									</tr>
									{/foreach} {if $customer->get('Customer Website')} 
									<tr id="website_tr" onmouseover="Dom.setStyle('quick_edit_website','visibility','visible')" onmouseout="Dom.setStyle('quick_edit_website','visibility','hidden')">
										<td id="website" colspan="2" class="aright">{$customer->get('Customer Website')}</td>
										<td><img alt="{t}Fax{/t}" title="{t}Website{/t}" src="/art/icons/world.png" /></td>
										<td id="website_label{$customer->get('Customer Main FAX Key')}" style="color:#777;font-size:80%"><img onmouseover="Dom.addClass('website_tr','edit_over')" onmouseout="Dom.removeClass('website_tr','edit_over')" id="quick_edit_website" style="cursor:pointer;visibility:hidden" src="/art/icons/edit.gif"></td>
									</tr>
									{/if} {foreach from=$customer->get_custmon_fields() key=name item=value} {if $value!=''} 
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
								<td> <span>{$customer->get('Customer Fiscal Name')}</span><br />
									<div id="billing_current_address">
										{if ($customer->get('Customer Billing Address Link')=='Contact') } <span style="font-weight:600">{t}Same as contact address{/t}</span> {else} {$customer->billing_address_xhtml()} {/if} 
									</div>
								</td>
								<td> 
									<div id="delivery_current_address">
										{if ($customer->get('Customer Delivery Address Link')=='Contact') or ( $customer->get('Customer Delivery Address Link')=='Billing' and ($customer->get('Customer Main Address Key')==$customer->get('Customer Billing Address Key')) ) } <span style="font-weight:600">{t}Same as contact address{/t}</span> {elseif $customer->get('Customer Delivery Address Link')=='Billing'} <span style="font-weight:600">{t}Same as billing address{/t}</span> {else} {$customer->delivery_address_xhtml()} {/if} 
									</div>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
		<div style="margin-top:3px;width:370px;float:left">
					<div id="sticky_note_div" class="sticky_note" style="{if $customer->get('Sticky Note')==''}display:none{/if}">
						<i class="fa fa-pencil" id="sticky_note_bis" style="float:right;cursor:pointer" ></i> 
						<div id="sticky_note_content" style="padding:10px 15px 10px 15px;">
							{$customer->get('Sticky Note')} 
						</div>
					</div>
					<div style="clear:both">
					</div>
					<div id="overviews" style="padding:20px;font-size:90%">

						<div id="customer_overview" style="float:left;margin-bottom:10px;">
							<h2 style="font-size:100%;padding:0;font-weight:800">
								{t}Current Balance{/t} 
							</h2>
							<table border=0 style="padding:0 5px;margin:0;border-top:1px solid #ccc;;border-bottom:1px solid #ddd;min-width:350px">
								<tr id="account_balance_tr">
									<td id="account_balance_label">{t}Account Balance{/t}</td>
									<td id="account_balance" class="aright" style="padding-right:20px;font-weight:800"><img id="edit_account_balance_button" src="/art/icons/add_bw.png" style="visibility:hidden;cursor:pointer"> {$customer->get('Account Balance')} </td>
								</tr>
								
								<tr id="last_credit_note_tr" style="display:none">
									
									<td colspan=2 class="aright"  style="padding-right:20px">{t}Credit note{/t}: <span id="account_balance_last_credit_note"></span></td>
								</tr>

								<tr style="{if $customer->get_pending_payment_amount_from_account_balance()==0}display:none{/if}" >
									<td id="account_balance_label"  >{t}Payments in Process{/t}</td>
									<td id="account_balance" class="aright" style="padding-right:20px;font-weight:800"> {$customer->get_formated_pending_payment_amount_from_account_balance()} </td>
								</tr>


							</table>
						</div>

						<div id="orders_overview" style="float:left;;margin-right:40px;width:300px">
							<h2 style="font-size:100%;padding:0;font-weight:800">
								{t}Customer Overview{/t} 
							</h2>
							<table border="0" style="padding:0;margin:0;border-top:1px solid #ccc;;border-bottom:1px solid #ddd;min-width:350px">
								{if $customer->get('Customer Level Type')=='VIP'} 
								<td></td>
								<td class="id" style="font-weight:800">{t}VIP Customer{/t}</td>
								{/if} {if $customer->get('Customer Level Type')=='Partner'} 
								<td></td>
								<td class="id" style="font-weight:800">{t}Partner Customer{/t}</td>
								{/if} {if $customer->get('Customer Type by Activity')=='Losing'} 
								<tr>
									<td colspan="2">{t}Losing Customer{/t}</td>
								</tr>
								{elseif $customer->get('Customer Type by Activity')=='Lost'} 
								<tr>
									<td>{t}Lost Customer{/t}</td>
									<td>{$customer->get('Lost Date')}</td>
								</tr>
								{/if} 
								<tr>
									<td>{t}Contact Since{/t}:</td>
									<td>{$customer->get('First Contacted Date')}</td>
								</tr>
								{assign var="correlation_msg" value=$customer->get_correlation_info()}
								
								{if $correlation_msg} 
								<tr>
									<td>{$correlation_msg}</td>
								</tr>
								{/if}
								
								 {if $customer->get('Customer Send Newsletter')=='No' or $customer->get('Customer Send Email Marketing')=='No' or $customer->get('Customer Send Postal Marketing')=='No'} 
								<tr>
									<td> 
										<div>
											{if $customer->get('Customer Send Newsletter')=='No'}<img alt="{t}Attention{/t}" width='14' src="/art/icons/exclamation.png" /> <span>{t}Don't send newsletters{/t}</span><br />
											{/if} {if $customer->get('Customer Send Email Marketing')=='No'}<img alt="{t}Attention{/t}" width='14' src="/art/icons/exclamation.png" /> <span>{t}Don't send marketing by email{/t}</span><br />
											{/if} {if $customer->get('Customer Send Postal Marketing')=='No'}<img alt="{t}Attention{/t}" width='14' src="/art/icons/exclamation.png" /> <span>{t}Don't send marketing by post{/t}</span><br />
											{/if} 
										</div>
									</td>
								</tr>
								{/if} {foreach from=$customer->get_category_data() item=item key=key} 
								<tr>
									<td>{$item.root_label}:</td>
									<td>{$item.value}</td>
								</tr>
								{/foreach} 
							</table>
						</div>
						{if $customer->get('Customer Orders')>0} 
						<div id="customer_overview" style="float:left;margin-top:10px;">
							<h2 style="font-size:100%;padding:0;font-weight:800">
								{t}Orders Overview{/t} 
							</h2>
							<table style="padding:0 5px;margin:0;border-top:1px solid #ccc;;border-bottom:1px solid #ddd;min-width:350px">
								{if $customer->get('Customer Type by Activity')=='Lost'}
								<tr>
									<td><span style="color:white;background:black;padding:1px 10px">{t}Lost Customer{/t}</span></td>
								</tr>
								{/if} {if $customer->get('Customer Type by Activity')=='Losing'}
								<tr>
									<td><span style="color:white;background:black;padding:1px 10px">{t}Warning!, loosing customer{/t}</span></td>
								</tr>
								{/if} 
								<tr>
									<td> {if $customer->get('Customer Orders')==1} {$customer->get('Customer Name')} {t}has place one order{/t}. {elseif $customer->get('Customer Orders')>1 } {$customer->get('customer name')} {if $customer->get('Customer Type by Activity')=='Lost'}{t}placed{/t}{else}{t}has placed{/t}{/if} <b>{$customer->get('Customer Orders')}</b> {if $customer->get('Customer Type by Activity')=='Lost'}{t}orders{/t}{else}{t}orders so far{/t}{/if}, {t}which amounts to a total of{/t} <b>{$customer->get('Net Balance')}</b> {t}plus tax{/t} ({t}an average of{/t} {$customer->get('Total Net Per Order')} {t}per order{/t}). {if $customer->get('Customer Orders Invoiced')}<br />
										{if $customer->get('Customer Type by Activity')=='Lost'}{t}This customer used to place an order every{/t}{else}{t}This customer usually places an order every{/t}{/if} {$customer->get('Order Interval')}.{/if} {else} Customer has not place any order yet. {/if} </td>
									</tr>
								</table>
							</div>
							{/if} 
						</div>
					</div>
				
		<div style="clear:both"></div>		
				
	</div>			
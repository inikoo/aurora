<div class="subject_profile">
	<div id="contact_data">
		<div class="data_container">
			<div class="data_field  {if $supplier->get('Supplier Type')!='Company'}hide{/if}">
				<i title="{t}Company name{/t}" class="fa fa-building-o"></i> <span class="Supplier_Name">{$supplier->get('Supplier Name')}</span> 
			</div>
			<div class="data_field">
				<i title="{t}Contact name{/t}" class="fa fa-user"></i> <span class="Supplier_Main_Contact_Name">{$supplier->get('Supplier Main Contact Name')}</span> 
			</div>
			
		</div>
		<div class="data_container">
			<div class="data_field   {if !$supplier->get('Supplier Main Plain Email')}hide{/if}">
				<i class="fa fa-fw fa-at"></i> <span id="Supplier_Main_Plain_Email" class="Supplier_Main_Plain_Email">{if $supplier->get('Supplier Main Plain Email')}{mailto address=$supplier->get('Main Plain Email')}{/if}</span> 
			</div>
			<span id="display_telephones"></span> {if $supplier->get('Supplier Preferred Contact Number')=='Mobile'} 
			<div id="Supplier_Main_Plain_Mobile_display" class="data_field {if !$supplier->get('Supplier Main Plain Mobile')}hide{/if}">
				<i class="fa fa-fw fa-mobile"></i> <span class="Supplier_Main_Plain_Mobile">{$supplier->get('Main Plain Mobile')}</span> 
			</div>
			<div id="Supplier_Main_Plain_Telephone_display" class="data_field {if !$supplier->get('Supplier Main Plain Telephone')}hide{/if}">
				<i class="fa fa-fw fa-phone"></i> <span class="Supplier_Main_Plain_Telephone">{$supplier->get('Main Plain Telephone')}</span> 
			</div>
			{else} 
			<div id="Supplier_Main_Plain_Telephone_display" class="data_field {if !$supplier->get('Supplier Main Plain Telephone')}hide{/if}">
				<i title="Telephone" class="fa fa-fw fa-phone"></i> <span class="Supplier_Main_Plain_Telephone">{$supplier->get('Main Plain Telephone')}</span> 
			</div>
			<div id="Supplier_Main_Plain_Mobile_display" class="data_field {if !$supplier->get('Supplier Main Plain Mobile')}hide{/if}">
				<i title="Mobile" class="fa fa-fw fa-mobile"></i> <span class="Supplier_Main_Plain_Mobile">{$supplier->get('Main Plain Mobile')}</span> 
			</div>
			{/if} 
			<div id="Supplier_Main_Plain_FAX_display" class="data_field {if !$supplier->get('Supplier Main Plain FAX')}hide{/if}">
				<i title="Fax" class="fa fa-fw fa-fax"></i> <span>{$supplier->get('Main Plain FAX')}</span> 
			</div>
		</div>
		<div style="clear:both">
		</div>
		<div class="data_container">
			<div style="min-height:80px;float:left;width:28px">
				<i class="fa fa-map-marker"></i> 
			</div>
			<div style="float:left;min-width:272px">
				{$supplier->get('Contact Address')} 
			</div>
		</div>
		<div class="data_container {if $supplier->get('Sticky Note')==''}hide{/if} ">
			<div class="sticky_note_button">
				<i class="fa fa-sticky-note"></i> 
			</div>
			<div class="sticky_note">
				{$supplier->get('Sticky Note')} 
			</div>
		</div>
		<div style="clear:both">
		</div>
	</div>
	<div id="info">
		<div id="overviews">
			<table border="0" class="overview" style="">
				<tr id="account_balance_tr" class="main">
					<td id="account_balance_label">{t}Account Balance{/t}</td>
					<td id="account_balance" class="aright highlight">{$supplier->get('Account Balance')} </td>
				</tr>
				<tr id="last_credit_note_tr" style="display:none">
					<td colspan="2" class="aright" style="padding-right:20px">{t}Credit note{/t}: <span id="account_balance_last_credit_note"></span></td>
				</tr>
				
			</table>
			<table border="0" class="overview">
				{if $supplier->get('Supplier Level Type')=='VIP'} 
				<td></td>
				<td class="highlight">{t}VIP Supplier{/t}</td>
				{/if} {if $supplier->get('Supplier Level Type')=='Partner'} 
				<td></td>
				<td class="highlight">{t}Partner Supplier{/t}</td>
				{/if} {if $supplier->get('Supplier Type by Activity')=='Losing'} 
				<tr>
					<td colspan="2">{t}Losing Supplier{/t}</td>
				</tr>
				{elseif $supplier->get('Supplier Type by Activity')=='Lost'} 
				<tr>
					<td>{t}Lost Supplier{/t}</td>
					<td>{$supplier->get('Lost Date')}</td>
				</tr>
				{/if} 
				<tr>
					<td>{t}Contact Since{/t}:</td>
					<td>{$supplier->get('First Contacted Date')}</td>
				</tr>
				
				{foreach from=$supplier->get_category_data() item=item key=key} 
				<tr>
					<td>{$item.root_label}:</td>
					<td>{$item.value}</td>
				</tr>
				{/foreach} 
			</table>
			{if $supplier->get('Supplier Send Newsletter')=='No' or $supplier->get('Supplier Send Email Marketing')=='No' or $supplier->get('Supplier Send Postal Marketing')=='No'} 
			<table border="0" class="overview compact">
				<tr class="{if $supplier->get('Supplier Send Newsletter')=='Yes'}hide{/if}">
					<td colspan="2"> <i class="fa fa-ban"></i> <span>{t}Don't send newsletters{/t}</span> </td>
				</tr>
				<tr class="{if $supplier->get('Supplier Send Email Marketing')=='Yes'}hide{/if}">
					<td colspan="2"> <i class="fa fa-ban"></i> <span>{t}Don't send marketing by email{/t}</span> </td>
				</tr>
				<tr class="{if $supplier->get('Supplier Send Postal Marketing')=='Yes'}hide{/if}">
					<td colspan="2"> <i class="fa fa-ban"></i> <span>{t}Don't send marketing by post{/t}</span> </td>
				</tr>
			</table>
			{/if} {if $supplier->get('Supplier Orders')>0} 
			<table class="overview">
				{if $supplier->get('Supplier Type by Activity')=='Lost'} 
				<tr>
					<td><span style="color:white;background:black;padding:1px 10px">{t}Lost Supplier{/t}</span></td>
				</tr>
				{/if} {if $supplier->get('Supplier Type by Activity')=='Losing'} 
				<tr>
					<td><span style="color:white;background:black;padding:1px 10px">{t}Warning!, loosing supplier{/t}</span></td>
				</tr>
				{/if} 
				<tr>
					<td class="text"> {if $supplier->get('Supplier Orders')==1} 
					<p>
						{$supplier->get('Name')} {t}has place one order{/t}.
					</p>
					{elseif $supplier->get('Supplier Orders')>1 } {$supplier->get('Name')} {if $supplier->get('Supplier Type by Activity')=='Lost'}{t}placed{/t}{else}{t}has placed{/t}{/if} <b>{$supplier->get('Supplier Orders')}</b> {if $supplier->get('Supplier Type by Activity')=='Lost'}{t}orders{/t}{else}{t}orders so far{/t}{/if}, {t}which amounts to a total of{/t} <b>{$supplier->get('Net Balance')}</b> {t}plus tax{/t} ({t}an average of{/t} {$supplier->get('Total Net Per Order')} {t}per order{/t}). {if $supplier->get('Supplier Orders Invoiced')}
					</p>
					<p>
						{if $supplier->get('Supplier Type by Activity')=='Lost'}{t}This supplier used to place an order every{/t}{else}{t}This supplier usually places an order every{/t}{/if} {$supplier->get('Order Interval')}.{/if} {else} Supplier has not place any order yet. {/if}
					</p>
					</td>
				</tr>
			</table>
			{/if} 
		</div>
	</div>
	<div style="clear:both">
	</div>
</div>
<script>
function email_width_hack() {
    var email_length = $('#Supplier_Main_Plain_Email').text().length

    if (email_length > 30) {
        $('#Supplier_Main_Plain_Email').css("font-size", "90%");
    }
}

email_width_hack();

</script>
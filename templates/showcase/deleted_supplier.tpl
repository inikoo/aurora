<div class="subject_profile">
	<div id="contact_data">
		<div class="data_container">
			<div class="data_field">
				<i title="{t}Company name{/t}" class="fa fa-building-o"></i> <span class="Supplier_Name">{$supplier->get('Deleted Name')}</span> 
			</div>
			<div class="data_field {if $supplier->get('Supplier Main Contact Name')==''}hide{/if}">
				<i title="{t}Contact name{/t}" class="fa fa-user"></i> <span class="Supplier_Main_Contact_Name">{$supplier->get('Supplier Main Contact Name')}</span> 
			</div>
			<div class="data_container" style=";margin-top:10px">
			<div style="min-height:80px;float:left;width:28px;">
				<i class="fa fa-map-marker"></i> 
			</div>
			<div style="float:left;width:272px" class="Supplier_Contact_Address">
				{$supplier->get('Contact Address')} 
			</div>
		</div>
		</div>
		<div class="data_container">
			<div id="Supplier_Main_Plain_Email_display" class="data_field   {if !$supplier->get('Supplier Main Plain Email')}hide{/if}">
				<i class="fa fa-fw fa-at"></i> <span id="Supplier_Other_Email_mailto">{if $supplier->get('Supplier Main Plain Email')}{mailto address=$supplier->get('Main Plain Email')}{/if}</span> 
			</div>
			{foreach $supplier->get_other_emails_data() key=other_email_key item=other_email}
			<div id="Supplier_Other_Email_{$other_email_key}_display" class="data_field ">
				<i  class="fa fa-fw fa-at discreet"></i> <span id="Supplier_Other_Email_{$other_email_key}_mailto">{mailto address=$other_email.email}</span> 
			</div>
			{/foreach}
			<div id="Supplier_Other_Email_display" class="data_field hide">
				<i  class="fa fa-fw fa-at discreet"></i> <span class="Supplier_Other_Email_mailto"></span> 
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
			
			{foreach $supplier->get_other_telephones_data() key=other_telephone_key item=other_telephone}
			<div id="Supplier_Other_Telephone_{$other_telephone_key}_display" class="data_field ">
				<i  class="fa fa-fw fa-phone discreet"></i> <span>{$other_telephone.formatted_telephone}</span> 
			</div>
			{/foreach}
			<div id="Supplier_Other_Telephone_display" class="data_field hide">
				<i  class="fa fa-fw fa-phone discreet"></i> <span></span> 
			</div>
			
		</div>
		<div style="clear:both">
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
	<div id="info" >
		<div id="overviews">
			
			<table border="0" class="overview">
				
				<tr>
					<td>{t}Contact since{/t}:</td>
					<td>{$supplier->get('Valid From')}</td>
				</tr>
				<tr>
					<td>{t}Products origin{/t}:</td>
					<td>{$supplier->get('Products Origin Country Code')}</td>
				</tr>
				<tr>
					<td>{t}Delivery time{/t}:</td>
					<td>{$supplier->get('Delivery Time')}</td>
				</tr>
				
				{foreach from=$supplier->get_category_data() item=item key=key} 
				<tr>
					<td>{$item.root_label}:</td>
					<td>{$item.value}</td>
				</tr>
				{/foreach} 
			</table>
			 {if $supplier->get('Supplier Orders')>0} 
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
    var email_length = $('#showcase_Supplier_Main_Plain_Email').text().length

    if (email_length > 30) {
        $('#showcase_Supplier_Main_Plain_Email').css("font-size", "90%");
    }
}

email_width_hack();

</script>
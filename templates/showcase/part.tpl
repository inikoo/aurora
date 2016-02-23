
<div class="asset_profile" >
<div id="main_categories_position" >
<div class="discret">
{if $family_data.id}{t}Family{/t} <span onClick="change_view('products/{$part->get('Store Key')}/category/{$family_data.id}')" class="id link">{$family_data.code}</span>  {/if}

</div>
</div>

	<div id="asset_data">
		<div class="data_container">
			
			<div class="data_field" >
				<h1 ><span class="Part_Unit_Description">{$part->get('Part Unit Description')}</span> <span class="Store_Product_Price">{$part->get('Price')}</span></h1>
			</div>
			
		</div>
		<div class="data_container">
			
			
		</div>
		<div style="clear:both">
		</div>
		<div class="data_container">
			<div style="min-height:80px;float:left;width:28px">
				<i class="fa fa-camera-retro"></i> 
			</div>
			<div class="wraptocenter main_image" >
				{if $main_image!=''}
				<img src="/{$main_image.small_url}"  >
				
				</span>
				{/if}
			</div>
		</div>
		{include file='sticky_note.tpl' object='Category'  key=$part->id sticky_note_field='Store_Product_Sticky_Note' _object=$part}

	
		
		
		<div style="clear:both">
		</div>
	</div>
	<div id="info" style="position:relative;top:-10px">
		<div id="overviews">
			<table border="0" class="overview" style="">
				<tr id="account_balance_tr" class="main">
					<td id="account_balance_label">{t}Sales{/t}</td>
					<td id="account_balance" class="aright highlight">{$part->get('Account Balance')} </td>
				</tr>
				<tr id="last_credit_note_tr" style="display:none">
					<td colspan="2" class="aright" style="padding-right:20px">{t}Credit note{/t}: <span id="account_balance_last_credit_note"></span></td>
				</tr>
				
			</table>
			<table border="0" class="overview">
				{if $part->get('Customer Level Type')=='VIP'} 
				<td></td>
				<td class="highlight">{t}VIP Customer{/t}</td>
				{/if} {if $part->get('Customer Level Type')=='Partner'} 
				<td></td>
				<td class="highlight">{t}Partner Customer{/t}</td>
				{/if} {if $part->get('Customer Type by Activity')=='Losing'} 
				<tr>
					<td colspan="2">{t}Losing Customer{/t}</td>
				</tr>
				{elseif $part->get('Customer Type by Activity')=='Lost'} 
				<tr>
					<td>{t}Lost Customer{/t}</td>
					<td>{$part->get('Lost Date')}</td>
				</tr>
				{/if} 
				<tr>
					<td>{t}Contact Since{/t}:</td>
					<td>{$part->get('First Contacted Date')}</td>
				</tr>
				
			</table>
			{if $part->get('Customer Send Newsletter')=='No' or $part->get('Customer Send Email Marketing')=='No' or $part->get('Customer Send Postal Marketing')=='No'} 
			<table border="0" class="overview compact">
				<tr class="{if $part->get('Customer Send Newsletter')=='Yes'}hide{/if}">
					<td colspan="2"> <i class="fa fa-ban"></i> <span>{t}Don't send newsletters{/t}</span> </td>
				</tr>
				<tr class="{if $part->get('Customer Send Email Marketing')=='Yes'}hide{/if}">
					<td colspan="2"> <i class="fa fa-ban"></i> <span>{t}Don't send marketing by email{/t}</span> </td>
				</tr>
				<tr class="{if $part->get('Customer Send Postal Marketing')=='Yes'}hide{/if}">
					<td colspan="2"> <i class="fa fa-ban"></i> <span>{t}Don't send marketing by post{/t}</span> </td>
				</tr>
			</table>
			{/if} {if $part->get('Customer Orders')>0} 
			<table class="overview">
				{if $part->get('Customer Type by Activity')=='Lost'} 
				<tr>
					<td><span style="color:white;background:black;padding:1px 10px">{t}Lost Customer{/t}</span></td>
				</tr>
				{/if} {if $part->get('Customer Type by Activity')=='Losing'} 
				<tr>
					<td><span style="color:white;background:black;padding:1px 10px">{t}Warning!, loosing product{/t}</span></td>
				</tr>
				{/if} 
				<tr>
					<td class="text"> {if $part->get('Customer Orders')==1} 
					<p>
						{$part->get('Name')} {t}has place one order{/t}.
					</p>
					{elseif $part->get('Customer Orders')>1 } {$part->get('Name')} {if $part->get('Customer Type by Activity')=='Lost'}{t}placed{/t}{else}{t}has placed{/t}{/if} <b>{$part->get('Customer Orders')}</b> {if $part->get('Customer Type by Activity')=='Lost'}{t}orders{/t}{else}{t}orders so far{/t}{/if}, {t}which amounts to a total of{/t} <b>{$part->get('Net Balance')}</b> {t}plus tax{/t} ({t}an average of{/t} {$part->get('Total Net Per Order')} {t}per order{/t}). {if $part->get('Customer Orders Invoiced')}
					</p>
					<p>
						{if $part->get('Customer Type by Activity')=='Lost'}{t}This product used to place an order every{/t}{else}{t}This product usually places an order every{/t}{/if} {$part->get('Order Interval')}.{/if} {else} Customer has not place any order yet. {/if}
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
    var email_length = $('#showcase_Customer_Main_Plain_Email').text().length

    if (email_length > 30) {
        $('#showcase_Customer_Main_Plain_Email').css("font-size", "90%");
    }
}

email_width_hack();

</script>
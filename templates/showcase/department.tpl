<div class="subject_profile">
	<div id="contact_data">
		<div class="data_container">
			
			<div class="data_field" >
				<h1 >{t}Department{/t}
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
		{include file='sticky_note.tpl' object='Category'  key=$category->id sticky_note_field='Category_Sticky_Note' _object=$category}

	
		
		
		<div style="clear:both">
		</div>
	</div>
	<div id="info">
		<div id="overviews">
			<table border="0" class="overview" style="">
				<tr id="account_balance_tr" class="main">
					<td id="account_balance_label">{t}Sales{/t}</td>
					<td id="account_balance" class="aright highlight">{$category->get('Account Balance')} </td>
				</tr>
				<tr id="last_credit_note_tr" style="display:none">
					<td colspan="2" class="aright" style="padding-right:20px">{t}Credit note{/t}: <span id="account_balance_last_credit_note"></span></td>
				</tr>
				
			</table>
			<table border="0" class="overview">
				{if $category->get('Customer Level Type')=='VIP'} 
				<td></td>
				<td class="highlight">{t}VIP Customer{/t}</td>
				{/if} {if $category->get('Customer Level Type')=='Partner'} 
				<td></td>
				<td class="highlight">{t}Partner Customer{/t}</td>
				{/if} {if $category->get('Customer Type by Activity')=='Losing'} 
				<tr>
					<td colspan="2">{t}Losing Customer{/t}</td>
				</tr>
				{elseif $category->get('Customer Type by Activity')=='Lost'} 
				<tr>
					<td>{t}Lost Customer{/t}</td>
					<td>{$category->get('Lost Date')}</td>
				</tr>
				{/if} 
				<tr>
					<td>{t}Contact Since{/t}:</td>
					<td>{$category->get('First Contacted Date')}</td>
				</tr>
				
			</table>
			{if $category->get('Customer Send Newsletter')=='No' or $category->get('Customer Send Email Marketing')=='No' or $category->get('Customer Send Postal Marketing')=='No'} 
			<table border="0" class="overview compact">
				<tr class="{if $category->get('Customer Send Newsletter')=='Yes'}hide{/if}">
					<td colspan="2"> <i class="fa fa-ban"></i> <span>{t}Don't send newsletters{/t}</span> </td>
				</tr>
				<tr class="{if $category->get('Customer Send Email Marketing')=='Yes'}hide{/if}">
					<td colspan="2"> <i class="fa fa-ban"></i> <span>{t}Don't send marketing by email{/t}</span> </td>
				</tr>
				<tr class="{if $category->get('Customer Send Postal Marketing')=='Yes'}hide{/if}">
					<td colspan="2"> <i class="fa fa-ban"></i> <span>{t}Don't send marketing by post{/t}</span> </td>
				</tr>
			</table>
			{/if} {if $category->get('Customer Orders')>0} 
			<table class="overview">
				{if $category->get('Customer Type by Activity')=='Lost'} 
				<tr>
					<td><span style="color:white;background:black;padding:1px 10px">{t}Lost Customer{/t}</span></td>
				</tr>
				{/if} {if $category->get('Customer Type by Activity')=='Losing'} 
				<tr>
					<td><span style="color:white;background:black;padding:1px 10px">{t}Warning!, loosing category{/t}</span></td>
				</tr>
				{/if} 
				<tr>
					<td class="text"> {if $category->get('Customer Orders')==1} 
					<p>
						{$category->get('Name')} {t}has place one order{/t}.
					</p>
					{elseif $category->get('Customer Orders')>1 } {$category->get('Name')} {if $category->get('Customer Type by Activity')=='Lost'}{t}placed{/t}{else}{t}has placed{/t}{/if} <b>{$category->get('Customer Orders')}</b> {if $category->get('Customer Type by Activity')=='Lost'}{t}orders{/t}{else}{t}orders so far{/t}{/if}, {t}which amounts to a total of{/t} <b>{$category->get('Net Balance')}</b> {t}plus tax{/t} ({t}an average of{/t} {$category->get('Total Net Per Order')} {t}per order{/t}). {if $category->get('Customer Orders Invoiced')}
					</p>
					<p>
						{if $category->get('Customer Type by Activity')=='Lost'}{t}This category used to place an order every{/t}{else}{t}This category usually places an order every{/t}{/if} {$category->get('Order Interval')}.{/if} {else} Customer has not place any order yet. {/if}
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
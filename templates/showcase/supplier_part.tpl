 
	    
<div class="asset_profile container" >

	<div id="asset_data">
		<div class="data_container">
			<div class="data_field">
				<h1>
					<span class="Part_Unit_Description">{$part->get('Part Unit Description')}</span> <span class="Store_Product_Price">{$part->get('Price')}</span>
				</h1>
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
			{assign "image_key" $part->get_main_image_key()} 
			<div class="wraptocenter main_image {if $image_key==''}hide{/if}">
				<img src="/{if $image_key}image_root.php?id={$image_key}&amp;size=small{else}art/nopic.png{/if}"> </span> 
			</div>
			{include file='upload_main_image.tpl' object='Part' key=$part->id class="{if $image_key!=''}hide{/if}"} 
		</div>
		{include file='sticky_note.tpl' object='Category' key=$part->id sticky_note_field='Store_Product_Sticky_Note' _object=$part} 
		<div style="clear:both">
		</div>
	</div>
	<div id="info" style="position:relative;top:0px">
		<div id="overviews">
			<table border="0" class="overview" style="">
				<tr id="account_balance_tr" class="main">
				<td >{$supplier_part->get('Average Delivery')}</td>
					<td  class="aright highlight">{$supplier_part->get('Status')} </td>
				</tr>
			</table>
			
			<table border="0" class="overview">
				<tr  class="main">
					
					<td >{$part->get('Available Forecast')}</td>
					
					<td class="aright highlight" >
					{$part->get('Part Current On Hand Stock')} {$part->get('Stock Status Icon')}
					</td>
					
				</tr>
				
				
			</table>
			
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
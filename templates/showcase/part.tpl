
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
			
			{assign "image_key" $part->get_main_image_key()}
			<div class="wraptocenter main_image {if $image_key==''}hide{/if}" >
				
				<img src="/{if $image_key}image_root.php?id={$image_key}&size=small{else}art/nopic.png{/if}"  >
				
				</span>
				
			</div>	
			{include file='upload_main_image.tpl' object='Part'  key=$part->id class="{if $image_key!=''}hide{/if}"}

			
			
				
				
				
			
		</div>
		{include file='sticky_note.tpl' object='Category'  key=$part->id sticky_note_field='Store_Product_Sticky_Note' _object=$part}

	
		
		
		
		
		<div style="clear:both">
		</div>
	</div>
	<div id="info" style="position:relative;top:-10px">
		<div id="overviews">
			<table border="0" class="overview">
				<tr  class="main">
					
					<td class=" highlight">{$part->get('Status')} </td>
					
					<td class="aright highlight" style="font-size:200%">
					{$part->get('Part Current On Hand Stock')} {$part->get('Stock Status Icon')}
					</td>
					
				</tr>
				<tr>
					
						<td class="aright" colspan="2" style="padding-top:0;color:#777;font-size:90%"> <b id="current_stock" title="{t}Current stock{/t}">{$part->get('Part Current Stock')}</b> <b>-[<span id="current_stock_picked" title="{t}Stock picked{/t}">{$part->get('Part Current Stock Picked')}</span>]</b> -(<span id="current_stock_in_process" title="{t}Waiting to be picked{/t}">{$part->get('Part Current Stock In Process')}</span>) &rarr; <span title="{t}Available to sell{/t}" id="current_stock_available">{$part->get('Current Stock Available')}</span></td>
					
				</tr>
				<tr>
				<td></td>
				<td class="aright">{$part->get('Available Forecast')}</td>
				</tr>
				
			</table>
			
			<table id="barcode" border="0" class="overview {if $part->get('Part Barcode Number')==''}hide{/if} ">
				<tr  class="main">
					<td class="label"><i class="fa fa-barcode"></i></td>
					<td class=" highlight">{$part->get('Part Barcode Number')} </td>
					<td class="aright">
					<a title="{t}Stock keeping unit (Outer){/t}" href="/asset_label.php?object=part&key={$part->id}&type=package"><i class="fa fa-tag "></i></a>
					<a class="padding_left_10" title="{t}Commercial unit label{/t}" href="/asset_label.php?object=part&key={$part->id}&type=unit"><i class="fa fa-tags "></i></a>
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
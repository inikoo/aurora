 
	    
<div class="asset_profile container" >

	<div id="asset_data" style="xborder:1px solid red">
		<div class="data_container" style="xborder:1px solid blue">
			<div class="data_field">
				<h1>
					<span class="Supplier_Part_Unit_Description">{$part->get('Part Unit Description')}</span> <span class="Store_Product_Price">{$part->get('Price')}</span>
				</h1>
			</div>
		</div>
		
		<div style="clear:both" >
		</div>
		<div class="data_container"  style="xborder:1px solid blue">
			
			{assign "image_key" $part->get_main_image_key()} 
			<div class="wraptocenter main_image {if $image_key==''}hide{/if}">
				<img src="/{if $image_key}image_root.php?id={$image_key}&amp;size=small{else}art/nopic.png{/if}"> </span> 
			</div>
			{include file='upload_main_image.tpl' object='Part' key=$part->id class="{if $image_key!=''}hide{/if}"} 
		</div>
		
		<div class="data_container carton_sko_units"  >
		    <table border=0 >
		    <tr class="carton">
		    <td class=" canvas" >
		    <canvas width="80" height="80"></canvas>
		    </td>
		     <td class="info">
		    <div>{t}Carton{/t}</div>
		    <div><span class="Carton_Weight padding_right_5">{$supplier_part->get('Carton Weight')}</span> <span class="Carton_CBM">{$supplier_part->get('Carton CBM')}</span></div>
		    <div></div>
		    <div><span class="Carton_Cost">{$supplier_part->get('Carton Cost')}</span></div>
		    </td>
		    
		    </tr>
		    <tr class="sko">
		    <td  class="canvas" >
		    <canvas width="80" height="80"></canvas>
		    </td>
		     <td class="info">
		    <div>{t}SKO{/t} (<span class="Packages_Per_Carton">{$supplier_part->get('Supplier Part Packages Per Carton')}</span> {t}per carton{/t})</div>
		    <div><span class="SKO_Barcode">{$supplier_part->get('SKO Barcode')}</span></div>
		    <div><span class="SKO_Weight padding_right_5">{$supplier_part->get('SKO Weight')}</span> <span class="SKO_Dimensions">{$supplier_part->get('SKO Dimensions')}</span></div>
		    <div><span class="SKO_Cost">{$supplier_part->get('SKO Cost')}</span></div>
		    </td>
		    
		    </tr>
		    <tr class="unit">
		    <td  class="canvas" >
		    <canvas width="80" height="80"></canvas>
		    </td>
		     <td class="info">
		    <div>{t}Unit{/t} (<span class="Supplier_Part_Units_Per_Package">{$supplier_part->get('Supplier Part Units Per Package')}</span> {t}per SKO{/t})</div>
		    <div><span class="Unit_CBM">{$supplier_part->get('Unit Dimensions')}</span></div>
		    <div><span class="Unit_Weight">{$supplier_part->get('Unit Weight')}</span></div>
		    <div><span class="Unit_Cost_Amount">{$supplier_part->get('Unit Cost Amount')}</span></div>
		    </td>
		    
		    </tr>
		    </table>
		</div>
		
		<div style="clear:both">
		</div>
	</div>
	<div id="info" style="position:relative;top:0px">
		
		<div id="overviews">
		
	
		
		
			<table border="0" class="overview" style="">
				<tr  class="main">
				<td ><span class="Average_Delivery">{$supplier_part->get('Average Delivery')}</span></td>
					<td  class="aright highlight Status">{$supplier_part->get('Status')} </td>
				</tr>
				
				<tr>
				</td>
				
				<td>
				<td class="aright">
			<span class="Supplier_Part_Units_Per_Package  " title="{t}Units per package{/t}">{$supplier_part->get('Supplier Part Units Per Package')}</span>
			<i  class="fa fa-arrow-right very_discreet padding_right_10 padding_left_10"></i> [<span class="Packages_Per_Carton" title="{t}Packages per carton{/t}">{$supplier_part->get('Supplier Part Packages Per Carton')}</span>]
			<span class="discreet Supplier_Part_Units_Per_Carton padding_left_10" title="{t}Units per carton{/t}" >{$supplier_part->get('Units Per Carton')}</span>

				
				
				
				</td>
				</tr>
				
			</table>
			
			
			
			
			
			<div id="part_data" style="padding-top:10px;clear:both">
			
			<table border="0" class="overview with_title">
			    
				<tr  class="top">
					
					<td >{t}Part{/t} <span class="Part_Reference button padding_left_10" onClick="change_view('part/{$part->id}')">{$part->get('Reference')}</span></td>
					
					<td class="aright" >
					{$part->get('State')}
					</td>
					
				</tr>
				<tr  class="main">
					
					<td >{$part->get('Available Forecast')}</td>
					
					<td class="aright highlight" >
					{$part->get('Part Current On Hand Stock')} {$part->get('Stock Status Icon')}
					</td>
					
				</tr>
				
				
			</table>
			</div>
			
		</div>
	</div>
	<div style="clear:both">
	</div>
</div>
<script>

</script>
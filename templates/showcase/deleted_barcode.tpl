
<div class="asset_profile"  style="padding-top:10px">


	<div id="asset_data">
		
		<div class="data_container" style="opacity:.4">
			<div class="wraptocenter" style="height:100px" >
				<img src="/barcode_asset.php?number={$barcode->get('Barcode Deleted Number')}&scale=10"  >
			</div>	
			
		</div>
		
		<div id="showcase_sticky_note"  class="data_container {if $barcode->get('Deleted Sticky Note')==''}hide{/if} ">
			<div class="sticky_note_button">
				<i class="fa fa-sticky-note " ></i> 
			</div>
			<div  class="sticky_note" > 
				{$barcode->get('Deleted Sticky Note')} 
			</div>
		</div>
		
		
		
		
		<div style="clear:both">
		</div>
	</div>
	<div id="info" style="position:relative;top:0px">
		<div id="overviews">
			<table border="0" class="overview" style="">
				<tr id="status_tr" class="main">
					<td  class=" highlight"> {t}Deleted{/t} </td>
				</tr>
				
				
			</table>
			
		</div>
	</div>
	<div style="clear:both">
	</div>
	
	
	
</div>



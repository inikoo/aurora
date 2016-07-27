
<div class="asset_profile container">
	<div id="asset_data">
		<div class="data_container">
			{assign "family" $employee->get('Family')} 
			<div class="data_field">
				<h1>
					<span class="Product_Name">{$employee->get('Name')}</span> 
				</h1>
			<div style="margin-top:10px">{t}Payroll Id{/t}: {$employee->get('ID')}
			</div>
			</div>
			
			
		</div>
		
		
		<div style="clear:both">
		</div>
	</div>
	<div id="info">
		<div id="overviews">
			<table border="0" class="overview" style="">
			<tr class="main  ">
					<td class=" title">{t}Deleted{/t}</td>
					<td class="aright ">{$employee->get('Deleted Date')}</td>

				</tr>
			
				
				
				
			</table>
		</div>
	</div>
	<div style="clear:both">
	</div>
</div>



<script>



</script>
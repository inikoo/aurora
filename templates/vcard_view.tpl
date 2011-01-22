{include file='header.tpl'}
<div id="bd" >

<div id="no_details_title" style="clear:left;{if $show_details}display:none;{/if}">
    <h1>Contacts imported</h1>
  </div>
<br>

				
			
			<div class='framedsection'>
			<center><a style='font-size: 14px; color:#256B91; text-decoration: none;'>{$num_records}</a>	</center>
			<table>
			</tr>
				<tr><td>&nbsp;</td><td></td>
				</tr>
			 {foreach from=$final_array item=array_item}
				
   				{foreach key=key from=$array_item item=array_value }
					{* <li>{$key} : {$array_value}</li> *}
					{if $key == 'Customer Main Contact Name'}
						{assign var='name' value=$array_value}
					{/if}
					{if $key == 'Customer Type'}
						{assign var='title' value=$array_value}
					{/if}
					{if $key == 'Customer Company Name'}
						{assign var='org' value=$array_value}
					{/if}
					{if $key == 'Customer Main Plain Email'}
						{assign var='email' value=$array_value}
					{/if}
					{if $key == 'Customer Main Plain Mobile'}
						{assign var='telephone' value=$array_value}
					{/if}
					{if $key == 'Customer Main Office Address'}
						{assign var='office_address' value=$array_value}
					{/if}
					

					
				{/foreach}
				
				<tr>
				    
				     <td>  
					<a href="#" style='font-size: 14px; color:#256B91;'>{$name}</a>,&nbsp; {$title} at <a href="#" style='color:#256B91;'>{$org}</a><br>{$office_address}
				     </td>
				     <td width="250">
					&nbsp;
				     </td>
				     <td>
					<a href="#" style='color:#256B91;'>{$email}</a><br>{$telephone}
				     </td>
				
				</tr>
				
				<tr><td>&nbsp;</td><td></td>

				</tr>
				
			
			{/foreach}
			
			</table> 

		
		
		</div>

				


</div>

{include file='footer.tpl'}

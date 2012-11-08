{include file='header.tpl'} 
<div id="bd">
	<input type="hidden" id="dn_key" value="{$dn->id}" />
		<input type="hidden" id="dn_state" value="{$dn->get('Delivery Note State')}" />
		<input type="hidden" id="dn_picker_key" value="{$dn->get('Delivery Note Assigned Picker Key')}" />
		<input type="hidden" id="dn_packer_key" value="{$dn->get('Delivery Note Assigned Packer Key')}" />


	{include file='orders_navigation.tpl'} 
	<div class="branch">
		<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home"/></a>&rarr; {if $user->get_number_stores()>1}<a href="orders_server.php?view=dns"> &#8704; {t}Delivery Notes{/t}</a> &rarr; {/if}<a href="orders.php?store={$store->id}&view=dns">{$store->get('Store Code')} {t}Delivery Notes{/t}</a> &rarr; {$dn->get('Delivery Note ID')} ({$dn->get_formated_state()})</span> 
	</div>
	<div class="top_page_menu" style="border:none">
		<div class="buttons" style="float:right">
  

			<button style="height:24px;" onclick="window.location='dn.pdf.php?id={$dn->id}'"><img style="width:40px;height:12px;position:relative;bottom:3px" src="art/pdf.gif" alt=""></button> 
			
			{if $dn->get('Delivery Note Fraction Picked')==1 and $dn->get('Delivery Note Fraction Packed')==1}
			
				{if $dn->get('Delivery Note Approved Done')=='No'}
			<button id="aprove_packing" onClick="aprove_packing()" style="height:24px;"><img id="aprove_packing_img" src="art/icons/flag_green.png" alt="" /> {t}Aprove Picking/Packing{/t}</button> 
				{else}
			    	{if $dn->get('Delivery Note Approved To Dispatch')=='No'}	
						<button id="aprove_dispatching"><img id="aprove_dispatching_img" src="art/icons/package_green.png" alt=""> {t}Aprove Dispatching{/t}</button>
					{else}
				
							<button id="set_as_dispatched"><img id="set_as_dispatched_img" src="art/icons/lorry_go.png" alt=""> {t}Set as Dispatched{/t}</button>
					{/if}
				
				
					{if !$dn->get_number_invoices()}
						<button style="height:24px;" id="create_invoice"><img src="art/icons/money.png" alt=""> {t}Create Invoice{/t}</button> 
					{/if}
				
				{/if}	
			
			
			
			{else if $dn->get('Delivery Note Fraction Picked')==0 and $dn->get('Delivery Note Fraction Packed')==0}
				{if $dn->get('Delivery Note Assigned Picker Key')}
						<button style="height:24px;"   onCLick="window.location='order_pick_aid.php?id={$dn->id}'"><img src="art/icons/basket_put.png" alt=""> {t}Picking Aid Sheet{/t}</button> 
				{else}
						<button style="height:24px;"   id="pick_it_"><img src="art/icons/basket_put.png" alt=""> {t}Start Picking{/t}</button> 
				{/if}
					
			{else if $dn->get('Delivery Note Fraction Picked')>0  and  $dn->get('Delivery Note Fraction Picked')<1 }
									<button style="height:24px;"   onCLick="window.location='order_pick_aid.php?id={$dn->id}'"><img src="art/icons/basket_put.png" alt=""> {t}Picking Aid Sheet{/t}</button> 

						{if $dn->get('Delivery Note Fraction Packed')>0}
															<button style="height:24px;"   onCLick="window.location='order_pack_aid.php?id={$dn->id}'"><img src="art/icons/package.png" alt=""> {t}Packing Aid Sheet{/t}</button> 

						{/if}
						
					
			
			{else if $dn->get('Delivery Note Fraction Picked')==1  }
						{if $dn->get('Delivery Note Assigned Packer Key')}
						<button style="height:24px;"   onCLick="window.location='order_pack_aid.php?id={$dn->id}'"><img src="art/icons/package.png" alt=""> {t}Packing Aid Sheet{/t}</button> 
					{else}
				<button style="height:24px;"  id="pack_it"><img src="art/icons/package_add.png" alt=""> {t}Start Packing{/t}</button> 

					{/if}	
					
			
			{/if}
			{*}
			
			
				
				
		
			
			
			
			{*}
			
			
		</div>
		<div class="buttons" style="float:left">
		
		<span class="main_title">{t}Delivery Note{/t} <span class="id">{$dn->get('Delivery Note ID')}</span></span>
		
		{*}
			{if isset($referal) and $referal=='store_pending_orders'} <button onclick="window.location='$referal_url'"><img src="art/icons/text_list_bullets.png" alt=""> {t}Pending Orders (Store){/t}</button> {else} <button onclick="window.location='warehouse_orders.php?id={$dn->get('Delivery Note Warehouse Key')}'"><img src="art/icons/basket.png" alt=""> {t}Pending Orders{/t}</button> {/if}  
		{*}
		</div>
		<div style="clear:both">
		</div>
	</div>
	<div style="border:1px solid #ccc;text-align:left;padding:10px;margin: 5px 0 10px 0">
		<div style="border:0px solid #ddd;width:350px;float:left;;position:relative;top:-5px">
			<h3 style="padding:0;padding-top:0px">
				{$dn->get('Delivery Note Title')}
			</h3>
			<h2 style="padding:0;padding-top:5px">
				{$dn->get('Delivery Note Customer Name')} <a class="id" href="customer.php?id={$dn->get(" order customer id")}">{$customer->get_formated_id()}</a>
			</h2>
			<div style="float:left;line-height: 1.0em;margin:5px 0px;color:#444">
				<span style="font-weight:500;color:#000">{$dn->get('Order Customer Contact Name')}</span>
			</div>
						<div style="float:left;line-height: 1.0em;margin:5px 0 0 0px;color:#444">
			
				{$dn->get('Delivery Note XHTML Ship To')}
			</div>
			
			<div style="clear:both">
			</div>
		</div>
		<div style="border:0px solid #ddd;width:290px;float:right">
			<table border="0" style="width:100%;border-top:1px solid #333;border-bottom:1px solid #333;width:100%,padding:0;margin:0;float:right;margin-left:0px;margin-bottom:5px">
				{if $dn->get('Delivery Note Fraction Packed')==1}
				<tr>
					<td class="aright">{t}Parcels:{/t}</td>
					<td width="200px" class="aright">{$dn->get_formated_parcels()}</td>
				</tr>
				{/if}	
				{if $dn->get('Delivery Note Weight Source')=='Estimated'}
				<tr>
					<td class="aright">{t}Estimated Weight:{/t}</td>
					<td width="200px" class="aright">{$dn->get('Estimated Weight')}</td>
				</tr>
				{else}
				<tr>
					<td class="aright">{t}Weight{/t}:</td>
					<td width="200px" class="aright">{$dn->get('Weight')}</td>
				</tr>
				{/if}
				<tr>
					<td class="aright">{t}Picked by:{/t}</td>
					<td width="200px" class="aright">{$dn->get('Delivery Note XHTML Pickers')}</td>
				</tr>
		        <tr>
					<td class="aright">{t}Packed by:{/t}</td>
					<td width="200px" class="aright">{$dn->get('Delivery Note XHTML Packers')}</td>
				</tr>
		    </table>
			<div style="display:none" id="dn_state" >
				{$dn->get('Delivery Note XHTML State')}
			</div>
		</div>
		<div style="border:0px solid red;width:250px;float:right">
			{if isset($note)}
			<div class="notes">
				{$note}
			</div>
			{/if} 
			<table border="0" style="border-top:1px solid #333;border-bottom:1px solid #333;width:100%,padding-right:0px;margin-right:30px;float:right">
				<tr>
					<td>{t}Creation Date{/t}:</td>
					<td class="aright">{$dn->get('Date Created')}</td>
				</tr>
				<tr>
					<td>{t}Orders{/t}:</td>
					<td class="aright">{$dn->get('Delivery Note XHTML Orders')}</td>
				</tr>
				
				
				
				{if $dn->get('Delivery Note XHTML Invoices')!=''} 
				<tr>
					<td>{t}Invoices{/t}:</td>
					<td class="aright">{$dn->get('Delivery Note XHTML Invoices')}</td>
				</tr>
				{/if} 
			</table>
		</div>
		<div style="clear:both">
		</div>
	</div>


<div class="data_table" style="clear:both">
			<span id="table_title" class="clean_table_title">{t}Items{/t}</span> 

			<div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999;margin-bottom:10px">
			</div>
			{include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0 } 
			<div id="table0" style="font-size:80%" class="data_table_container dtable btable">
			</div>
		</div>


</div>

<div id="dialog_pick_it" style="padding:20px 20px 10px 20px">
  <div id="pick_it_msg"></div>
  
  <div class="buttons">
  <button  class="positive"  onClick="assign_picker(this,{$dn->id})" >{t}Assign Picker{/t}</button>
  <button  class="positive" onClick="pick_it(this,{$dn->id})" >{t}Pick it{/t}</button>
    <button class="negative" id="close_dialog_pick_it" >{t}Cancel{/t}</button>

  </div>

</div>

<div id="dialog_pack_it" style="padding:20px 20px 10px 20px">
  <div id="pack_it_msg"></div>
  
  <div class="buttons">
 <button  class="positive"  onClick="assign_packer(this,{$dn->id})" >{t}Assign Packer{/t}</button>
  <button  class="positive" onClick="pack_it(this,{$dn->id})" >{t}Pack it{/t}</button>
    <button class="negative" id="close_dialog_pack_it" >{t}Cancel{/t}</button>

  </div>

</div>

{include file='assign_picker_packer_splinter.tpl'}

{include file='footer.tpl'} 


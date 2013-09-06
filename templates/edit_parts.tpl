{include file='header.tpl'} 
<div id="bd">
	{include file='locations_navigation.tpl'} 
	<input type="hidden" value="{$warehouse->id}" id="warehouse_id" />
	<input type="hidden" value="{$warehouse->id}" id="warehouse_key" />
	<input type="hidden" value="{$warehouse->id}" id="parent_key" />
	<input type="hidden" id="Custom_Field_Warehouse_Key" value="{$warehouse->id}"> 
	<input type="hidden" id="Custom_Field_Table" value="Part"> 
	<div class="branch">
		<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /></a>&rarr; {if $user->get_number_warehouses()>1}<a href="warehouses.php">{t}Warehouses{/t}</a> &rarr; {/if}{t}Inventory{/t}</span> 
	</div>
	<div class="top_page_menu">
		<div class="buttons" style="float:right">
			<button onclick="window.location='inventory.php?id={$warehouse->id}'"><img src="art/icons/door_out.png" alt=""> {t}Exit Edit{/t}</button> 
		</div>
		<div class="buttons" style="float:left">
			<span class="main_title"><img src="art/icons/warehouse.png" style="height:18px;position:relative;bottom:2px" /> <span class="id">{$warehouse->get('Warehouse Name')}</span> {t}Edit Parts{/t} </span> 
		</div>
		<div style="clear:both">
		</div>
	</div>
	<ul class="tabs" id="chooser_ul">
		<li><span class="item {if $edit=='parts'}selected{/if}" id="parts"> <span>{t}Parts{/t}</span></span></li>
	</ul>
	<div class="tabbed_container">

		<div class="edit_block" {if $edit!="parts" }style="display:none" {/if} id="d_parts">
		
		
		</div>
	</div>
</div>
{include file='footer.tpl'} 
{include file='header.tpl'} 
<div id="bd" style="padding:0px 0px">
	<div style="padding:0px 20px;margin-top:5px">
		
		<div style="clear:both;width:100%;border-bottom:1px solid #ccc;padding-bottom:3px">
    {if $number_of_dashboards> 1}<img onMouseover="this.src='art/previous_button.gif'"  onMouseout="this.src='art/previous_button.png'"   title="{t}Previous Dashboard{/t} {$prev.name}" onclick="window.location='index.php?dashboard_id={$prev.id}'"  src="art/previous_button.png" alt="<"  style="margin-right:10px;float:left;height:22px;cursor:pointer;position:relative;top:2px" />{/if}
<div class="buttons" style="float:left">
			</div>



    {if  $number_of_dashboards> 1}<img onMouseover="this.src='art/next_button.gif'"  onMouseout="this.src='art/next_button.png'"  title="{t}Next Dashboard{/t} {$next.name}"  onclick="window.location='index.php?dashboard_id={$next.id}'"   src="art/next_button.png" alt=">"  style="float:right;height:22px;cursor:pointer;position:relative;top:2px"/ >{/if}

			<div class="buttons" style="float:right">
				<button onclick="window.location='edit_dashboard.php?id={$dashboard_key}'"><img src="art/icons/cog.png" alt=""> {t}Configure Dashboard{/t}</button> 
		{if $user->get('User Type')=='Warehouse' and isset($warehouse_key)}<button onclick="window.location='warehouse_orders.php?id={$warehouse_key}'"><img src="art/icons/basket_put.png" alt=""> {t}Orders to Process{/t}</button>{/if}
		</div>
			
			<div style="clear:both">
			</div>
		</div>
	</div>
	{if $user->get('User Type')=='Warehouse'}
	<div style="margin:20px;border:1px solid #ccc;padding:20px">
	<table>
	<tr><td>{t}Order Picking Aid{/t}: <input id="order_picking_aid" value="" style="width:200px"></td><td><div class="buttons small"><button id="search_order_picking_aid">{t}Search{/t}</button></div></td>
	<td><span style="display:none" id="order_picking_aid_waiting"><img src="art/loading.gif" alt=""/>{t}Processig Request{/t}</span><span id="order_picking_aid_msg"></span></td>
	</table>
	</div>
	{/if}
	<div class="dashboard_blocks" style="margin-top:20px">
		{foreach from=$blocks key=key item=block} 
		<div class="{$block.class}" style="margin-bottom:30px">
			<iframe onload="changeHeight(this);" id="block_{$block.key}" src="{$block.src}&block_key={$block.key}" width="100%" {if $block.height}height="{$block.height}"{/if} frameborder="0" scrolling="no"> 
		<p>
			{t}Your browser does not support iframes{/t}.
		</p>
		</iframe> 
	</div>
	{/foreach} 
</div>
</div>
{include file='footer.tpl'} 
{include file='header.tpl'}
<div id="bd" >
<div id="content" >
{include file='contacts_navigation.tpl'}
		<div class="branch">
			<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home"/></a>&rarr;  {if $user->get_number_stores()>1}<a href="customers_server.php">{t}Customers{/t}</a> &rarr; {/if}<a href="customers.php?store={$store->id}">{t}Customers{/t} ({$store->get('Store Code')})</a> &rarr; {t}Deleted Customer{/t} ({$customer_data.CustomerKey})</span> 
		</div>
		<div id="top_page_menu" class="top_page_menu">
			<div class="buttons" style="float:left">
				{if isset($parent_list)}<img style="vertical-align:xbottom;xfloat:none" class="previous" onmouseover="this.src='art/previous_button.gif'" onmouseout="this.src='art/previous_button.png'"  title="{t}Previous Customer{/t} {$prev.name}" onclick="window.location='customer.php?{$parent_info}id={$next.id}{if $parent_list}&p={$parent_list}{/if}'" onclick="window.location='customer.php?{$parent_info}id={$prev.id}{if $parent_list}&p={$parent_list}{/if}'" src="art/previous_button.png" />{/if} 
			<span class="main_title">{t}Deleted Customer{/t} <span class="id">{$customer_data.CustomerKey}</span></span>		
				
			</div>
			{if isset($parent_list)}<img onmouseover="this.src='art/next_button.gif'" onmouseout="this.src='art/next_button.png'" title="{t}Next Customer{/t} {$next.name}" onclick="window.location='customer.php?{$parent_info}id={$next.id}{if $parent_list}&p={$parent_list}{/if}'" src="art/next_button.png" alt=">" style="float:right;height:22px;cursor:pointer;position:relative;top:2px" />{/if} 
			<div class="buttons" style="float:right">

			</div>
			<div style="clear:both">
			</div>
		</div>


<i>{t}deleted date{/t}: {$deleted_date}</i>
<div style="margin-top:20px">{$customer_data.CustomerCard}</div>


<div style="margin-top:20px">
{$message}
</div>

</div>
</div>
{include file='footer.tpl'}

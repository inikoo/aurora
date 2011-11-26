{include file='header.tpl'}
<div id="bd" style="padding:0px">
<div style="padding:0 20px;height:50px">

<div  class="branch" style="clear:left;"> 
  <span >{t}Dashboard{/t}</span>
</div>

<div style="clear:both;width:100%;border-bottom:1px solid #ccc;padding-bottom:3px">
    <div class="buttons" style="float:right">
       
        <button  onclick="window.location='dashboard_configuration.php'" ><img src="art/icons/cog.png" alt=""> {t}Configure Dashboard{/t}</button>
      
    </div>
    <div class="buttons" style="float:left">
    </div>
    <div style="clear:both"></div>
</div>


<div class="dashboard_blocks" style="margin-top:20px">
	{foreach from=$blocks key=key item=block}
	    <div class="{$block.class}">
	    <iframe  id="block_{$block.key}" src="{$block.src}&block_key={$block.key}" width="100%" frameborder=0  >
            <p>Your browser does not support iframes.</p>
        </iframe> 
	    </div>
	{/foreach}
</div>

{*}
<div  class="general_options" style="margin:5px 20px 0 0">
 {foreach from=$search_options_list item=options }
    {if $options.tipo=="url"}
    <span style="float:left" onclick="window.location.href='{$options.url}'" >{$options.label}</span>
    {else}
    <span  id="{$options.id}" state="{$options.state}">{$options.label}</span>
    {/if}
    {/foreach}
</div>
 <div id="search" style="display:none;border:0px solid black;margin:auto;text-align:center;padding:10px;margin:10px;margin-top:0;margin-bottom:0;padding-bottom:0">
Please use the Search in customers/orders pages.
</div>
 <div id="search" style="visibility:hidden;border:0px solid black;margin:auto;text-align:center;padding:10px;margin:10px">
    <span  >{t}Search{/t}:</span>
    <input size="45" class="text" id="all_search" value="" state="" name="search"/><img style="position:relative;left:-18px;display:none"  id="all_clean_search"  class="submitsearch" src="art/icons/cross_bw.png" alt="{t}cross{/t}" />
   
    <div id="all_search_Container" style="display:none"></div>
    <div style="position:relative;font-size:80%">
      <div id="all_search_results" style="display:none;position:absolute;background:#fff;border:1px solid #777;padding:10px;margin-top:0px;width:720px;z-index:20;left:100px;">
	<table id="all_search_results_table"></table>
      </div>
    </div>
  </div>
{*}
</div>

{*}
	<div id="wrapper" stylw="display:none">
	<input type="hidden" value='{$store_keys}' id="store_keys"/>
		<div id="wid_menu" >
			<img style="position:relative;top:3px;display:none" src="art/icons/previous.png" alt="" id="previous"/>
			<ul id="buttons">
	{foreach from=$splinters key=key item=splinter}
        <li id="splinter_but_{$key}" key="{$key}" class="splinter_buttons {if $display_block==$key}active{/if}" onClick="change_block(this)">{$splinter.title}</li>
            {/foreach}
            </ul>
			<img style="position:relative;top:3px;display:none" src="art/icons/next.png" alt="" id="next"/>
		</div>

		
		<!-- this section has our panes, unfortunately we need two divs to make the effect work -->
		<div id="panes">
			<div id="content">
			
			{foreach from=$splinters key=key item=splinter}
			<div class="pane"  id="pane_{$key}" {if $display_block!=$key}style="display:none"{/if}>
			{if !($valid_sales) && ($key eq 'sales')}
			<center>{t}No Sales{/t}</center>
			{elseif !($valid_customers) && ($key eq 'top_customers')}
			<center>{t}No Customers{/t}</center>
			{elseif !($valid_products) && ($key eq 'top_products')}
			<center>{t}No Products{/t}</center>
			{else}
			{include file=$splinter.tpl index=$splinter.index}
			{/if}
			</div>
			{/foreach}
			</div>
		</div>
	</div>
{*}

</div>
{include file='footer.tpl'}

{include file='header.tpl'}
<div id="bd" style="padding:0px">
	<script src="js/index_tools.js" type="text/javascript"></script>
	<script src="js/index_sliding_tabs.js" type="text/javascript"></script>



<div  class="general_options" style="margin:5px 20px 0 0">
 {foreach from=$search_options_list item=options }
    {if $options.tipo=="url"}
    <span style="float:left" onclick="window.location.href='{$options.url}'" >{$options.label}</span>
    {else}
    <span  id="{$options.id}" state="{$options.state}">{$options.label}</span>
    {/if}
    {/foreach}
</div>

<div style="font-weight:800;clear:both;margin:20px 50px;padding:20px 50px;border:1px solid #ccc">
Hello, here i am going to place the announcements about bug fixed and new features.
<ul style="padding:20px">



<li  >
<h2>Order Read Times</h2>
<table>
<tr><td>GB</td><td>00',10',20',30',40',50'</td><td> (past every hour)</td></tr>
<tr><td>DE</td><td>02',12',22',32',42',52' </td><td>(past every hour)</td></tr>
<tr><td>FR</td><td>04',14',24',34',44',54'</td><td> (past every hour)</td></tr>
<tr><td>PL</td><td>06' <span style="color:#777;font-size:90%">(remember to save as CSV)</span></td><td> (past every hour)</td></tr>

</table>

</li>

<li  style="list-style-type: square;color:red">Delivery Address Implemented (Display, Creation and Edition)</li>

<li  style="list-style-type: square">Better Telephone Formatting (editing and <span style="color:red">creating</span>)</li>
<li  style="list-style-type: square">Customer Orders now ordered by default by date</li>

<li  style="list-style-type: square">Accept/Reject Marketing Emails is asked when creating a customer</li>
<li  style="list-style-type: square">Tax number exported in QO Data Excel Cell[CJ1]</li>
<li  style="list-style-type: square">New Feature: Customer Lists (Used to make Newsletters and Marketing Emails)</li>

</ul>
</div>

 <div id="search" style="border:0px solid black;margin:auto;text-align:center;padding:10px;margin:10px">
    <span  >{t}Search{/t}:</span>
    <input size="45" class="text" id="all_search" value="" state="" name="search"/><img style="position:relative;left:-18px;display:none"  id="all_clean_search"  class="submitsearch" src="art/icons/cross_bw.png" alt="{t}cross{/t}" />
   
    <div id="all_search_Container" style="display:none"></div>
    <div style="position:relative;font-size:80%">
      <div id="all_search_results" style="display:none;position:absolute;background:#fff;border:1px solid #777;padding:10px;margin-top:0px;width:720px;z-index:20;left:100px;">
	<table id="all_search_results_table"></table>
      </div>
    </div>
  </div>




	<div id="wrapper">
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
{include file=$splinter.tpl index=$splinter.index}
</div>
{/foreach}
			</div>
		</div>
	</div>
	{literal}
	<script type="text/javascript" charset="utf-8">
	
	
	
	
	</script>
{/literal}

</div>
{include file='footer.tpl'}

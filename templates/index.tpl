{include file='header.tpl'}
<div id="bd" style="padding:0px">
	<script src="js/index_tools.js" type="text/javascript"></script>
	<script src="js/index_sliding_tabs.js" type="text/javascript"></script>




 <div id="search" style="border:0px solid black;margin:auto;text-align:center;padding:10px;margin:10px">
    <span  >{t}Search{/t}:</span>
    <input size="45" class="text" id="all_search" value="" state="" name="search"/><img style="position:relative;left:-18px;display:none"align="absbottom" id="all_clean_search"  class="submitsearch" src="art/icons/cross_bw.png" >
   
    <div id="all_search_Container" style="display:none"></div>
    <div style="position:relative;font-size:80%">
      <div id="all_search_results" style="display:none;position:absolute;background:#fff;border:1px solid #777;padding:10px;margin-top:0px;width:720px;z-index:20;left:100px;">
	<table id="all_search_results_table"></table>
      </div>
    </div>
  </div>




	<div id="wrapper">
	<input type="hidden" value='{$store_keys}' id="store_keys">
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

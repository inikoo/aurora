{include file='header.tpl'}
<div id="bd" style="padding:0px">
	<script src="js/index_tools.js" type="text/javascript"></script>
	<script src="js/index_sliding_tabs.js" type="text/javascript"></script>
 <div id="search" style="border:0px solid black;margin:auto;text-align:center;padding:10px;margin:10px">
    <span  >{t}Search{/t}:</span>
    <input size="45" class="text" id="{$search_scope}_search" value="" state="" name="search"/>
   
    <div id="{$search_scope}_search_Container" style="display:none"></div>
    <div style="position:relative;font-size:80%">
      <div id="{$search_scope}_search_results" style="display:none;background:#fff;border:1px solid #777;padding:10px;margin-top:0px;width:500px;position:absolute;z-index:20;left:-520px">
	<table id="{$search_scope}_search_results_table"></table>
      </div>
    </div>
  </div>

	<div id="wrapper">
		<div id="wid_menu" >
			<img style="position:relative;top:3px" src="art/icons/previous.png" alt="" id="previous" />
			<ul id="buttons">
			
{foreach from=$splinters key=key item=splinter}

<li>{$splinter.title}</li>
{/foreach}
			</ul>
			<img style="position:relative;top:3px" src="art/icons/next.png" alt="" id="next" />
		</div>

		
		<!-- this section has our panes, unfortunately we need two divs to make the effect work -->
		<div id="panes">
			<div id="content">
			
			{foreach from=$splinters key=key item=splinter}
<div class="pane">
{include file=$splinter.tpl index=$splinter.index}
</div>
{/foreach}
			
			
			
			

			</div>
		</div>
	</div>
	{literal}
	<script type="text/javascript" charset="utf-8">
		window.addEvent('load', function () {
			myTabs = new SlidingTabs('buttons', 'panes');
			
			// this sets up the previous/next buttons, if you want them
			$('previous').addEvent('click', myTabs.previous.bind(myTabs));
			$('next').addEvent('click', myTabs.next.bind(myTabs));
			
			// this sets it up to work even if it's width isn't a set amount of pixels
			window.addEvent('resize', myTabs.recalcWidths.bind(myTabs));
		});
	</script>
{/literal}

</div>
{include file='footer.tpl'}

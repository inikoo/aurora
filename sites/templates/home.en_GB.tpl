{include file="$head_template"}
 <body>
   <div id="container" >
     {include file="$home_header_template"}
     <div id="page_content" style="{$page_data.content_style}">
       {include file="$left_menu_template"}
            <div id="central_content">

{include file="templates/search_input.tpl"}
	


  
	{foreach from=$page_data.showcases item=showcase}
	{if $showcase.type=='banner'}
	 <div class="banner"  >
	<a href="{$showcase.url}"><img src="{$showcase.src}"/></a>
	</div>
	{/if}
	{if $showcase.type=='div'}
	 <div class="showcase" style="{$showcase.style}"  >
	 
	{$showcase.innerHtml}
	</div>
	{/if}
	{/foreach}
       </div>
       {include file="$right_menu_template"}
        <div style="clear:both"></div>
     </div>
	<div align=center>Hits Number in this site : {$count_hits}</div>
     {include file="$footer_template"}
 </body>

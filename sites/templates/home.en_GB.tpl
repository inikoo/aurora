{include file="$head_template"}
 <body>
   <div id="container" >
     {include file="$home_header_template"}
     <div id="page_content" >
       {include file="$left_menu_template"}
     
       <div id="central_content">

	
	
	 <div id="search_container" >
    <span class="search_title" >{$traslated_labels.search}:</span>
    <input size="25" class="text search" id="search" store_key="{$store_key}"  value="" state="" name="search"/><img align="absbottom" id="clean_search"  class="submitsearch" src="art/icons/zoom.png" >
    <div id="search_Container" style="display:none"></div>
    <div style="position:relative;font-size:80%">
      <div id="search_results" style="display:none;background:#fff;border:1px solid #777;padding:10px;margin-top:0px;width:500px;position:absolute;z-index:20;xleft:-520px">
	<table id="search_results_table">
	
	</table>
      </div>
    </div>
  </div>
	
	
	
	
	<div >
	   {include file="$main_showcase"}
	 </div>
	 <div id="banner_top" class="banner"  >
	   <a href="{$banners.top.url}"><img src="{$banners.top.src}"/></a>
	 </div>
	 {if $second_showcase}	 
	 <div id="second_showcase" >
	   {include file="$second_showcase"}
	 </div>
	 {/if}
	 <div id="banner_bottom" class="banner" >
	   <a href="{$banners.bottom.url}"><img src="{$banners.bottom.src}"/></a>
	 </div>
       </div>
       {include file="$right_menu_template"}
        <div style="clear:both"></div>
     </div>
     {include file="$footer_template"}
 </body>

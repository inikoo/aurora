{include file="$head_template"}
 <body>
   <div id="container" >
     {include file="$home_header_template"}
     <div id="page_content">
       {include file="$left_menu_template"}
     
       <div id="central_content">
	 <div id="search" >
	   Search: <input type="text"/>
	 </div>
	 <div >
	   {include file="$main_showcase"}
	 </div>
	 <div id="banner_top" class="banner"  >
	   <a href="{$banners.top.url}"><img src="{$banners.top.src}"/></a>
	 </div>
	 <div id="second_showcase" >
	   {include file="$second_showcase"}
	 </div>
	 <div id="banner_bottom" class="banner" >
	   <a href="{$banners.bottom.url}"><img src="{$banners.bottom.src}"/></a>
	 </div>
       </div>
       {include file="$right_menu_template"}
       
     </div>

     {include file="$footer_template"}
 </body>

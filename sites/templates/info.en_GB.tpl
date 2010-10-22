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
            <div id="content">
                {$page->get('Product Presentation Template Data')}	 
            </div>
       </div>
       {include file="$right_menu_template"}
        <div style="clear:both"></div>
     </div>

     {include file="$footer_template"}
 </body>

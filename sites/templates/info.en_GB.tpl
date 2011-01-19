{include file="$head_template"}
 <body>
   <div id="container" >
     {include file="$home_header_template"}
     <div id="page_content" style="{$page_data.content_style}"  >
       {include file="$left_menu_template"}
     
       <div id="central_content">
       {include file="templates/search_input.tpl"}
       
	       
            <div id="content">
                {$page->get('Product Presentation Template Data')}	 
            </div>
       </div>
       {include file="$right_menu_template"}
        <div style="clear:both"></div>
     </div>

     {include file="$footer_template"}
 </body>

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

	 {include file="$contents" }
	 

       </div>
       {include file="$right_menu_template"}
       
     </div>

     {include file="$footer_template"}
 </body>

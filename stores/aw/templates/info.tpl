{include file='head.tpl'}
 <body>
   <div id="container" >
     {include file='home_header.tpl'}
     <div id="page_content">
       {include file='left_menu.tpl'}
     
       <div id="central_content">
	 <div id="search" >
	   Search: <input type="text"/>
	 </div>

	 {include file="$contents" }
	 

       </div>
       {include file='right_menu.tpl'}
       
     </div>

     {include file='footer.tpl'}
 </body>

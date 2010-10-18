{include  file="$head_template"}
 <body>
   <div id="container" >
     {include file="$home_header_template"}
     <div id="page_content" >

     {include file="$left_menu_template"}
     <div id="central_content" style="min-height:600px">
       <div id="no_logged_error" style="border:1px solid #ccc;margin:20px 40px;padding:20px;{if !$login}display:none{/if}" > 
	     <p>You are already login.</p>
       </div>
       {if !$login}
       <div  style="border:1px solid #ccc;margin:20px 40px;padding:20px;" > 
	 <div style="width:250px">
	 <input type="hidden" value="{$secret_string}" id="ep">


      {$traslated_labels.email}:
      <input id="login_handle" type="text" value="" style="width:95%"/>
      {$traslated_labels.password}:
      <input  id="login_password"  type="password"  value=""  style="width:95%"/>
      <button id="sing_in" onclick="login()" style="float:right;margin-right:5px;font-size:95%">{$traslated_labels.login}</button>
      
      <div style="margin-top:36px;display:none" id="invalid_credentials">{$traslated_labels.invalid_credentials}</div>

      
      <div style="margin-top:36px"><a href="lost_password.php">{$traslated_labels.forgot_password}</a></div>
      
	 </div>
	 
	
	 </div>
       {/if}
       </div>
     
     <div style="clear:both"></div>

      </div>
     
   
     {include file="$footer_template"}
     
   </div>
 </body>

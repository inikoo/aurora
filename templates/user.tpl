{include file='header.tpl'}
<div id="bd" >
{include file='users_navigation.tpl'}


  <div id="yui-main">
    <div style="width:300px;float:right;padding:10px;text-align:right">

     
    </div>
<h1>{t}My Profile{/t}</h1>
<div style="width:200px;margin-top:20px">
	<table    class="show_info_product">
		  <td class="aright">
		    
		     <tr >
		       <td>{t}Login{/t}:</td>
		        <td>{$user_class->get('User Handle')}</td>
			</tr>
		     <tr>
		       <td>{t}Alias{/t}:</td>
		        <td>{$user_class->get('User Alias')}</td>
		     </tr>
		</table>
</div>

    
  </div>
</div> 

{include file='footer.tpl'}


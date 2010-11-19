{include file='header.tpl'}
<div id="bd" >
<div id="no_details_title" style="clear:right;">
    <h1>{t}Staff User{/t}</h1>
</div>

<div style="width:230px;margin-top:20px;float:left">
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
<div style="width:310px;margin-top:20px;float:left;margin-left:20px">
	<table    class="show_info_product">
		  <td class="aright">
		    
		     <tr >
		       <td>{t}Login Count{/t}:</td>
		        <td>{$user_class->get('Login Count')}</td>
			</tr>
		     <tr>
		       <td>{t}Last Login{/t}:</td>
		        <td>{$user_class->get('Last Login')}</td>
		     </tr>
		</table>
		
</div>
<div style="width:310px;margin-top:20px;float:left;margin-left:20px">
	<table    class="show_info_product">
		  <td class="aright">
		    
		     <tr >
		       <td>{t}Failed Login Count{/t}:</td>
		        <td>{$user_class->get('Failed Login Count')}</td>
			</tr>
		     <tr>
		       <td>{t}Failed Last Login{/t}:</td>
		        <td>{$user_class->get('Last Failed Login')}</td>
		     </tr>
		</table>
		
</div>    
  </div>


{include file='footer.tpl'}


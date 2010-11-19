{include file='header.tpl'}

<div id="bd" >
{include file='users_navigation.tpl'}



  <div id="yui-main">
   
    <div class="data_table" style="margin-top:25px">
      <span class="clean_table_title">{t}Staff User{/t}</span>
      <div  class="clean_table_caption"  style="clear:both;">
	<div style="float:left;"><div id="table_info0" class="clean_table_info"><span id="rtext0"></span> <span class="rtext_rpp" id="rtext_rpp0"></span> <span class="filter_msg"  id="filter_msg0"></span></div></div>
	
	<div class="clean_table_controls" style="" ><div><span  style="margin:0 5px" id="paginator0"></span></div></div>
      </div>
      <div  id="table0"   class="data_table_container dtable btable "> </div>
    </div>
   
    
  </div>
</div> 

{include file='footer.tpl'}



/* {include file='header.tpl'}
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

<div  id="block_orders" class="data_table" style="clear:both;margin:25px 0px">
    <span id="table_title" class="clean_table_title">{t}Orders with this Product{/t}</span>
    {include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0}
    <div  id="table0"   class="data_table_container dtable btable "> </div>
  </div>


{include file='footer.tpl'}*/


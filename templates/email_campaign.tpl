{include file='header.tpl'}
<div id="bd" >
<input value="{$email_campaign->id}" id="email_campaign_key" type="hidden"  />
{include file='marketing_navigation.tpl'}
<div class="branch"> 
  <span>{if $user->get_number_stores()>1}<a href="marketing_server.php">{t}Marketing{/t}</a> &rarr;  {/if} <a href="marketing.php?store={$store->id}&block_view=email">{$store->get('Store Code')} {t}Marketing{/t} ({t}Email Campaigns{/t})</a> &rarr; {$email_campaign->get('Email Campaign Name')}</span>
</div>

<div style="clear:both;border:1px solid #ccc;padding:5px 10px">
<h2>{$email_campaign->get('Email Campaign Name')}</h2>

<div style="width:310px;margin-top:0px;float:left;margin-left:0px">
	<table    class="show_info_product">
		  <td class="aright">
		    
		     <tr >
		       <td>{t}Objective{/t}:</td>
		        <td class="aright">{$email_campaign->get('Email Campaign Objective')}</td>
			</tr>
		     <tr>
		       <td>{t}Number of Recipients{/t}:</td>
		        <td class="aright">{$email_campaign->get('Number of Emails')}</td>
		     </tr>
		</table>
		
		
		<span id="countdown1">{$email_campaign->get('Email Campaign Start Overdue Date')} GMT+00:00</span>
</div>

<div style="clear:both"></div>
</div>


 <div id="the_table" class="data_table" style="clear:both;margin-top:20px">
      <span class="clean_table_title">{t}Mailing List{/t}</span>
      
 
  <div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999;"></div>

{include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0  }
 <div  id="table0"  style="font-size:90%"  class="data_table_container dtable btable"> </div>
 </div>

</div>

<div id="dialog_export">
	
  
  
  
  <div id="filtermenu0" class="yuimenu">
    <div class="bd">
      <ul class="first-of-type">
	<li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Filter options{/t}:</li>
	{foreach from=$filter_menu0 item=menu }
	<li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_filter('{$menu.db_key}','{$menu.label}',0)"> {$menu.menu_label}</a></li>
	{/foreach}
      </ul>
    </div>
  </div>
  
  <div id="rppmenu0" class="yuimenu">
    <div class="bd">
      <ul class="first-of-type">
	<li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Rows per Page{/t}:</li>
	{foreach from=$paginator_menu0 item=menu }
	<li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_rpp({$menu},0)"> {$menu}</a></li>
	{/foreach}
      </ul>
    </div>
  </div>
  


  {include file='footer.tpl'}

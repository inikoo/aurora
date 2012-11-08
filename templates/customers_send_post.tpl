{include file='header.tpl'}
<div id="bd" >
{include file='contacts_navigation.tpl'}
<input type="hidden" id="store_key" value="{$store->id}"/>
<div class="branch"> 
  <span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home"/></a>&rarr;  {if $user->get_number_stores()>1}<a  href="customers_server.php">{t}Customers{/t}</a> &rarr; {/if}<a  href="customers.php?store={$store->id}">{$store->get('Store Code')} {t}Customers{/t}</a> &rarr; {t}Pending Post{/t}</span>
</div>
 <div style="clear:both;width:100%;border-bottom:1px solid #ccc;padding-bottom:3px">
    <div class="buttons" style="float:left">
<span class="main_title">{t}Post to send{/t} <span class="id">({$store->get('Store Code')})</span></span>
    </div>
  <div class="buttons">
      {if $modify}<button onclick="window.location='edit_customers.php?list_key={$customer_list_key}'" ><img src="art/icons/table_edit.png" alt=""/> {t}Edit Customers in List{/t}</button>{/if}
           <button onclick="window.location='customers_address_label.pdf.php?label=l7159&type=send_post&id={$store->id}'" ><img src="art/icons/printer.png" alt=""/> {t}Print{/t}</button>

      </div>
    <div style="clear:both"></div>
</div>






 <div id="the_table" class="data_table" style="clear:both;margin-top:10px">
      <div>
      <span class="clean_table_title">{t}Customers{/t}  <img id="export_data"  style="position:relative;top:0px;left:5px;cursor:pointer;vertical-align:text-bottom;" label="{t}Export (CSV){/t}" alt="{t}Export (CSV){/t}" src="art/icons/export_csv.gif"></span>
    <div style="font-size:90%" id="transaction_chooser">
				<span style="float:right;margin-left:20px;" class=" table_type transaction_type state_details {if $elements.Send}selected{/if} label_page_type" id="elements_Send">{t}Send{/t} (<span id="elements_Send_number">{$elements_number.Send}</span>)</span> 
				<span style="float:right;margin-left:20px;" class=" table_type transaction_type state_details {if $elements.ToSend}selected{/if} label_page_type" id="elements_ToSend">{t}To Send{/t} (<span id="elements_ToSend_number">{$elements_number.ToSend}</span>)</span> 
			</div>



 
  <div style="clear:both;margin:2px 0 0 0px;padding:0 20px ;border-bottom:1px solid #999"></div>
  <table style="float:left;margin:0 0 0 0px ;padding:0"  class="options" >
	<tr>
	  <td {if $view=='general'}class="selected"{/if} id="general" >{t}General{/t}</td>
	  	  <td {if $view=='info'}class="selected"{/if}  id="info"  >{t}Info{/t}</td>

	  <td {if $view=='contact'}class="selected"{/if}  id="contact"  >{t}Contact{/t}</td>
	  <td {if $view=='address'}class="selected"{/if}  id="address"  >{t}Address{/t}</td>
	  <td {if $view=='balance'}class="selected"{/if}  id="balance"  >{t}Balance{/t}</td>
	  <td {if $view=='rank'}class="selected"{/if}  id="rank"  >{t}Ranking{/t}</td>
	</tr>
      </table>
{include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0  }
 <div  id="table0"  style="font-size:85%"  class="data_table_container dtable btable"> </div>
 </div>

</div>

</div>

<div id="dialog_export">
	<div id="export_msg"></div>
	  <table style="padding:10px;margin:20px 10px 10px 10px" >
	 <tr><td><a href="export_data.php?subject=customers_list&subject_key={$customer_list_key}&source=db">{t}Export Data (using last map){/t}</a></td></tr>
	 <tr><td><a href="export_data_maps.php?subject=customers_list&subject_key={$customer_list_key}&source=db">{t}Export from another map{/t}</a></td></tr>
	 <tr><td><a href="export_wizard.php?subject=customers_list&subject_key={$customer_list_key}">{t}Export Wizard (new map){/t}</a></td></tr>
	</table>
	</div>
  
  
  
  
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
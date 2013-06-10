{include file='header.tpl'}
<div id="bd" >
{include file='contacts_navigation.tpl'}
<input type="hidden" id="customer_list_key" value="{$customer_list_key}"/>
<div class="branch"> 
  <span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home"/></a>&rarr;  {if $user->get_number_stores()>1}<a  href="customers_server.php">{t}Customers{/t}</a> &rarr; {/if}<a  href="customers.php?store={$store->id}">{$store->get('Store Code')} {t}Customers{/t}</a> &rarr; <a href="customers_lists.php?store={$store->id}">{t}Lists{/t}</a> &rarr; {$customer_list_name}</span>
</div>
 <div style="clear:both;width:100%;border-bottom:1px solid #ccc;padding-bottom:3px">
    <div class="buttons" style="float:left">
<span class="main_title">{t}Customers List{/t}: <span class="id">{$customer_list_name}</span></span>
    </div>
  <div class="buttons">
      {if $modify}<button onclick="window.location='edit_customers.php?list_key={$customer_list_key}'" ><img src="art/icons/table_edit.png" alt=""/> {t}Edit Customers in List{/t}</button>{/if}
           <button onclick="window.location='customers_address_label.pdf.php?label=l7159&scope=list&id={$customer_list_key}'" ><img src="art/icons/printer.png" alt=""/> {t}Print{/t}</button>

      </div>
    <div style="clear:both"></div>
</div>


 <div id="the_table" class="data_table" style="clear:both;margin-top:10px">
      <div>
      <span class="clean_table_title">{t}Customers{/t}  <img id="export_customers"  style="position:relative;top:0px;left:5px;cursor:pointer;vertical-align:text-bottom;" label="{t}Export (CSV){/t}" alt="{t}Export (CSV){/t}" src="art/icons/export_csv.gif"></span>
    
 
<div class="table_top_bar">
				</div>
 <div class="clusters">
					<div class="buttons small left cluster">
	<button class="table_option {if $view=='general'}selected{/if}" id="general">{t}General{/t}</button> 
						<button class="table_option {if $view=='contact'}selected{/if}" id="contact">{t}Contact{/t}</button> 
						<button class="table_option {if $view=='address'}selected{/if}" id="address">{t}Address{/t}</button> 
						<button class="table_option {if $view=='balance'}selected{/if}" id="balance">{t}Balance{/t}</button> 
						<button class="table_option {if $view=='rank'}selected{/if}" id="rank">{t}Ranking{/t}</button>
						<button class="table_option {if $view=='weblog'}selected{/if}"  id="weblog"  >{t}WebLog{/t}</button>

	</div>
	<div style="clear:both">
					</div>
      </div>
{include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0  }
 <div  id="table0"  style="font-size:90%"  class="data_table_container dtable btable"> </div>
 </div>

</div>

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
  

{include file='export_splinter.tpl' id='customers' export_fields=$export_customer_fields map=$export_customer_map is_map_default={$export_customer_map_is_default}}







  {include file='footer.tpl'}
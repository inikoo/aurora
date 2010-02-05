{include file='header.tpl'}
<div id="bd" >
{include file='contacts_navigation.tpl'}

  <div id="no_details_title"  style="clear:left;xmargin:0 20px;{if $details!=0}display:none{/if}">
    <h1>{t}Store{/t}: {$store->get('Store Name')} ({$store->get('Store Code')})</h1>
  </div>
  <div id="info"  style="clear:left;margin-top:10px;padding:0 0px;width:910px;{if $details==0}display:none{/if}">
    <h2>{t}Customers Information{/t} ({$store->get('Store Code')})</h2>
      <p style="width:475px">{$overview_text}</p>
      <div id="plot"  class="top_bar" style="width:100%;;position:relative;clear:both;padding:0;margin:0px;{if !$details}display:none;{/if}">
	<ul id="plot_chooser" class="tabs" style="margin:0 0px;padding:0 20px "  >
	  <li>
	    <span class="item  {if $plot_tipo=='customers'}selected{/if}" onClick="change_plot(this)" id="plot_customers" tipo="customers" category="{$plot_data.customers.category}" period="{$plot_data.customers.period}" >
	      <span>{t}All Contacts{/t}</span>
	    </span>
	  </li>
	  <li>
	    <span class="item  {if $plot_tipo=='active_customers'}selected{/if}"  id="plot_active_customers" onClick="change_plot(this)" tipo="active_customers" category="{$plot_data.active_customers.category}" period="{$plot_data.active_customers.period}" name=""  >
	      <span>{t}Actual Customers{/t}</span>
	    </span>
	  </li>
	  
	</ul> 
	<ul id="plot_options" class="tabs" style="{if $plot_tipo=='pie'}display:none{/if};position:relative;top:.6em;float:right;margin:0 20px;padding:0 20px;font-size:90% "  >
	  <li><span class="item option"> <span id="plot_category"  category="{$plot_category}" style="xborder:1px solid black;display:inline-block; vertical-align:middle">{$plot_formated_category}</span></span></li>
	  <li><span class="item option"> <span id="plot_period"   period="{$plot_period}" style="xborder:1px solid black;display:inline-block; vertical-align:middle">{$plot_formated_period}</span></span></li>
	</ul> 
	
	<div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999">
	  
      </div>
      
      <iframe id="the_plot" src ="{$plot_page}?{$plot_args}"  frameborder=0 height="325" scrolling="no" width="100%"></iframe>
      </div>
      <p style="width:475px">{$top_text}</p>
      <p style="width:475px">{$export_text}</p>




    </div>

    
    <div id="the_table" class="data_table" style="clear:both">
      <span class="clean_table_title">Customers List</span>
       <div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999"></div>
  <table style="float:left;margin:0 0 0 0px ;padding:0"  class="options" {if $customers==0 }style="display:none"{/if}>
	<tr>
	  <td  {if $view=='general'}class="selected"{/if} id="general" >{t}General{/t}</td>
	  <td {if $view=='contact'}class="selected"{/if}  id="contact"  >{t}Contact{/t}</td>
	  <td {if $view=='addresses'}class="selected"{/if}  id="address"  >{t}Address{/t}</td>
	  <td {if $view=='ship_to_addresses'}class="selected"{/if}  id="ship_to_address"  >{t}Shipping Address{/t}</td>
	  <td {if $view=='balance'}class="selected"{/if}  id="balance"  >{t}Balance{/t}</td>
	  <td {if $view=='rank'}class="selected"{/if}  id="rank"  >{t}Ranking{/t}</td>

	</tr>
      </table>

      <div  class="clean_table_caption"  style="clear:both;">
	<div style="float:left;"><div id="table_info0" class="clean_table_info"><span id="rtext0"></span> <span class="rtext_rpp" id="rtext_rpp0"></span> 
	    <span class="filter_msg"  id="filter_msg0"></span></div></div>
	<div class="clean_table_filter" id="clean_table_filter0"><div class="clean_table_info"><span id="filter_name0">{$filter_name}</span>: <input style="border-bottom:none" id='f_input0' value="{$filter_value}" size=10/><div id='f_container'></div></div></div>

	<div class="clean_table_controls" style="" ><div><span  style="margin:0 5px" id="paginator"></span></div></div>

      </div>
      <div  id="table0"   class="data_table_container dtable btable "> </div>
    </div>
</div>


  </div>
</div>
</div> 
<div id="filtermenu" class="yuimenu">
  <div class="bd">
    <ul class="first-of-type">
       <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Filter options{/t}:</li>
      {foreach from=$filter_menu item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_filter('{$menu.db_key}','{$menu.label}',0)"> {$menu.menu_label}</a></li>
      {/foreach}
    </ul>
  </div>
</div>

<div id="rppmenu" class="yuimenu">
  <div class="bd">
    <ul class="first-of-type">
       <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Rows per Page{/t}:</li>
      {foreach from=$paginator_menu item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_rpp({$menu},0)"> {$menu}</a></li>
      {/foreach}
    </ul>
  </div>
</div>

{include file='footer.tpl'}

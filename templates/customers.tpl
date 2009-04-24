{include file='header.tpl'}
<div id="bd" >
  <span class="nav2 onleft"><a href="contacts.php">{t}Contact's List{/t}</a></span>
  <span class="nav2 onleft"><a href="search_customers.php">{t}Advanced Search{/t}</a></span>
    
  <div >
    
  <div class="search_box" >
       <span class="search_title" style="padding-right:15px" tipo="customer_name">{t}Customer Name{/t}:</span> <br>
       <input size="8" class="text search" id="customer_search" value="" name="search"/><img align="absbottom" id="customer_submit_search" class="submitsearch" src="art/icons/zoom.png" alt="Submit search"><br/>
       <span  class="product_search_msg"   id="customer_search_msg"    ></span> <span  class="search_sugestion"   id="customer_search_sugestion"    ></span>
       <br/>
 <a id="but_advanced_search"  class="state_details" href="search_customers.php">{t}Advanced Search{/t}</a><br/>
 <span id="but_show_details" state="{$details}" atitle="{if $details==0}{t}Hide Details{/t}{else}{t}Show Details{/t}{/if}" class="state_details"   >{if $details==1}{t}Hide Details{/t}{else}{t}Show Details{/t}{/if}</span><br/>
 <a id="but_new_customer"  class="state_details" href="new_customer.php">{t}New Customer{/t}</a>       
  </div>
    


    
 <div id="top" class="top_bar">
    <div id="short_menu" class="nodetails" style="width:100%;margin-bottom:0px">
      <table style="float:left;margin:0 0 0 20px ;padding:0"  class="options" {if $products==0 }style="display:none"{/if}>
	<tr>
	  <td  {if $view=='general'}class="selected"{/if} id="general" >{t}General{/t}</td>
	  <td {if $view=='contact'}class="selected"{/if}  id="contact"  >{t}Contact{/t}</td>
	  <td {if $view=='addresses'}class="selected"{/if}  id="address"  >{t}Address{/t}</td>
	  <td {if $view=='ship_to_addresses'}class="selected"{/if}  id="ship_to_address"  >{t}Shipping Address{/t}</td>
	  <td {if $view=='balance'}class="selected"{/if}  id="balance"  >{t}Balance{/t}</td>
	  <td {if $view=='rank'}class="selected"{/if}  id="rank"  >{t}Ranking{/t}</td>

	</tr>
      </table>
    </div>


    <div id="details"  style="margin-top:10px;padding:0 20px;width:770px;{if $details==0}display:none{/if}">
      <h2>{t}Our Dear Customers{/t}</h2>
      <p style="width:475px">{$overview_text}</p>
      <p style="width:475px">{$top_text}</p>
      <p style="width:475px">{$export_text}</p>
    </div>

    
    <div id="the_table" class="data_table" style="margin:20px 20px;clear:both">
      <span class="clean_table_title">{$table_title}</span>
      <div  class="clean_table_caption"  style="clear:both;">
	<div style="float:left;"><div id="table_info0" class="clean_table_info"><span id="rtext0"></span> <span class="filter_msg"  id="filter_msg0"></span></div></div>
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

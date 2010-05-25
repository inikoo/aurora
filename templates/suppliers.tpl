{include file='header.tpl'}
<div id="bd" >
 {include file='suppliers_navigation.tpl'}
 <div id="no_details_title"  style="clear:left;xmargin:0 20px;{if $details!=0}display:none{/if}">
    <h1>{t}Suppliers{/t}</h1>
  </div>


 <div class="data_table" style="clear:both">
   <span class="clean_table_title">{t}Suppliers List{/t}</span>
   <div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999"></div>
   <table style="float:left;margin:0 0 0 0px ;padding:0"  class="options" {if $products==0 }style="display:none"{/if}>
	<tr><td  {if $view=='general'}class="selected"{/if} id="general" >{t}General{/t}</td>
	  <td {if $view=='products'}class="selected"{/if}  id="products"  >{t}Products{/t}</td>
	  {if $view_stock}<td {if $view=='stock'}class="selected"{/if}  id="stock"  >{t}Stock{/t}</td>{/if}
	  {if $view_sales}<td  {if $view=='sales'}class="selected"{/if}  id="sales"  >{t}Sales{/t}</td>{/if}
	</tr>
      </table>
  
 <div  class="clean_table_caption"  style="clear:both;">
	 <div style="float:left;">
	   <div id="table_info0" class="clean_table_info"><span id="rtext0"></span> <span class="rtext_rpp" id="rtext_rpp0"></span> <span class="filter_msg"  id="filter_msg0"></span></div>
	 </div>
	 <div class="clean_table_filter clean_table_filter_show" id="clean_table_filter_show0" {if $filter_show0}style="display:none"{/if}>{t}filter results{/t}</div>
	 <div class="clean_table_filter" id="clean_table_filter0" {if !$filter_show0}style="display:none"{/if}>
	   

	   <div class="clean_table_info" style="padding-bottom:1px; ">
	     
	     <span id="filter_name0" class="filter_name"  style="margin-right:5px">{$filter_name0}:</span>
	     <input style="border-bottom:none;width:6em;" id='f_input0' value="{$filter_value0}" size=10/> <span class="clean_table_filter_show" id="clean_table_filter_hide0" style="margin-left:8px">{t}Hide filter{/t}</span>
	 <div id='f_container0'></div>
	   </div>
	   
	 </div>
	 <div class="clean_table_controls" style="" ><div><span  style="margin:0 5px" id="paginator0"></span></div></div>
       </div>
 
    <div  id="table0"   class="data_table_container dtable btable "> </div>
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

{include file='footer.tpl'}

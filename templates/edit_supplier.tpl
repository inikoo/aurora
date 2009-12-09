{include file='header.tpl'}
<div id="bd" >
<div class="search_box" style="margin-top:15px">
  <div class="general_options">
    {foreach from=$general_options_list item=options }
        {if $options.tipo=="url"}
            <span onclick="window.location.href='{$options.url}'" >{$options.label}</span>
        {else}
            <span  id="{$options.id}" state="{$options.state}">{$options.label}</span>
        {/if}
    {/foreach}
    </div>
</div>
 <div id="no_details_title"  style="clear:left;xmargin:0 20px;{if $details!=0}display:none{/if}">
    <h1>{t}Edit Supplier{/t}: <span id="title_name">{$supplier->get('Supplier Name')}</span> (<span id="title_code">{$supplier->get('Supplier Code')}</span>)</h1>
  </div>

  <ul class="tabs" id="chooser_ul" style="clear:both">
    <li> <span class="item {if $edit=='details'}selected{/if}"  id="details">  <span> {t}Supplier Details{/t}</span></span></li>

    <li> <span class="item {if $edit=='company'}selected{/if}"  id="company">  <span> {t}Company Details{/t}</span></span></li>
    <li> <span class="item {if $edit=='products'}selected{/if}"  id="products">  <span> {t}Supplier Products{/t}</span></span></li>
   
  </ul>

 <div class="tabbed_container" > 
   <div  class="edit_block" style="{if $edit!="details"}display:none{/if}"  id="d_details">
  
       <div class="general_options" style="float:right">
	
	<span  style="margin-right:10px;visibility:hidden"  id="save_edit_supplier" class="state_details">{t}Save{/t}</span>
	<span style="margin-right:10px;visibility:hidden" id="reset_edit_supplier" class="state_details">{t}Reset{/t}</span>
	
      </div>
  
   <table class="edit" border=0 style="clear:both">
	<tr class="first"><td style="width:11em" class="label">Supplier Code:</td>
	  <td  style="text-align:left;width:19em">
	    <div  style="width:15em;position:relative;top:00px" >
	      <input style="text-align:left;width:18em" id="Supplier_Code" value="{$supplier->get('Supplier Code')}" ovalue="{$supplier->get('Supplier Code')}" valid="0">
	      <div id="Supplier_Code_Container" style="" ></div>
	    </div>
	  </td>
	  <td id="Supplier_Code_msg" class="edit_td_alert"></td>
	</tr>
	<tr class="first"><td style="" class="label">{t}Company Name{/t}:</td>
	  <td  style="text-align:left">
	    <div  style="width:15em;position:relative;top:00px" >
	      <input style="text-align:left;width:18em" id="Supplier_Name" value="{$supplier->get('Supplier Name')}" ovalue="{$supplier->get('Supplier Name')}" valid="0">
	      <div id="Supplier_Name_Container" style="" ></div>
	    </div>
	  </td>
	  	  <td id="Supplier_Name_msg" class="edit_td_alert"></td>

	</tr>


     </table>
   </div>
   

   <div  class="edit_block" style="{if $edit!="company"}display:none{/if}"  id="d_company">
      <div class="general_options" style="float:right">
	
	<span  style="margin-right:10px;display:none"  id="save_new_supplier" class="state_details">{t}Save{/t}</span>
	<span style="margin-right:10px;display:none" id="close_add_supplier" class="state_details">{t}Reset{/t}</span>
	
      </div>


      <div id="new_supplier_messages" class="messages_block"></div>

      


     
	  
       {include file='edit_company_splinter.tpl'}


     
   </div>

  <div  class="edit_block" style="{if $edit!="products"}display:none{/if}"  id="d_products">
   
  <div class="data_table" style="clear:both">
    <span class="clean_table_title">{t}Suppliers Product List{/t}</span>
    <div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999"></div>
    <table style="float:left;margin:0 0 0 0px ;padding:0"  class="options" {if $products==0 }style="display:none"{/if}>
      <tr><td  {if $view=='general'}class="selected"{/if} id="general" >{t}General{/t}</td>
	  <td {if $view=='products'}class="selected"{/if}  id="products"  >{t}Products{/t}</td>
	  {if $view_stock}<td {if $view=='stock'}class="selected"{/if}  id="stock"  >{t}Stock{/t}</td>{/if}
	  {if $view_sales}<td  {if $view=='sales'}class="selected"{/if}  id="sales"  >{t}Sales{/t}</td>{/if}
      </tr>
      </table>
    
    <div  class="clean_table_caption"  style="clear:both;">
      <div style="float:left;"><div id="table_info0" class="clean_table_info"><span id="rtext0"></span> <span class="filter_msg"  id="filter_msg0"></span></div></div>
      <div class="clean_table_filter" id="clean_table_filter0"><div class="clean_table_info"><span  id="filter_name0">{$filter_name0}</span>: <input style="border-bottom:none;width:100px;position:relative;"  id='f_input0' value="{$filter_value}" size=10/><div id='f_container0'></div></div></div>
      <div class="clean_table_controls" style="" ><div><span  style="margin:0 5px" id="paginator"></span></div></div>
    </div>
    <div  id="table0"   class="data_table_container dtable btable "> </div>
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

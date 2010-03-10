{include file='header.tpl'}
<div id="bd" >
 {include file='suppliers_navigation.tpl'}
  <div id="no_details_title" style="clear:left;{if $show_details}display:none;{/if}">
  <h1>{t}Supplier{/t}: {$supplier->get('Supplier Name')} <span style="color:SteelBlue">({$supplier->get('Supplier Code')})</span></h1>
   <table style="width:500px" >
	
	<tr>
	  <td valign="top">{$company->get('Company Main XHTML Address')}</td>
	  <td  valign="top">
	    <table border=0 style="padding:0">
	      <tr><td colspan=2>{$company->get('Company Main Contact Name')}</td ></tr>
	      <tr><td colspan=2>{$company->get('Company Main XHTML Email')}</td ></tr>
	      <tr><td colspan=2>{$company->get('Company Main Telephone')}</td ></tr>
	      <tr><td colspan=2>{$company->get('Company Main FAX')}</td ></tr>

	      
	      
	      {foreach from=$telecoms item=telecom}
	       <tr><td >
		   {if $telecom[0]=='mob'}<img src="art/icons/phone.png"/ title="{t}Mobile Phone{/t}">
		   {elseif   $telecom[0]=='tel'}<img src="art/icons/telephone.png"/ title="{t}Telephone{/t}">
		   {elseif   $telecom[0]=='email'}<img src="art/icons/email.png"/ title="{t}Email Address{/t}">
		   {elseif   $telecom[0]=='fax'}<img src="art/icons/printer.png"/ title="{t}Fax{/t}">
		   {/if}
		 </td><td class="aright" style="padding-left:10px">{$telecom[1]}</td></tr>
	       {/foreach}
	     </table>
	   </td>
	 </tr>
	 
       </table>

  
  </div>
  
 
 
      
      <table border=0 cellpadding="2" style="float:right;margin-top:20px;margin-bottom:10px;display:none" class="view_options">
	  <tr style="border-bottom:1px solid #ddd">
	    <th><img src="art/icons/information.png" title="{t}Supplier Details{/t}"/></th>
	    <th><img src="art/icons/bricks.png" title="{t}Supplier Products{/t}"/></th>
	    <th><img src="art/icons/page_paste.png" title="{t}Purchase Orders{/t}"/></th>
	    <th><img src="art/icons/script.png" title="{t}History{/t}"/></th>
	  </tr>
	  <tr style="height:18px;border-bottom:1px solid #ddd">
	    <td  id="change_view_details"  state="{$display.details}" block="details"  
		 {if $display.details==0}title="{t}Show Supplier Details{/t}" atitle="{t}Hide Supplier Details{/t}"{else}atitle="Hide Supplier Details"  title="{t}Hide Supplier Details{/t}"{/if} >
	      <img {if $hide.details==0}style="opacity:0.2"{/if} src="art/icons/tick.png"  id="but_logo_details"  /></td>

	    <td  id="change_view_products" state="{$display.products}" block="products"  
		 {if $display.products==0} title="{t}Show Products{/t}" atitle="{t}Hide Products{/t}"{else} atitle="{t}Show Products{/t}" title="{t}Hide Products{/t}"{/if} >
	      <img {if $display.products==0}style="opacity:0.2"{/if} src="art/icons/tick.png"  id="but_logo_products"  /></td>
	    
	    <td  state="{$display.po}" block="po"  id="change_view_po" 
		 {if $display.po==0}title="{t}Show the Purchase Orders{/t}" atitle="{t}Hide the Purchase Orders{/t}" {else} atitle="{t}Show the Purchase Orders{/t}" title="{t}Hide the Purchase Orders{/t}" {/if} >
	  <img {if $display.po==0}style="opacity:0.2"{/if} src="art/icons/tick.png"    id="but_logo_po"   /></td>
	    
	    <td  state="{$display.history}" block="history"   id="change_view_history" {if $display.history==0}title="{t}Show History{/t}" atitle="{t}Hide History{/t}"{else}atitle="{t}Show History{/t}" title="{t}Hide History{/t}"{/if} ><img {if $display.history==0}style="opacity:0.2"{/if} src="art/icons/tick.png"    id="but_logo_history"   /></td>
	    
	
	  </tr>
	</table>
      
      

       <div  id="info"  style="{if !$show_details}display:none;{/if};clear:left"   >
	 <h2 style="font-size:150%;">{t}Supplier Details{/t}</h2>
	
	<div style="clear:both">
	<div style="width:300px;float:left">
  <table    class="show_info_product">

    <tr >
      <td>{t}Code{/t}:</td><td class="price">{$supplier->get('Supplier Code')}</td>
    </tr>
    <tr >
      <td>{t}Name{/t}:</td><td>{$supplier->get('Supplier Name')}</td>
    </tr>
  <tr >
      <td>{t}Location{/t}:</td><td>{$supplier->get('Supplier Location')}</td>
    </tr>
    <tr >
      <td>{t}Email{/t}:</td><td>{$supplier->get('Supplier Main XHTML Email')}</td>
    </tr> 
    
</table>
 
</div>
	<div style="width:320px;margin-left:10px;float:left">
	 	<table    class="show_info_product"     >
		  <tr>
		    <td>{t}Items availeable{/t}:</td><td class="aright">{$supplier->get('Supplier Active Supplier Products')} </td>
		  </tr>
		  <tr>
		    <td>{t}Items no longer availeable{/t}:</td><td class="aright">{$supplier->get('Supplier Discontinued Supplier Products')} </td>

		  </tr>
		</table>

		<table    class="show_info_product"  >
		  <tr>
		    <td>{t}Total Sales{/t}:</td><td class="aright">{$supplier->get('Total Parts Sold Amount')} </td>
		  </tr>
		  <tr>
		    <td>{t}Total Profit{/t}:</td><td class="aright">{$supplier->get('Total Parts Profit')} </td>
		  </tr>
		  <tr>
		    <td>{t}Stock Value{/t}:</td><td class="aright">{$supplier->get('Stock Value')} </td>
		  </tr>
		  
		</table>
</div>
<div style="{if !$show_details}display:none;{/if};clear:both"  id="plot"></div>
</div>
       </div>
       



  <div  id="block_pending" class="data_table" style="margin:25px 0px;">
	 <span class="clean_table_title">{t}Pending Orders{/t}</span>
	 <div  class="clean_table_caption"  style="clear:both;">
	   <div style="float:left;"><div id="table_info4" class="clean_table_info"><span id="rtext4"></span> <span  id="rtext_rpp4" class="rtext_rpp"></span> <span class="filter_msg"  id="filter_msg4"></span></div></div>
	   <div class="clean_table_filter"><div class="clean_table_info"><span id="filter_name4">{$filter_name4}</span>: <input style="border-bottom:none" id='f_input4' value="{$filter_value}" size=10/><div id='f_container4'></div></div></div>
	   <div class="clean_table_controls" style="" ><div><span  style="margin:0 5px" id="paginator4"></span></div></div>
	 </div>
	 <div  id="table4"   class="data_table_container dtable btable "> </div>
       </div>
      
       <div  id="block_products" class="data_table" style="{if $display.products==0}display:none;{/if}margin:25px 0px;clear:both">
	 <div class="data_table" >
	   <span class="clean_table_title">{t}Supplier Products{/t}</span>
	   <table style="position:relative;bottom:4px;float:left;margin:0 0 0px 20px ;padding:0;"  class="options" {if $products==0 }style="display:none"{/if}>
	     <tr>
	       <td {if $products_view=='product_general'}class="selected"{/if} id="product_general" >{t}General{/t}</td>
	       <td {if $products_view=='product_stock'}class="selected"{/if}  id="product_stock"  >{t}Stock{/t}</td>
	       <td {if $products_view=='product_sales'}class="selected"{/if}  id="product_sales"  >{t}Sales{/t}</td>
	       <td {if $products_view=='product_forecast'}class="selected"{/if}  id="product_forecasr"  >{t}Forecast{/t}</td>
	     </tr>
	   </table>
	   <table style="position:relative;bottom:4px;clear:none;float:left;margin:0 0 0 20px ;padding:0"  class="options_mini" {if $parts==0 }style="display:none"{/if}>
	     <tr>
	       <td {if $products_period=='all'}class="selected"{/if} id="product_period_all" >{t}All{/t}</td>
	       <td {if $products_period=='year'}class="selected"{/if}  id="product_period_year"  >{t}1Yr{/t}</td>
	       <td {if $products_period=='quarter'}class="selected"{/if}  id="product_period_quarter"  >{t}1Qtr{/t}</td>
	       <td {if $products_period=='month'}class="selected"{/if}  id="product_period_month"  >{t}1M{/t}</td>
	       <td {if $products_period=='week'}class="selected"{/if}  id="product_period_week"  >{t}1W{/t}</td>
	     </tr>
	   </table>
	   <div  class="clean_table_caption"  style="clear:both;">
	     <div style="float:left;"><div id="table_info0" class="clean_table_info"><span id="rtext0"></span> <span  id="rtext_rpp0" class="rtext_rpp"></span> <span class="filter_msg"  id="filter_msg0"></span></div></div>
	     <div class="clean_table_filter"  id="clean_table_filter0"><div class="clean_table_info"><span id="filter_name0">{$filter_name0}</span>: <input style="border-bottom:none" id='f_input0' value="{$filter_value0}" size=10/><div id='f_container0'></div></div></div>
	     <div class="clean_table_controls" style="" ><div><span  style="margin:0 5px" id="paginator0"></span></div></div>
	   </div>
	   <div  id="table0"   class="data_table_container dtable btable "> </div>
	 </div>
       </div>
       
<div style="border-bottom:0px solid #ccc;width:100%;text-align:right">
<span id="pos" class="state_details {if $orders_view=='pos'}selected{/if}" style="margin-right:25px">{t}Purchase Orders{/t}</span>
<span id="dns" class="state_details {if $orders_view=='dns'}selected{/if}"style="margin-right:25px">{t}Delivery Notes{/t}</span>
<span id="invoices" class="state_details {if $orders_view=='invoices'}selected{/if}"> {t}Invoices{/t}</span>
</div>


       <div  id="block_pos" class="data_table" style="margin:5px 0px 25px 0;{if $orders_view!='pos'}display:none{/if}">
	 <span class="clean_table_title">{t}Purchase Orders{/t}</span>
	 <div  class="clean_table_caption"  style="clear:both;">
	   <div style="float:left;"><div id="table_info1" class="clean_table_info"><span id="rtext1"></span> <span class="rtext_rpp" id="rtext_rpp1"></span> <span class="filter_msg"  id="filter_msg1"></span></div></div>
	   <div class="clean_table_filter"><div class="clean_table_info"><span id="filter_name1">{$filter_name}</span>: <input style="border-bottom:none" id='f_input1' value="{$filter_value}" size=10/><div id='f_container1'></div></div></div>
	   <div class="clean_table_controls" style="" ><div><span  style="margin:0 5px" id="paginator1"></span></div></div>
	 </div>
	 <div  id="table1"   class="data_table_container dtable btable "> </div>
       </div>

       <div  id="block_dns" class="data_table" style="margin:5px 0px 25px 0;{if $orders_view!='dns'}display:none{/if}">
	 <span class="clean_table_title">{t}Supplier Delivery Notes{/t}</span>
	 <div  class="clean_table_caption"  style="clear:both;">
	   <div style="float:left;"><div id="table_info3" class="clean_table_info"><span id="rtext3"></span> <span class="rtext_rpp" id="rtext_rpp3"></span> <span class="filter_msg"  id="filter_msg3"></span></div></div>
	   <div class="clean_table_filter"><div class="clean_table_info"><span id="filter_name3">{$filter_name}</span>: <input style="border-bottom:none" id='f_input3' value="{$filter_value}" size=10/><div id='f_container3'></div></div></div>
	   <div class="clean_table_controls" style="" ><div><span  style="margin:0 5px" id="paginator3"></span></div></div>
	 </div>
	 <div  id="table3"   class="data_table_container dtable btable "> </div>
       </div>

     <div  id="block_invoices" class="data_table" style="margin:5px 0px 25px 0;{if $orders_view!='invoices'}display:none{/if}">
	 <span class="clean_table_title">{t}Supplier Invoices{/t}</span>
	 <div  class="clean_table_caption"  style="clear:both;">
	   <div style="float:left;"><div id="table_info4" class="clean_table_info"><span id="rtext4"></span> <span class="rtext_rpp" id="rtext_rpp4"></span> <span class="filter_msg"  id="filter_msg4"></span></div></div>
	   <div class="clean_table_filter"><div class="clean_table_info"><span id="filter_name4">{$filter_name}</span>: <input style="border-bottom:none" id='f_input4' value="{$filter_value}" size=10/><div id='f_container4'></div></div></div>
	   <div class="clean_table_controls" style="" ><div><span  style="margin:0 5px" id="paginator4"></span></div></div>
	 </div>
	 <div  id="table4"   class="data_table_container dtable btable "> </div>
       </div>


<div  id="block_history" class="data_table" style="{if $display.history==0}display:none;{/if}margin:25px 0px;">
  <span class="clean_table_title">{t}History{/t}</span>
  <div  class="clean_table_caption"  style="clear:both;">
    <div style="float:left;"><div id="table_info2" class="clean_table_info">{$table_info} <span class="filter_msg"  id="filter_msg2"></span></div></div>
    <div class="clean_table_filter"><div class="clean_table_info"><span id="filter_name2">{$filter_name}</span>: <input style="border-bottom:none" id='f_input2' value="{$filter_value}" size=10/><div id='f_container2'></div></div></div>
    <div class="clean_table_controls" style="" ><div><span  style="margin:0 5px" id="paginator"></span></div></div>
  </div>
  <div  id="table2"   class="data_table_container dtable btable "> </div>
</div>



    </div>
  </div>
    <div class="yui-b">
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


<div id="filtermenu1" class="yuimenu">
  <div class="bd">
    <ul class="first-of-type">
      <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Filter options{/t}:</li>
      {foreach from=$filter_menu1 item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_filter('{$menu.db_key}','{$menu.label}',0)"> {$menu.menu_label}</a></li>
      {/foreach}
    </ul>
  </div>
</div>

<div id="rppmenu1" class="yuimenu">
  <div class="bd">
    <ul class="first-of-type">
       <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Rows per Page{/t}:</li>
      {foreach from=$paginator_menu1 item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_rpp({$menu},0)"> {$menu}</a></li>
      {/foreach}
    </ul>
  </div>
</div>

<div id="filtermenu2" class="yuimenu">
  <div class="bd">
    <ul class="first-of-type">
      <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Filter options{/t}:</li>
      {foreach from=$filter_menu2 item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_filter('{$menu.db_key}','{$menu.label}',0)"> {$menu.menu_label}</a></li>
      {/foreach}
    </ul>
  </div>
</div>

<div id="rppmenu2" class="yuimenu">
  <div class="bd">
    <ul class="first-of-type">
       <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Rows per Page{/t}:</li>
      {foreach from=$paginator_menu2 item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_rpp({$menu},0)"> {$menu}</a></li>
      {/foreach}
    </ul>
  </div>
</div>


{include file='footer.tpl'}


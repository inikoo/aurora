{include file='header.tpl'}
<div id="bd" >
<span class="nav2"><a href="suppliers.php">{t}Suppliers{/t}</a></span>
<span class="nav2"><a href="suppliers.php">{t}Purchase Orders{/t}</a></span>
<span class="nav2"><a href="suppliers.php">{t}Delivery Notes{/t}</a></span>
<span class="nav2"><a href="suppliers.php">{t}History{/t}</a></span>
 <div id="yui-main">
    <div class="yui-b" style="padding:0 20px">
      <div class="search_box" >
	<table border=0 cellpadding="2" style="float:right;margin-top:20px;margin-bottom:10px;" class="view_options">
	  <tr style="border-bottom:1px solid #ddd">
	    <th><img src="art/icons/information.png" title="{t}Supplier Details{/t}"/></th>
	    <th><img src="art/icons/bricks.png" title="{t}Products{/t}"/></th>
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
	<table >
	  <tr><td  style="text-align:right"><a href="edit_supplier.php?id={$supplier_id}">Edit Supplier</a></td></tr>
	  <tr><td  style="text-align:right"><span class="but" onclick="create_new_po()" >New Purchase Order</span></td></tr>
	  
	</table>
      </div>


       <h1>{$name} <span style="color:SteelBlue">{$id}</span></h1> 
       <table style="width:500px" border=1>
	 
	 <tr>
	   {if $principal_address}<td valign="top">{$principal_address}</td>{/if}
	   <td  valign="top">
	     <table border=0 style="padding:0">
	       {if $contact}<tr><td colspan=2>{$contact}</td ></tr>{/if}
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

       <div  id="block_details"  style="{if $display.details==0}display:none;{/if}"   >
	 <h2 style="font-size:150%">{t}Supplier Overview{/t}</h2>
	 <table style="padding:0;margin:0;border-top:1px solid black;;border-bottom:1px solid black;width:500px">
	   <tr>
	     <td>
	       {$supplier_overview}
	     </td>
	   </tr>
	 </table>
       </div>


      
<div  id="block_products" class="data_table" style="{if $display.products==0}display:none;{/if}margin:25px 0px;clear:both">
  <div class="data_table" style="">
   <span class="clean_table_title">{t}Products{/t}</span>
    <div  class="clean_table_caption"  style="clear:both;">
      <div style="float:left;"><div id="table_info0" class="clean_table_info"><span id="rtext0"></span> <span class="filter_msg"  id="filter_msg0"></span></div></div>
      <div class="clean_table_filter"  id="clean_table_filter0"><div class="clean_table_info"><span id="filter_name0">{$filter_name0}</span>: <input style="border-bottom:none" id='f_input0' value="{$filter_value0}" size=10/><div id='f_container0'></div></div></div>
      <div class="clean_table_controls" style="" ><div><span  style="margin:0 5px" id="paginator0"></span></div></div>
    </div>
    <div  id="table0"   class="data_table_container dtable btable "> </div>
 </div>
</div>

<div  id="block_po" class="data_table" style="{if $display.po==0}display:none;{/if}margin:25px 0px;">
  <span class="clean_table_title">{t}Purchase Orders{/t}</span>
  <div  class="clean_table_caption"  style="clear:both;">
    <div style="float:left;"><div id="table_info1" class="clean_table_info"><span id="rtext1"></span> <span class="filter_msg"  id="filter_msg1"></span></div></div>
    <div class="clean_table_filter"><div class="clean_table_info"><span id="filter_name1">{$filter_name}</span>: <input style="border-bottom:none" id='f_input1' value="{$filter_value}" size=10/><div id='f_container1'></div></div></div>
    <div class="clean_table_controls" style="" ><div><span  style="margin:0 5px" id="paginator1"></span></div></div>
  </div>
  <div  id="table1"   class="data_table_container dtable btable "> </div>
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


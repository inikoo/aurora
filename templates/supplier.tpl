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
	    <th><img  src="art/icons/bricks.png" title="{t}Products{/t}"/></th>
	    <th><img src="art/icons/page_paste.png" title="{t}Purchase Orders{/t}"/></th>
	    <th><img src="art/icons/script.png" title="{t}History{/t}"/></th>
	  </tr>
	  <tr style="height:18px;border-bottom:1px solid #ddd">
	    <td  id="change_view_details" 
		 {if $display.details==0}title="{t}Show Supplier Details{/t}" atitle="{t}Hide Supplier Details{/t}"{else}atitle="Hide Supplier Details"  title="{t}Hide Supplier Details{/t}"{/if} >
	      <img {if $hide.details==0}style="opacity:0.2"{/if} src="art/icons/tick.png"  id="but_logo_details"  /></td>

	    <td  id="change_view_products" state="{$display.products}" block="products"  
		 {if $display.products==0} title="{t}Show Charts{/t}" atitle="{t}Hide Charts{/t}"{else} atitle="{t}Show Charts{/t}" title="{t}Hide Charts{/t}"{/if} >
	      <img {if $display.products==0}style="opacity:0.2"{/if} src="art/icons/tick.png"  id="but_logo_products"  /></td>
	
	<td  state="{$display.po}" block="po"  id="change_view_po" 
	     {if $display.po==0}title="{t}Show the Purchase Orders{/t}" atitle="{t}Hide the Purchase Orders{/t}" {else} atitle="{t}Show the Purchase Orders{/t}" title="{t}Hide the Purchase Orders{/t}" {/if} >
	  <img {if $display.po==0}style="opacity:0.2"{/if} src="art/icons/tick.png"    id="but_logo_po"   /></td>
	
	<td  state="{$display.history}" block="history"   id="change_view_history" {if $display.history==0}title="{t}Show History{/t}" atitle="{t}Hide History{/t}"{else}atitle="{t}Show History{/t}" title="{t}Hide History{/t}"{/if} ><img {if $display.history==0}style="opacity:0.2"{/if} src="art/icons/tick.png"    id="but_logo_history"   /></td>

	
	  </tr>
	</table>
	<table >
	  <tr><td  style="text-align:right"><a href="edit_supplier.php?id={$supplier_id}">Edit Supplier</a></td></tr>
	  <tr><td  style="text-align:right"><a href="porder.php?id=new&supplier_id={$supplier_id}">New Purchase Order</a></td></tr>
	  
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

       <div >
  <h2 style="font-size:150%">{t}Supplier Overview{/t}</h2>
  <table style="padding:0;margin:0;border-top:1px solid black;;border-bottom:1px solid black;width:500px">
    <tr>
      <td>
	{$supplier_overview}
      </td>
    </tr>
  </table>
</div>


      
<div  id="block_products" class="data_table" style="{if $display.products==0}display:none;{/if}margin:25px 0px;">
  <span class="clean_table_title">{t}Products{/t}</span>
  <div  class="clean_table_caption"  style="clear:both;">
    <div style="float:left;"><div id="table_info0" class="clean_table_info">{$table_info} <span class="filter_msg"  id="filter_msg0"></span></div></div>
    <div class="clean_table_filter"><div class="clean_table_info"><span id="filter_name0">{$filter_name}</span>: <input style="border-bottom:none" id='f_input0' value="{$filter_value}" size=10/><div id='f_container'></div></div></div>
    <div class="clean_table_controls" style="" ><div><span  style="margin:0 5px" id="paginator"></span></div></div>
  </div>
  <div  id="table0"   class="data_table_container dtable btable "> </div>
</div>

<div  id="block_products" class="data_table" style="{if $display.po==0}display:none;{/if}margin:25px 0px;">
  <span class="clean_table_title">{t}Purchase Orders{/t}</span>
  <div  class="clean_table_caption"  style="clear:both;">
    <div style="float:left;"><div id="table_info1" class="clean_table_info">{$table_info} <span class="filter_msg"  id="filter_msg1"></span></div></div>
    <div class="clean_table_filter"><div class="clean_table_info"><span id="filter_name1">{$filter_name}</span>: <input style="border-bottom:none" id='f_input1' value="{$filter_value}" size=10/><div id='f_container'></div></div></div>
    <div class="clean_table_controls" style="" ><div><span  style="margin:0 5px" id="paginator"></span></div></div>
  </div>
  <div  id="table1"   class="data_table_container dtable btable "> </div>
</div>

<div  id="block_products" class="data_table" style="{if $display.history==0}display:none;{/if}margin:25px 0px;">
  <span class="clean_table_title">{t}History{/t}</span>
  <div  class="clean_table_caption"  style="clear:both;">
    <div style="float:left;"><div id="table_info2" class="clean_table_info">{$table_info} <span class="filter_msg"  id="filter_msg2"></span></div></div>
    <div class="clean_table_filter"><div class="clean_table_info"><span id="filter_name2">{$filter_name}</span>: <input style="border-bottom:none" id='f_input2' value="{$filter_value}" size=10/><div id='f_container'></div></div></div>
    <div class="clean_table_controls" style="" ><div><span  style="margin:0 5px" id="paginator"></span></div></div>
  </div>
  <div  id="table2"   class="data_table_container dtable btable "> </div>
</div>



    </div>
  </div>
    <div class="yui-b">
    </div>

</div> 
{include file='footer.tpl'}


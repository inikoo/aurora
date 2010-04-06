{include file='header.tpl'}
<div id="bd" >
  <span class="nav2 onleft"><a class="selected" id="warehouse_operations" href="warehouse_orders.php">{t}Warehouse Operations{/t}</a></span>


 
<div style="height:40px">
   <table  style="float:left;margin:0 0 0 0px ;padding:0;clear:left"  class="options_mini" >
     <tr  id="orders_show_only"  style="display:{if $view!='orders'}none{/if}"  >
       <td  style="margin-right:20px; ;"  >{t}All{/t}</td>
       <td style="width:10px;border:none;cursor:default"></td>
       <td  style="" {if $view=='ready_to_pick'}class="selected"{/if}  id="ready_to_pick"  >{t}Ready to Pick{/t}</td>
       <td  style="" {if $view=='picking'}class="selected"{/if}  id="picking"  >{t}Picking in Progress{/t}</td>
       <td  style="" {if $view=='picked'}class="selected"{/if}  id="cancelled"  >{t}Picked, ready to Pack{/t}</td>
       <td  style="" {if $view=='packed'}class="selected"{/if}  id="cancelled"  >{t}Packed, waiting Approval{/t}</td>
       <td  style="" {if $view=='ready_to_dispatch'}class="selected"{/if}  id="cancelled"  >{t}Ready to Dispach{/t}</td>
       

     </tr>


   </table>
</div>
 
  

  

  <div  id="orders_table" class="data_table" style="{$view!='ready_to_pick'}display:none{/if};clear:left">
    <span class="clean_table_title">{t}Ready to Pick{/t}</span>
    <div  class="clean_table_caption"  style="clear:both;">
      <div style="float:left;"><div id="table_info0" class="clean_table_info"><span id="rtext0"></span> <span class="rtext_rpp" id="rtext_rpp0"></span> <span class="filter_msg"  id="filter_msg0"></span></div></div>
      <div class="clean_table_filter"  id="clean_table_filter0"><div class="clean_table_info"><span id="filter_name0"  class="filter_name">{$filter_name0}</span>: <input style="border-bottom:none" id='f_input0' value="{$filter_value0}" size=10/><div id='f_container0'></div></div></div>
      <div class="clean_table_controls" style="" ><div><span  style="margin:0 5px" id="paginator0"></span></div></div>
    </div>
    <div  id="table0"   class="data_table_container dtable btable "> </div>
  </div>
  
   <div  id="invoices_table"   class="data_table" style="{if $view!='invoices'}display:none{/if};clear:left">
    <span class="clean_table_title">{t}Invoice List{/t}</span>
    <div  class="clean_table_caption"  style="clear:both;">
      <div style="float:left;"><div id="table_info1" class="clean_table_info"><span id="rtext1"></span> <span class="rtext_rpp" id="rtext_rpp1"></span <span class="filter_msg"  id="filter_msg1"></span></div></div>
      <div class="clean_table_filter"  id="clean_table_filter1"><div class="clean_table_info"><span id="filter_name1" class="filter_name">{$filter_name1}</span>: <input style="border-bottom:none" id='f_input1' value="{$filter_value1}" size=10/><div id='f_container1'></div></div></div>
      <div class="clean_table_controls" style="" ><div><span  style="margin:0 5px" id="paginator1"></span></div></div>
    </div>
    <div  id="table1"   class="data_table_container dtable btable "> </div>
  </div>

 <div   id="dn_table"  class="data_table" style="{if $view!='dn'}display:none{/if};clear:left">
    <span class="clean_table_title">{t}Delivery Note List{/t}</span>
    <div  class="clean_table_caption"  style="clear:both;">
      <div style="float:left;"><div id="table_info2" class="clean_table_info"><span id="rtext2"></span> <span class="rtext_rpp" id="rtext_rpp2"></span <span class="filter_msg"  id="filter_msg2"></span></div></div>
      <div class="clean_table_filter"  id="clean_table_filter2"><div class="clean_table_info"><span id="filter_name2" class="filter_name" >{$filter_name2}</span>: <input style="border-bottom:none" id='f_input2' value="{$filter_value2}" size=10/><div id='f_container2'></div></div></div>
      <div class="clean_table_controls" style="" ><div><span  style="margin:0 5px" id="paginator2"></span></div></div>
    </div>
    <div  id="table2"   class="data_table_container dtable btable "> </div>
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


<div id="pick_it_dialog">
{foreach from=$pickers_list item=picker}
<div></div>
{/foreach}

<table class="edit">
<tr class="first"><td style="" class="label">{t}Staff Name{/t}:</td>
   <td  style="text-align:left">
     <div  style="width:15em;position:relative;top:00px" >
       <input style="text-align:left;width:18em" id="Staff_Name" value="" ovalue="" valid="0">
       <div id="Staff_Name_Container" style="" ></div>
     </div>
   </td>
   <td id="Staff_Name_msg" class="edit_td_alert"></td>
 </tr>
</table>

<table class="edit">
  <tr><td>{t}PIN{/t}:</td><td><input /></td></tr>
  <tr><td colspan="2"><span class="button">Cancel</span><span class="button">Go</span><td></tr>
</table>

<div>

{include file='header.tpl'}
<div id="bd" >
  <span class="nav2 onleft"><a class="selected" id="warehouse_operations" href="warehouse_orders.php">{t}Warehouse Operations{/t}</a></span>


 


  

  <div  id="orders_table" class="data_table" style="clear:left">
    <span class="clean_table_title">{t}Ready to Pick{/t}</span>
    <div  class="clean_table_caption"  style="clear:both;">
      <div style="float:left;"><div id="table_info0" class="clean_table_info"><span id="rtext0"></span> <span class="rtext_rpp" id="rtext_rpp0"></span> <span class="filter_msg"  id="filter_msg0"></span></div></div>
      <div class="clean_table_filter"  id="clean_table_filter0"><div class="clean_table_info"><span id="filter_name0" class="filter_name"   class="filter_name">{$filter_name0}</span>: <input style="border-bottom:none" id='f_input0' value="{$filter_value0}" size=10/><div id='f_container0'></div></div></div>
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

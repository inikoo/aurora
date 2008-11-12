{include file='header.tpl'}
<div id="bd" >

 <div id="top" class="top_bar">
    <div id="short_menu" class="nodetails" style="{if $show_details}display:none;{/if}width:100%;margin-bottom:0px">
      <table style="float:left;margin:0 0 0 20px ;padding:0"  class="options" >
	<tr>
	  <td {if $view=='staff'}class="selected"{/if}  id="staff"  >{t}Staff{/t}</td>
	  <td  {if $view=='exstaff'}class="selected"{/if}  id="exstaff"  >{t}Ex-staff{/t}</td>
	  <td  {if $view=='all'}class="selected"{/if} id="all" >{t}Everybody{/t}</td>
	  <td style="padding:0;font-weight:100;color:#777;padding:0 0 0 5px;cursor:default;;border:none"><span  class="state_details"  id="show_details">{t}show details{/t}</span></td>
	</tr>
      </table>
    </div>
    
    
<div class="data_table" style="margin-top:25px">
  <span class="clean_table_title">{t}{$table_title}{/t}</span>
  <div  class="clean_table_caption"  style="clear:both;">
    <div style="float:left;"><div class="clean_table_info"><span id="rtext0"></span> <span class="filter_msg"  id="filter_msg0"></span></div></div>
    <div class="clean_table_filter"id="clean_table_filter0" ><div class="clean_table_info" id="filter_name0">{$filter_name}: <input style="border-bottom:none" id='f_input0' value="{$filter_value}" size=10/><div id='f_container'></div></div></div>
    <div class="clean_table_controls" style="" ><div id="xxx" ><span  style="margin:0 5px" id="paginator0"></span></div></div>
  </div>
  <div  id="table0"   class="data_table_container dtable btable "> </div>
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

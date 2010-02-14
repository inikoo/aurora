{include file='header.tpl'}
<div id="bd" >
{include file='assets_navigation.tpl'}
 

<div id="no_details_title" style="clear:left;{if $show_details}display:none;{/if}">
    <h1>{t}Store Deals{/t}: {$store->get('Store Name')} ({$store->get('Store Code')})</h1>
  </div>
<div id="info" style="clear:left;margin:20px 0 10px 0;padding:0;{if !$show_details}display:none;{/if}">

<h2 style="margin:0;padding:0">{t}Deals Information{/t}</h2>


</div>

<div class="data_table" style="clear:both;">
    <span   class="clean_table_title">{t}Deals{/t}</span>
    <div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999;margin-bottom:5px"></div>
    
    
    
   
    <div  class="clean_table_caption"  style="clear:both;">
      <div style="float:left;"><div id="table_info0" class="clean_table_info"><span id="rtext0"></span> <span class="rtext_rpp" id="rtext_rpp0"></span> <span class="filter_msg"  id="filter_msg0"></span></div></div>
      

      <div class="clean_table_filter" id="clean_table_filter0">
	
	<div class="clean_table_info" style="width:10.2em;padding-bottom:1px;" >
	  <span id="filter_name0" style="margin-right:5px">{$filter_name}:</span>
	  <input style="border-bottom:none;width:6em;border-bottom:none" id='f_input0' value="{$filter_value}" size=10/>
	  <div id='f_container0'></div>
	</div>
      </div>
      <div class="clean_table_controls" style="" ><div><span  style="margin:0 5px" id="paginator0"></span></div></div>
    </div>
    <div  id="table0"   class="data_table_container dtable btable with_total"> </div>
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

{include file='footer.tpl'}

{include file='header.tpl'}
<div id="bd" >
{include file='reports_navigation.tpl'}

  <div id="no_details_title"  style="clear:left;xmargin:0 20px;{if $details!=0}display:none{/if}">
    <h1>Modelo 347</h1>
  </div>
  <div id="info"  style="clear:left;margin-top:10px;padding:0 0px;width:770px;{if $details==0}display:none{/if}"></div>

    
    <div id="the_table" class="data_table" style="clear:both">
      <span class="clean_table_title">{t}Customers{/t}</span>
      
      
     <div id="table_type">
         <a  style="float:right"  class="table_type state_details"  href="report_tax_ES1_csv.php" >{t}Export (CSV){/t}</a>

     </div>
     
      
       <div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999"></div>
  

      <div  class="clean_table_caption"  style="clear:both;margin-top:10px">
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

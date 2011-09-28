{include file='header.tpl'}
<div id="bd" >
 
{include file='contacts_navigation.tpl'}
<div> 
  <span class="branch">{t}Marketing{/t}</span>
</div>
  
  <div style="clear:left;">
    <h1>{t}Marketing Corporate Overview{/t}</h1>
  </div>





<div class="data_table" style="clear:both">
    <span class="clean_table_title">{t}Store Marketing Sections{/t}</span>

 <div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999"></div>



<table style="float:left;margin:0 0 0 0px ;padding:0;margin-bottom:10px"  class="options" >
	
      </table>



       
{include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0  no_filter=1} 
<div  id="table0"   class="data_table_container dtable btable with_total"> </div>		
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

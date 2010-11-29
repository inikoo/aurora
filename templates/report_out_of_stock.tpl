{include file='header.tpl'}
<div id="bd" >
{include file='reports_navigation.tpl'}
{include file='calendar_splinter.tpl'}

<h1 style="clear:left">{$title}</h1>





<div id="the_table" class="data_table" style="clear:both">
  <span class="clean_table_title">Product Marked as Out of Stock</span>
<div style="float:right"><span class="state_details" id="export" output="{$export.type}" >{$export.label}</span></div>
 
  
  {include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0}
      <div  id="table0"   class="data_table_container dtable btable "> </div>
    </div>
</div>
{include file='footer.tpl'}

<div id="export_menu" class="yuimenu" >
  <div class="bd">
    <ul class="first-of-type">
     
      {foreach from=$export_menu key=key item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_export_type('export','{$key}','{$menu.label}')"> {$menu.title}</a></li>
      {/foreach}
    </ul>
  </div>
</div>



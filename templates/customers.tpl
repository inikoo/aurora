{include file='header.tpl'}
<div id="bd" >
  <span class="nav2 onright"><a href="contacts.php">{t}List of contacts{/t}</a></span>
  <div id="yui-main">
    <div class="yui-b" style="text-align:right;float:right">
      {include file='customer_search.tpl'}
    </div>
    <div class="yui-b">
      <h1>{t}Our Dear Customers{/t}</h1>
      <p style="width:475px">{$overview_text}</p>
      <p style="width:475px">{$top_text}</p>
      <p style="width:475px">{$export_text}</p>
      
      <div class="data_table" style="margin-top:25px">
	<span class="clean_table_title">{t}{$table_title}{/t}</span>
	<div  class="clean_table_caption"  style="clear:both;">
	  <div style="float:left;"><div class="clean_table_info">{$table_info} <span class="filter_msg"  id="filter_msg0"></span></div></div>
	  <div class="clean_table_filter"><div class="clean_table_info">{$filter_name}: <input style="border-bottom:none" id='f_input0' value="{$filter_value}" size=10/><div id='f_container'></div></div></div>
	  <div class="clean_table_controls" style="" ><div><span  style="margin:0 5px" id="paginator"></span></div></div>
	</div>
	<div  id="table0"   class="data_table_container dtable btable "> </div>
      </div>


    </div>
  </div>
</div>
</div> 
{include file='footer.tpl'}

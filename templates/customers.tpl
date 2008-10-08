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
      {include file='table.tpl' table_id=0 table_title=$table_title filter=$filter filter_name=$filter_name}
      	<div  id="table0"   class="data_table_container dtable btable "> </div>

</div>
    </div>
  </div>
  </div>
</div> 
{include file='footer.tpl'}

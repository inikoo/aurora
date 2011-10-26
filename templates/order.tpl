{include file='header.tpl'}
<div id="bd" >
  <div id="yui-main">
    <div class="yui-b">
      <h2>{t}Orders{/t}</h2>
      <div class="yui-b" style="border:1px solid #ccc;text-align:left;margin:0px;padding:10px;height:60px;margin: 0 0 10px 0">
	<div style="float:right;border: 0px solid #ddd;text-align:right">
	  <form  id="prod_search_form" action="assets_index.php" method="GET" >
	    <label>{t}Order Search{/t}:</label><input size="12" class="text search" id="prod_search" value="" name="name"/><img onclick="document.getElementById('prod_search_form').submit()"align="absbottom" id="submit_search" class="submitsearch" src="art/icons/zoom.png" alt="Submit search"/>
	  </form>
	<br/>
	<img align="absbottom" src="art/icons/calendar_view_month.png"/> <input type="text" class="text" size="8" value=""/> {t}to{/t} <input type="text" class="text" size="8" value=""/>
	</div>
	</div>
      {include file='table.tpl' table_id=0 table_title=$table_title filter=$filter filter_name=$filter_name}
    </div>
  </div>
</div>
</div> 
{include file='footer.tpl'}

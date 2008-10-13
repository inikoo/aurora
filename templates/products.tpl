{include file='header.tpl'}
<div id="bd" >
  <span class="nav2"><a href="assets_tree.php">{$home}</a></span>
  <div id="yui-main">
    <div class="yui-b">
      <h2>{t}Product Index{/t}</h2>
      {include file='table.tpl' table_id=0 table_title='Products' filter=$filter filter_name=$filter_name}
    </div>
  </div>
  <div class="yui-b"></div>
</div> 
{include file='footer.tpl'}


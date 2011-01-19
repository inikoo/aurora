{include file='header.tpl'}
<div id="bd" >
 {include file='assets_navigation.tpl'}
 <div style="clear:both">
	  <h1>{$code}</h1>
	  
 <div  id="orders_table" class="data_table" style="clear:both;">
    <span class="clean_table_title">{t}Product ID List{/t}</span>
    <div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999;margin-bottom:10px"></div>
    {include file='table_splinter.tpl' table_id=2 filter_name=$filter_name2 filter_value=$filter_value2  no_filter=1} 
    <div  id="table2"   class="data_table_container dtable btable  "> </div>
  </div>	  
	  
</div>

</div>{include file='footer.tpl'}


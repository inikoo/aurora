<div class="splinter_cell double" >
<div style="float:right;width:400px">
<h1>{t}Pending Orders{/t}</h1>
<span>{$pending_orders}</span>

</div>

<div id="the_table" class="data_table" style="width:500px">
 <span class="clean_table_title">{t}Orders in Process{/t}</span>
  {include file='table_splinter.tpl' table_id=$index filter_name=$filter_name filter_value=$filter_value no_filter=1}
   <div  id="table{$index}"   class="data_table_container dtable btable "> </div>
 </div>
 </div>
<div class="splinter_cell double" >

<div id="the_table" class="data_table" style="width:500px;float:left">
 <span class="clean_table_title">{t}Orders in Process{/t}</span>
  {include file='table_splinter.tpl' table_id=$index filter_name=$filter_name filter_value=$filter_value no_filter=1}
   <div  id="table{$index}"   class="data_table_container dtable btable "> </div>
 </div>
 
 
 <div style="float:right;width:400px">
<h1>{t}Pending Orders{/t}</h1>

<div >
<h2>{t}Orders to do{/t}</h2>
<div>{$orders_in_process_data.orders}</div>
</div>

<div>
<h2>{t}Value{/t}</h2>
<div>{$orders_in_process_data.value}</div>
</div>

</div>

 
 
 </div>
 
 
 
 
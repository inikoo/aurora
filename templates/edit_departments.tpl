{include file='header.tpl'}
<div id="bd" >



 <div class="search_box" style="clear:both;margin-right:20px;margin-top:10px" >
    <span class='reset' onclick='window.location="store.php?edit=0"'   >{t}Exit{/t}</span>
 </div>
  

 
 
 
 <div   class="data_table" style="margin:25px 20px">
   <span class="clean_table_title">{t}Departments{/t}</span>
   <div  class="clean_table_caption"  style="clear:both;">
     <div style="float:left;"><div id="table_info0" class="clean_table_info"><span id="rtext0"></span> <span class="rtext_rpp" id="rtext_rpp0"></span> <span class="filter_msg"  id="filter_msg0"></span></div></div>
     <div class="clean_table_filter" style="display:none" id="clean_table_filter0"><div class="clean_table_info"><span id="filter_name0" class="filter_name" >{$filter_name0}</span>: <input style="border-bottom:none" id='f_input0' value="{$filter_value0}" size=10/><div id='f_container0'></div></div></div>
     <div class="clean_table_controls"  ><div><span  style="margin:0 5px" id="paginator0"></span></div></div>
   </div>
   <div  id="table0"   class="data_table_container dtable btable "> </div>
 </div>
 
 
</div> 
{include file='footer.tpl'}

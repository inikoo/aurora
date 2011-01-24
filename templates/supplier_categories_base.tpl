{include file='header.tpl'}
<div id="bd" >
 {include file='suppliers_navigation.tpl'}

 <div style="clear:left;">
  <h1>{t}Supplier Categories Home{/t}</h1>
</div>



<div class="data_table" style="clear:both">
    <span class="clean_table_title">{t}Main Categories{/t}</span>



 <span   style="float:right;margin-left:20px" class="state_details"  id="change_stores_mode" >{$display_stores_mode_label}</span>
 <span   style="float:right;margin-left:20px" class="state_details"  id="change_stores" >{$display_stores_label}</span>
 <span   style="float:right;margin-left:20px" class="state_details"  id="change_display_mode" >{$display_mode_label}</span>

       
    {include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0  }

       <div  id="table0"   class="data_table_container dtable btable "> </div>		
</div>



  
</div> 
{include file='footer.tpl'}
{include file='new_category_splinter.tpl'}

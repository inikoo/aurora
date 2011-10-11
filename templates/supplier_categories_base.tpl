{include file='header.tpl'}
<div id="bd" >
 {include file='suppliers_navigation.tpl'}
 <input id="category_key" type="hidden" value="0"/>

 <div style="clear:left;">
  <h1>{t}Supplier Categories Home{/t}</h1>
</div>



<div class="data_table" style="clear:both">
    <span class="clean_table_title">{t}Main Categories{/t}</span>

   <div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999"></div>


 
       
    {include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0  }

       <div  id="table0"   class="data_table_container dtable btable "> </div>		
</div>



  
</div> 
{include file='footer.tpl'}
{include file='new_category_splinter.tpl'}

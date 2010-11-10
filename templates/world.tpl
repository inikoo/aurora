{include file='header.tpl'}

<div id="bd"  >
<div class="branch" style="text-align:right;;width:300px;float:right"> 
  <span  ><span>{t}World Regions{/t} &crarr;</span> <span style="margin-left:20px">{t}Countries{/t} &crarr;</span></span>
</div>
<div class="branch" style="width:300px"> 
  <span  ><a  href="region.php?world">{t}World{/t}</span>
</div>


<div id="photo_container" style="float:left;border:0px solid #777;width:510px;height:320px">

	    <iframe id="the_map" src ="map.php?country=" frameborder="0" scrolling="no" width="550"  height="420"></iframe>
	   
	    
	    
	  </div>





     
</div>





 
      

  {if $view_orders} 
  <div  id="block_orders" class="data_table" style="{if $display.orders==0}display:none;{/if}clear:both;margin:25px 0px">
    <span id="table_title" class="clean_table_title">{t}Orders with this Product{/t}</span>
    {include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0}
    <div  id="table0"   class="data_table_container dtable btable "> </div>
  </div>
  {/if}
  
  {if $view_customers} 
  <div  id="block_customers" class="data_table" style="{if $display.customers==0}display:none;{/if}clear:both;margin:25px 0px">
    <span id="table_title" class="clean_table_title">{t}Customer who order this Product{/t}</span>
    {include file='table_splinter.tpl' table_id=1 filter_name=$filter_name1 filter_value=$filter_value1}
  <div  id="table1"   class="data_table_container dtable btable "> </div>
  </div>
  {/if}


 
</div>




</div>{include file='footer.tpl'}


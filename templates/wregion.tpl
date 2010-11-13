{include file='header.tpl'}

<div id="bd"  >
<div class="branch" style="text-align:right;;width:300px;float:right"> 
  <span  ><span>{t}World Regions{/t} &crarr;</span> 
<span style="margin-left:20px" >{t}Countries{/t} &crarr;</a></span></span>
</div>
<div class="branch" style="width:300px"> 

</div>


<div  id="block_continents" class="data_table" style="clear:both;margin:25px 0px">
    <span id="table_title" class="clean_table_title">{t}World Regions{/t}</span>
    {include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0}
    <div  id="table0"   class="data_table_container dtable btable "> </div>
  </div>  
     
<div id="photo_container" style="display:none;float:left;border:0px solid #777;width:510px;height:320px">
	    <iframe id="the_map" src ="map.php?country=" frameborder="0" scrolling="no" width="550"  height="420"></iframe>
</div>
</div>
</div>
</div>{include file='footer.tpl'}

 






{include file='header.tpl'}

<div id="map"> 
 <script type="text/javascript" src="external_libs/ammap_2.5.5/ammap/swfobject.js"></script>
	<div id="flashcontent">
		<strong>You need to upgrade your Flash Player</strong>
	</div>

	<script type="text/javascript">
		// <![CDATA[		
		var so = new SWFObject("external_libs/ammap_2.5.5/ammap/ammap.swf", "ammap", "100%", "100%", "8", "#7d96cb");
    so.addVariable("path", "external_libs/ammap_2.5.5/ammap/");
		so.addVariable("data_file", escape("external_libs/ammap_2.5.5/examples/drill_down/ammap_data.xml"));
    so.addVariable("settings_file", escape("external_libs/ammap_2.5.5/examples/drill_down/ammap_settings.xml"));		
		so.addVariable("preloader_color", "#999999");
		so.write("flashcontent");
		// ]]>
	</script></div>





<div id="bd"  >
<div class="branch" style="text-align:right;;width:300px;float:right"> 
  <span  ><span>{t}World Regions{/t} &crarr;</span> 
<span style="margin-left:20px" >{t}Countries{/t} &crarr;</a></span></span>
</div>
<div class="branch" style="width:300px"> 
  <span  ><a  href="region.php?world">{t}World{/t}</span>
</div>


     
  <div  id="block_countries" class="data_table" style="clear:both;margin:25px 0px">
    <span id="table_title" class="clean_table_title">{t}Counties{/t}</span>
    {include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0}
    <div  id="table0"   class="data_table_container dtable btable "> </div>
  </div>
  
  <div  id="block_wregions" class="data_table" style="clear:both;margin:25px 0px">
    <span id="table_title" class="clean_table_title">{t}World Regions{/t}</span>
    {include file='table_splinter.tpl' table_id=1 filter_name=$filter_name1 filter_value=$filter_value1}
    <div  id="table1"   class="data_table_container dtable btable "> </div>
  </div>  

<div  id="block_continents" class="data_table" style="clear:both;margin:25px 0px">
    <span id="table_title" class="clean_table_title">{t}Continents{/t}</span>
    {include file='table_splinter.tpl' table_id=2 filter_name=$filter_name2 filter_value=$filter_value2}
    <div  id="table2"   class="data_table_container dtable btable "> </div>
  </div>  
     
     
<div id="photo_container" style="display:none;float:left;border:0px solid #777;width:510px;height:320px">

	    <iframe id="the_map" src ="map.php?country=" frameborder="0" scrolling="no" width="550"  height="420"></iframe>
	   
	    
	    
	  </div>





     
</div>





 
      


 
</div>




</div>{include file='footer.tpl'}


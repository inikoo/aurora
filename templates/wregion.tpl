{include file='header.tpl'}
<div id="bd"  >
<div class="branch" style="width:300px"> 
  <a href="region.php?world">{t}World{/t}</a>  &rarr;  <span>{t}Region{/t} {$wregion_name} ({$wregion_code})</span>
</div>

<div style="border:1px solid #ccc;padding:10px;margin-top:5px">

<div id="map_countries" style="width:700px;height:480px;">
		<strong>You need to upgrade your Flash Player</strong>
	</div>

	
<script type="text/javascript">
		// <![CDATA[		
		var so = new SWFObject("{$ammap_path}/ammap/ammap.swf", "ammap", "100%", "100%", "8", "#FFFFFF");
        so.addVariable("path", "{$ammap_path}/ammap/");
		so.addVariable("data_file", escape("map_data_wregion_countries.xml.php?wregion={$wregion_code}"));
        so.addVariable("settings_file", escape("{$settings_file}"));		
		so.addVariable("preloader_color", "#999999");
		so.write("map_countries");
		
	
		// ]]>
	</script>
	
</div>


<div  id="block_continents" class="data_table" style="clear:both;margin:25px 0px">
    <span id="table_title" class="clean_table_title">{t}Countries{/t}</span>
    {include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0}
    <div  id="table0"   class="data_table_container dtable btable"> </div>
  </div>  
     
<div id="photo_container" style="display:none;float:left;border:0px solid #777;width:510px;height:320px">
	    <iframe id="the_map" src ="map.php?country=" frameborder="0" scrolling="no" width="550"  height="420"></iframe>
</div>
</div>
</div>
</div>{include file='footer.tpl'}

 






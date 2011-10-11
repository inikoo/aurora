 <div  class="clean_table_caption"  style="clear:both;">
	<div style="float:left;">
	  <div id="table_info{$table_id}" class="clean_table_info"><span id="rtext{$table_id}"></span> <span class="rtext_rpp" id="rtext_rpp{$table_id}"></span> <span class="filter_msg"  id="filter_msg{$table_id}"></span></div>
	</div>
	<div style="{if $no_filter==1}display:none{/if}">
	<div class="clean_table_filter clean_table_filter_show" id="clean_table_filter_show{$table_id}" {if $filter_show or $filter_value!=''}style="display:none"{/if}>{t}filter results{/t}</div>
	<div class="clean_table_filter" id="clean_table_filter{$table_id}" {if !$filter_show and $filter_value==''}style="display:none"{/if}>
	  <div class="clean_table_info" style="padding-bottom:1px; ">
	    <span id="filter_name{$table_id}" class="filter_name"  style="margin-right:5px">{$filter_name}:</span>
	    <input style="border-bottom:none;width:6em;" id='f_input{$table_id}' value="{$filter_value}" size=10/> <span class="clean_table_filter_show" id="clean_table_filter_hide{$table_id}" style="margin-left:8px">{t}Close filter{/t}</span>
	    <div id='f_container{$table_id}'></div>
	  </div>
	</div>	
	</div>
	<div class="clean_table_controls"  >
	    <div><span  style="margin:0 5px" id="paginator{$table_id}"></span></div>
	 </div>
</div>

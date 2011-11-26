{include file='header.tpl'}
<div id="bd" style="padding:0px">
 <div id="search" style="border:0px solid black;margin:auto;text-align:center;padding:10px;margin:20px ">
   
    <input size="45" class="text" id="all_search" value="{$query}" state="" name="search"/> <span id="submit_search" class="button" >{t}Search{/t}</span>
   
    <div id="all_search_Container" style="display:none"></div>
    <div style="position:relative;font-size:80%">
      <div id="all_search_results" style="display:none;position:absolute;background:#fff;border:1px solid #777;padding:10px;margin-top:0px;width:720px;z-index:20;left:100px;">
	<table id="all_search_results_table"></table>
      </div>
    </div>
  </div>

<div style="border-bottom:1px solid #ccc">

</div>

<div style="padding:20px">
			  <div id="the_table0" class="data_table" style="margin:0px 0px;clear:both">
    <span class="clean_table_title">{t}Results{/t}</span>
{include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0 no_filter=1 }
    <div  id="table0"   class="data_table_container dtable btable main_search"> </div>
  </div>
</div>
		
	
	
</div>
{include file='footer.tpl'}

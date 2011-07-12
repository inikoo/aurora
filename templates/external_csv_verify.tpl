{include file='header.tpl'}
{include file='contacts_navigation.tpl'}
<input type="hidden" id="scope" value="{$scope}">
<input type="hidden" id="scope_key" value="{$scope_key}">


<div id="bd">
<div id="no_details_title" style="clear:left;">
    <h1>{t}Import Contacts From CSV File{/t}</h1>
</div>
<div class="left3Quarters" style="text-align:right">
    <input type="hidden" name="form" value="form" />
    <div class="framedsection">
        <div id="call_table"></div>
    </div>
    <span class="button" id="insert_data" style="margin-right:20px">{t}Insert data{/t}</span>	
</div>
</div>



<div id="dialog_map">
  <div id="map_msg"></div>
  <table style="padding:10px;margin:20px" >
 
  <tr><td colspan=2>{t}Please write the map name{/t}</td></tr>
    <tr><td colspan=2>
	<input  id="map_name" />
      </td>
    <tr>
    <tr class="buttons" style="font-size:100%;display:block;margin-top:10px">
  <td style="text-align:center;width:50%">
   
  <td style="text-align:center;width:50%">
    <span   id="save_map"  class="unselectable_text button"   >{t}Save{/t}</span></td></tr>
</table>
</div>


 <div id="dialog_map_select">
    <div class="splinter_cell" style="padding:10px 15px 10px 0;border:none">
        <div id="the_table" class="data_table" >
            <span class="clean_table_title">{t}Map List{/t}</span>
            {include file='table_splinter.tpl' table_id=5 filter_name=$filter_name5 filter_value=$filter_value5}
            <div  id="table5"   class="data_table_container dtable btable "> </div>
        </div>
    </div>
 </div>
 
 
{include file='footer.tpl'}

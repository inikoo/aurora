{include file='header.tpl'}
<div style="display:none; position:absolute; left:10px; top:200px; z-index:2" id="cal1Container"></div>
<div id="bd" >

  <h1 id="wellcome" style="padding:10px 20px">{t}New Company Area{/t}</h1>
  
  

  <div id="block_individual"  style="margin:0px 20px;clear:both;">
    <div style="float:left;padding:20px;border:1px solid #ddd;width:400px">
      <table class="edit">
	<tr><td class="label">{t}New Company Area{/t}:</td><td><span style="font-weight:800"></td></tr>
	<tr><td class="label">{t}Area Code{/t}:</td><td><input  onKeyUp="validate_general('company_area', 'area_code', this.value)" id="area_code" ovalue=""  type="text"/></td><td id="area_code_msg"></td><td id="area_code_msg"></td></tr>
	<tr><td class="label">{t}Area Name{/t}:</td><td><input   onKeyUp="validate_general('company_area', 'area_name', this.value)" id="area_name" ovalue=""  type="text"/></td><td id="area_name_msg"></td><td id="area_name_msg"></td></tr>
	<tr><td class="label">{t}Area Description{/t}:</td><td><textarea ovalue="" id="area_description"></textarea></td><td id="area_description_msg"></td></tr>




       </table>
      
    </div>
    
    <div id="location_save_block" style="margin:0px 20px;padding:20px 20px;float:left;border:1px solid #ddd;width:300px">
      <span id="save_edit_company_area" onClick="save_new_general('company_area')" class="button">{t}Save{/t}</span>
      
      <span style="margin-right:10px" onClick="reset_edit_general('company_area')" id="reset_edit_company_area" class="button">{t}Cancel{/t}</span>

    </div>
    
    
  </div>

  <div style="clear:both"></div>
  
  <div id="the_table" class="data_table" style="margin:20px 20px 20px 20px;clear:both">
    <span class="clean_table_title">{t}New Company Areas{/t}</span>
    <div  class="clean_table_caption"  style="clear:both;">
      <div style="float:left;"><div id="table_info0" class="clean_table_info"><span id="rtext0"></span> <span class="filter_msg"  id="filter_msg0"></span></div></div>
      <div class="clean_table_filter" id="clean_table_filter0"><div class="clean_table_info"><span id="filter_name0" class="filter_name" >{$filter_name}</span>: <input style="border-bottom:none" id='f_input0' value="{$filter_value}" size=10/><div id='f_container'></div></div></div>
      <div class="clean_table_controls"  ><div><span  style="margin:0 5px" id="paginator"></span></div></div>
    </div>
    <div  id="table0"   class="data_table_container dtable btable "> </div>
  </div>
   
    



</div>
{include file='footer.tpl'}


{include file='header.tpl'}
<div id="bd" >
  <span class="nav2 onleft"><a class="selected" href="departments.php">{t}Departments{/t}</a></span>
  <span class="nav2 onleft"><a href="families.php">{t}Families{/t}</a></span>
  <span class="nav2 onleft"><a href="products.php">{t}Products{/t}</a></span>
  <span class="nav2 onleft"><a href="categories.php">{t}Categories{/t}</a></span>
  <span class="nav2 onleft"><a href="parts.php">{t}Parts{/t}</a></span>


 <div class="search_box" style="clear:both;margin-right:20px" >
    <span class='reset' onclick='window.location="store.php?edit=0"'   >{t}Exit{/t}</span>
 </div>
  

<div style="clear:left;margin-left:20px;width:700px"id="details" class="details" >
<h1><span style="color:red">{t}Editing{/t}</span> {$store->get('Store Name')} ({$store->get('Store Code')})</h1>
<h2>{t}Add new department{/t}</h2>
<div id="add_department_form">
<div id="edit_messages"></div>

  <div class="bd"> 

      <table >
	<tr><td>{t}Code{/t}:</td><td><input  id="new_code" onKeyUp="new_dept_changed(this)"    onMouseUp="new_dept_changed(this)"  onChange="new_dept_changed(this)"  name="code" changed=0 type='text' class='text' style="width:15em" MAXLENGTH="16" value="" /></td></tr>
	<tr><td>{t}Full Name{/t}:</td><td><input   id="new_name" onKeyUp="new_dept_changed(this)"    onMouseUp="new_dept_changed(this)"  onChange="new_dept_changed(this)"  name="name" changed=0 type='text'  MAXLENGTH="255" style="width:30em"  class='text' value="" /></td>
<td>
	    <span class="save" id="add_new_dept" onclick="save_new_dept()" style="display:none">Add</span>
	</td></tr>
      </table>

  </div>
</div>

</div>


  <div   class="data_table" style="margin:25px 20px">
    <span class="clean_table_title">{t}Departments{/t}</span>
    <div  class="clean_table_caption"  style="clear:both;">
      <div style="float:left;"><div id="table_info0" class="clean_table_info"><span id="rtext0"></span> <span class="rtext_rpp" id="rtext_rpp0"></span> <span class="filter_msg"  id="filter_msg0"></span></div></div>
      <div class="clean_table_filter" style="display:none" id="clean_table_filter0"><div class="clean_table_info"><span id="filter_name0">{$filter_name0}</span>: <input style="border-bottom:none" id='f_input0' value="{$filter_value0}" size=10/><div id='f_container0'></div></div></div>
      <div class="clean_table_controls" style="" ><div><span  style="margin:0 5px" id="paginator0"></span></div></div>
    </div>
    <div  id="table0"   class="data_table_container dtable btable "> </div>
  </div>

  
</div> 
{include file='footer.tpl'}

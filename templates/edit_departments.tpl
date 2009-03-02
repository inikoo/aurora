{include file='header.tpl'}
<div id="bd" >
  <span class="nav2 onleft"><a class="selected" href="departments.php">{t}Departments{/t}</a></span>
  <span class="nav2 onleft"><a href="families.php">{t}Families{/t}</a></span>
  <span class="nav2 onleft"><a href="products.php">{t}Products{/t}</a></span>
  <span class="nav2 onleft"><a href="categories.php">{t}Categories{/t}</a></span>
  <span class="nav2 onleft"><a href="parts.php">{t}Parts{/t}</a></span>


 <div class="search_box" style="clear:both;margin-right:20px" >
     <a href="departments.php?edit=0"  class="state_details" id="edit"  >{t}exit edit{/t}</a>
 </div>
  

<div style="clear:left;margin-left:20px;width:700px"id="details" class="details" >
<h1>{t}Edit Mode{/t}</h1>
<h2>{t}Add new department{/t}</h2>
<div id="add_department_form">
  <div class="bd"> 
    <form method="POST" action="ar_assets.php"> 
      <input name="tipo" type="hidden" value="new_department" />
      <table >
	<tr><td>{t}Code{/t}:</td><td><input name="code" type='text' class='text' style="width:30em" MAXLENGTH="16"/></td></tr>
	<tr><td>{t}Full Name{/t}:</td><td><input name="name" type='text'  MAXLENGTH="255" style="width:30em"  class='text' />
	    <button style="cursor:pointer">Add</button>
	</td></tr>
      </table>
    </form>
  </div>
</div>

</div>


  
  <div class="data_table" style="clear:both;margin:0px 20px">
    <span id="table_title" class="clean_table_title">{t}{$table_title}{/t}</span>
    <div  class="clean_table_caption"  style="clear:both;">
      <div style="float:left;"><div id="table_info0" class="clean_table_info">{$table_info} <span class="filter_msg"  id="filter_msg0"></span></div></div>
      <div class="clean_table_controls" style="" ><div><span  style="margin:0 5px" id="paginator">{t}Showing all Records{/t}</span></div></div>
    </div>
    <div  id="table0"   class="data_table_container dtable btable with_total"> </div>
  </div>
  
</div> 
{include file='footer.tpl'}

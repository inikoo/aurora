{include file='header.tpl'}
<div id="bd" >

{if $next.id>0}<span class="nav2 onright"><a href="assets_family.php?id={$next.id}">{t}Next{/t}</a></span>{/if}
{if $prev.id>0}<span class="nav2 onright" ><a href="assets_family.php?id={$prev.id}">{t}Previous{/t}</a></span>{/if}
<span class="nav2 onright" style="margin-left:20px"><a href="assets_tree.php">{t}Up{/t}</a></span>


<span class="nav2 onright"><a href="assets_index.php">{t}Product index{/t}</a></span>
<span class="nav2"><a href="assets_tree.php">{$home}</a></span>

  <div id="yui-main">
    <div class="yui-b">
      <h2>{$department} </h2>
      <div class="data_table" style="margin-top:25px">
	{include file='table.tpl'  hide_table=$hide_first table_id=0 table_title='Families' filter=$filter filter_name=$filter_name filter_value=$filter_value}
	<div {if $hide_first==1} style="display:none"{/if} id="table0"   class="data_table_container dtable btable {$showtable}"> </div>
	{if $view_stock}<div  {if $view_table!=1} style="display:none"{/if}  id="table1" class="data_table_container dtable btable  "></div>{/if}
	{if $view_sales} <div  {if $view_table!=2} style="display:none"{/if}  id="table2" class="data_table_container dtable btable "></div>{/if}
      </div>


    </div>
  </div>
  <div class="yui-b" style="text-align:right">
    {include file='product_search.tpl'}
    
    {if $products>0 and ($view_sales or $view_stock) }
     <table class="options" >
      <tr><td  {if $view_table==0}class="selected"{/if} id="but_view0" >{t}Basic{/t}</td></tr>
      	{if $view_stock}<tr><td {if $view_table==1}class="selected"{/if}  id="but_view1"  >{t}Stock{/t}</td></tr>{/if}
      	{if $view_sales}<tr><td  {if $view_table==2}class="selected"{/if}  {if $products==0 }style="display:none"{/if} id="but_view2"  >{t}Sales{/t}</td></tr>{/if}
     </table>
     {/if}
       {if $modify}
     <table class="options" >
       <tr><td   id="but_view3" >{t}Edit{/t}</td>  </tr>
     </table>
     <div id="edit_menu" style="display:none">
       <table class="but edit" style="float:right">
	  {if $create}<tr><td id="add_family">{t}Add Family{/t}</td></tr>{/if}
      </table>
    </div>
     {/if}

  </div>
</div> 
<div id="add_family_form">
  <div class="hd">{t}New Family{/t}</div> 
  <div class="bd"> 
    <form method="POST" action="ar_assets.php"> 
      <input name="tipo" type="hidden" value="new_family" />
      <input name="id" type="hidden" value="{$department_id}" />

      <br>
      <table >
	<tr><td>{t}Name{/t}:</td><td><input name="name" type='text' class='text' MAXLENGTH="16"/></td></tr>
	<tr><td>{t}Description{/t}:</td><td><input name="description" type='text'  MAXLENGTH="60" class='text' /></td></tr>
      </table>
    </form>
  </div>
</div>
<div id="upload_family_form">
  <div class="hd">{t}New Products from file{/t}</div> 
  <div class="bd"> 
    <form  enctype="multipart/form-data" method="POST" action="upload_assets.php"   id="uploadForm"   > 
      <input name="from" type="hidden" value="department" />
      <br>
      <table >
	<tr><td>{t}CVS File{/t}:</td><td><input  class="file" name="uploadedfile" type="file" /></td></tr>
      </table>
    </form>
  </div>
</div>
{include file='footer.tpl'}

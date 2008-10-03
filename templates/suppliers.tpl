{include file='header.tpl'}
<div id="bd" >

  <div id="yui-main">
    <div class="yui-b">
      <h2>{t}Suppliers{/t}</h2>
      <div class="data_table" style="margin-top:25px">
      {include file='table.tpl' table_id=0 table_title=$t_title0 filter=$filter0 filter_name=$filter_name0  filter_value=$filter_value0  }
      	       <div  id="table0"   class="data_table_container dtable btable "> </div>
	</div> 

    </div>
  </div>
  <div class="yui-b" style="text-align:right">
    <h2>{t}Edit Menu{/t}</h2>
    <div>
    <button id="edit_suppliers">{t}Edit Suppliers{/t}</button>
    <button id="add_supplier">{t}Add Supplier{/t}</button>
    </div>
  </div>
</div> 


<div id="add_supplier_form">
  <div class="hd">{t}New Supplier{/t}</div> 
  <div class="bd"> 
    <form method="POST" action="ar_suppliers.php"> 
      <input name="tipo" type="hidden" value="new_supplier" />
      <br>
      <table >
	<tr><td>{t}Code{/t}:</td><td><input name="code" type='text' class='text' MAXLENGTH="16"/></td></tr>
	<tr><td>{t}Full Name{/t}:</td><td><input name="name" type='text'  MAXLENGTH="60" class='text' /></td></tr>
      </table>
    </form>
  </div>
</div>

{include file='footer.tpl'}

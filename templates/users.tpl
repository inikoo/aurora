{include file='header.tpl'}
<div id="bd" >
  <div id="yui-main">
    <div style="width:300px;float:right;padding:10px;text-align:right">
      <span class="but new edit" id="add_user">Add User</span>
    </div>
<div is="add_user" class="data_table" style="margin-top:25px">
  <span class="clean_table_title">{t}Users{/t}</span>
  <div  class="clean_table_caption"  style="clear:both;">
    <div style="float:left;"><div class="clean_table_info">{$table_info} <span class="filter_msg"  id="filter_msg0"></span></div></div>
    <div class="clean_table_filter"><div class="clean_table_info">{$filter_name}: <input style="border-bottom:none" id='f_input0' value="{$filter_value}" size=10/><div id='f_container0'></div></div></div>
    <div class="clean_table_controls" style="" ><div><span  style="margin:0 5px" id="paginator0"></span></div></div>
  </div>
  <div  id="table0"   class="data_table_container dtable btable "> </div>
</div>
<div class="data_table" style="margin-top:25px">
  <span class="clean_table_title">{t}Groups{/t}</span>
  <div  class="clean_table_caption"  style="clear:both;">
    <div style="float:left;"><div class="clean_table_info">{$table_info} <span class="filter_msg"  id="filter_msg1"></span></div></div>
    <div class="clean_table_filter"><div class="clean_table_info">{$filter_name}: <input style="border-bottom:none" id='f_input1' value="{$filter_value}" size=10/><div id='f_container1'></div></div></div>
    <div class="clean_table_controls" style="" ><div><span  style="margin:0 5px" id="paginator1"></span></div></div>
  </div>
  <div  id="table1"   class="data_table_container dtable btable "> </div>
</div>

  </div>
</div> 
<div id="add_user_dialog">
  <div class="hd">{t}New user{/t}</div>
  <div class="bd">
    <div class="resp" ></div>
    <form action="ar_users.php">
      <table>
      <input type="hidden" name="tipo" value="add_user"/>
      <input type="hidden" id="ep" name="ep" value=""/>
      <tr><td><label for="handle">{t}Handle{/t}:</label></td><td><input class="text"  type="text" value="" name="handle"/></td></tr>
      <tr><td><label for="name"  >{t}Name{/t}  :</label></td><td><input  class="text" type="text" value="" name="name"/></td></tr>
      <tr><td><label for="surname">{t}Surname{/t}:</label></td><td><input class="text" type="text" value="" name="surname"/></td></tr>
      <tr><td><label for="email">{t}Email{/t}:</label></td><td><input  class="text" type="text" value="" name="email"/></td></tr>
      <tr><td>
      <label for="lang[]">{t}Language{/t}:</label></td><td>
      <select name="lang[]">
	{foreach from=$newuser_langs item=lang key=lang_id}
	<option value="{$lang_id}">{$lang}</option>
	{/foreach}
      </select> 
      </td></tr>
      <tr><td>
      <label for="isactive">{t}Activate Account{/t}:</label></td><td>
      <input type="radio" value="1" name="isactive[]" checked="checked"  />{t}Yes{/t}
      </td></tr>
      <tr><td><label style="visibility:hidden">isactive:</label> </td><td>
      <input type="radio" value="0" name="isactive[]"  />{t}No{/t}
      </td></tr>
     <tr><td>
      <label for="group">{t}Groups{/t}:</label></td><td>
      {foreach from=$newuser_groups item=group key=group_id}
      <tr><td><label style="visibility:hidden">g</label></td><td><input type="checkbox" name="group[]" value="{$group_id}" />{$group}</td></tr>
      {/foreach}
      </table>
    </form>
  </div>
</div>


<div id="choose_user_kind_dialog">
  <div class="hd">{t}Choose kind of user{/t}</div>
  <div class="bd">
    <table>
      <tr><td>Staff</td></tr>
      <tr><td>Supplier</td></tr>
      <tr><td>Customer</td></tr>
      <tr><td>Other</td></tr>
    </table>
  </div>
</div>






{include file='footer.tpl'}


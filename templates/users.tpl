{include file='header.tpl'}
<div id="bd" >
  <div id="yui-main">
    <div class="yui-b">
      <h2>{t}Users Administration{/t}</h2>
      {include file='table.tpl' table_id=0 table_title='Users' filter=$filter filter_name=$filter_name}
      {include file='table.tpl' table_id=1 table_title='Groups' filter=$filter filter_name=$filter_name}
      
    </div>
  </div>
    <div class="yui-b">
      <h2>{t}Edit Options{/t}</h2>
      <button id="add_user">{t}Add User{/t}</button><br/><br/>
      <button id="edit_users">{t}Edit Users{/t}</button>
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
{include file='footer.tpl'}


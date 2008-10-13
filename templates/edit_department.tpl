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

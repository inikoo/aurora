<div id="dialog_upload_postcard" style="padding:20px 10px 10px 10px;width:320px;">

 <table>
  <form enctype="multipart/form-data" method="post" id="upload_postcard_form">
<input type="hidden" name="store_key" value="{$store->id}" />
 <tr><td>{t}Image{/t}:</td><td><input id="upload_postcard_file" style="border:1px solid #ddd;" type="file" name="image"/></td></tr>
  <tr><td>{t}Name{/t}:</td><td><input id="upload_postcard_name" style="border:1px solid #ddd;width:100%" name="name"/></td></tr>
  </form>
 <tr><td colspan=2>
  <div class="buttons">
<button class="positive"  id="upload_postcard"  >{t}Upload{/t}</button>
<button  id="cancel_upload_postcard" class="negative" >{t}Cancel{/t}</button><br/>

</div>
  </td></tr>

    </table>
 



</div>
<div id="dialog_change_email_type" style="padding:20px 10px 10px 10px;width:420px">

 <table  border=0 style="margin:auto">
<tr>
<td>
<div class="warning" style="padding:10px"><b>{t}Warning{/t}</b> {t}The email layout maybe be lost when changing the email type{/t}.</div>

</td>
</tr>


 <tr  style="height:40px">



<td >

<div class="buttons left">
<button  id="select_text_email" class="{if $email_campaign->get('Email Campaign Content Type')=='Plain'}selected{/if}" ><img src="art/icons/script.png" alt=""/> {t}Text Email{/t}</button>
<button  id="select_html_from_template_email" class="{if $email_campaign->get('Email Campaign Content Type')=='HTML Template'}selected{/if}" ><img src="art/icons/layout.png" alt=""/> {t}Template Email{/t}{if $email_campaign->get('Email Campaign Content Type')=='HTML Template'}<img class="selected" src="art/icons/accept.png"/>{/if}</button>
<button  id="select_html_email" class="{if $email_campaign->get('Email Campaign Content Type')=='HTML'}selected{/if}" ><img src="art/icons/html.png" alt=""/> {t}HTML Email{/t}</button>
</div>

</td>
</tr>

    </table>
</div>
<div id="dialog_upload_header_image" style="padding:20px 10px 10px 10px;width:320px">
 <table>
  <form enctype="multipart/form-data" method="post" id="upload_header_image_form">
<input type="hidden" name="store_key" value="{$store->id}" />
 <tr><td>{t}Image{/t}:</td><td><input id="upload_header_image_file" style="border:1px solid #ddd;" type="file" name="image"/></td></tr>
  <tr><td>{t}Name{/t}:</td><td><input id="upload_header_image_name" style="border:1px solid #ddd;width:100%" name="name"/></td></tr>

  </form>
 <tr><td colspan=2>
  <div class="buttons">
<button class="positive"  id="upload_header_image"  >{t}Upload{/t}</button>
<button  id="cancel_upload_header_image" class="negative" >{t}Cancel{/t}</button><br/>
</div>
  </td></tr>
    </table>
</div>
<div id="dialog_edit_color" style="padding-right:10px;width:360px;height:230px">
 <input type="hidden" id="color_edit_element" value=""/>

  <div style="position:relative;top:200px" class="buttons">
    <button id="save_color" class="positive">{t}Save{/t}</button>
  <button id="close_edit_color_dialog" class="negative">{t}Cancel{/t}</button>

 
 </div>
 <div id="edit_color" style="margin-top:20px;padding-top:20px;"></div>

</div>
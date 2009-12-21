 <form action="upload.php" enctype="multipart/form-data" method="post" id="testForm">
    <input type="file" name="testFile"/>
    <input type="button" id="uploadButton" value="Upload"/>
  </form>

  <div  id="images" class="edit_images" principal="{$data.principal_image}" >
    
    



    {foreach from=$images item=image  name=foo}
    <div id="image_container{$image.id}" class="image"  >
      <div class="image_name" id="image_name$image.id}">{$image.name}</div>
      <img class="picture" style="border:none"    src="{$image.small_url}" width="160"    /> 
      <div class="image_showcase">
	<span  class="image_caption" id="image_caption{$image.id}" >{$image.caption}</span> 
	<img  class="edit_image_caption" onClick="show_edit_caption({$image.id})" src="art/icons/edit.gif" style="cursor:pointer;position:relative;bottom:2px">
	<div style="display:none">
	<textarea class="caption" style="width:160px;margin-bottom:5px" onkeydown="caption_changed(this)" id="img_caption{$image.id}" image_id="{$image.id}" ovalue="{$image.caption}">{$image.caption} </textarea>
	<img style="vertical-align:top;"  class="caption" id="save_img_caption{$image.id}" onClick="save_image('img_caption',{$image.id})" title="{t}Save caption{/t}" alt="{t}Save caption{/t}"   src="art/icons/disk.png">
	<img style="vertical-align:top"  class="caption" id="save_img_caption{$image.id}" onClick="save_image('img_caption',{$image.id})" title="{t}Save caption{/t}" alt="{t}Save caption{/t}"   src="art/icons/bullet_come.png">
	</div>
      </div>
      <div class="operations">

	<span  style="{if $image.is_principal=='Yes'} {else}display:none{/if}"   class="img_set_principal"  ><img id="img_set_principal{$image.id}" onClick="set_image_as_principal(this)" title="{t}Main Image{/t}" image_id="{$image.id}" principal="1" src="art/icons/asterisk_orange.png"></span>
	
	<span  style="{if $image.is_principal=='Yes'}display:none{else}{/if}" class="img_set_principal" style="cursor:pointer"  >
	  <img id="img_set_principal{$image.id}" onClick="set_image_as_principal(this)" title="{t}Set as the principal image{/t}" image_id="{$image.id}" principal="0" src="art/icons/picture_empty.png"></span>

	<span style="cursor:pointer;" image_id="{$image.id}" onClick="delete_image()"><img src="art/icons/delete.png" alt="{t}Delete{/t}" title="{t}Delete{/t}"></span>

      </div>
     
    
    </div>
    {/foreach}
    <div style="clear:both"></div>
  </div>

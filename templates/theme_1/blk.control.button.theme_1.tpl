{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 15 July 2017 at 18:09:10 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}

<div id="edit_mode_{$key}" class=" edit_mode " type="{$block.type}" key="{$key}" style="height: 22px;line-height: 22px">
    <div style="float:left;margin-right:20px;min-width: 200px;">
        <div style="float:left;min-width: 200px;position: relative;top:2px">
            <i class="fa fa-fw {$block.icon}" style="margin-left:10px" aria-hidden="true" title="{$block.label}"></i>
            <span class="label">{$block.label}</span>
        </div>



        <span class="small" style="font-style: italic">{t}Background image{/t}, {t}Min width{/t} 1240px,{t}Min height{/t} 75px  </span>
        <input style="display:none" type="file" block_key="{$key}" name="button_bg" id="update_image_{$key}" class="image_upload_from_iframe"

               data-parent="Webpage" data-parent_key="{$webpage->id}" data-parent_object_scope="Image" data-metadata='{ "block":"button"}'  data-options='{ "min_width":"1240","min_height":"750"}'  data-response_type="webpage" />

        <label style="margin-left:10px;font-weight: normal;cursor: pointer" for="update_image_{$key}"><i class="fa fa-upload" aria-hidden="true"></i> {t}Upload{/t}</label>



        <div id="button_link_edit_block_{$key}" name="button_link_edit_block" class="hide edit_block" style="position:absolute;padding:10px;background-color: #FFF;border:1px solid #ccc;z-index: 4000">
            <input value="{$block.link}" style="width: 450px"> <i class="apply_changes  fa button fa-check-square" style="margin-left: 10px" aria-hidden="true"></i>
        </div>

        <span style="margin-left:20px"> {t}Link{/t}</span>
        <span id="button_link_{$key}" key="{$key}" class="button_link button" style="margin-left:10px">
    <i class="fa fa-link   {if $block.link=='' }very_discreet{/if} " aria-hidden="true"></i>
    <span class="button  {if $block.link=='' }hide{/if} " style="border:1px solid #ccc;padding:2px 4px;">{$block.link|truncate:30}</span>
</span>

    </div>
    <div style="clear: both"></div>
</div>

<script>
  var webpage_scope_droppedFiles = false;


  $(document).on('change', '.image_upload_from_iframe', function (e) {

    var ajaxData = new FormData();

    if (webpage_scope_droppedFiles) {
      $.each(webpage_scope_droppedFiles, function (i, file) {
        ajaxData.append('files', file);
        return false;
      });
    }

    $.each($(this).prop("files"), function (i, file) {
      ajaxData.append("files[" + i + "]", file);
      return false;
    });


    var response_type = $(this).data('response_type')

    ajaxData.append("tipo", 'upload_images')
    ajaxData.append("parent", $(this).data('parent'))
    ajaxData.append("parent_key", $(this).data('parent_key'))
    ajaxData.append("parent_object_scope", $(this).data('parent_object_scope'))
    if ($(this).data('metadata') != '') {
      ajaxData.append("metadata", JSON.stringify($(this).data('metadata')))
    }
    if ($(this).data('options') != '') {
      ajaxData.append("options", JSON.stringify($(this).data('options')))
    }
    ajaxData.append("response_type", response_type)


    var element = $(this)

    $.ajax({
             url: "/ar_upload.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false,


             complete: function () {

             }, success: function (data) {

        console.log(data)

        if (data.state == '200') {


          console.log(element.attr('name'))
          switch (element.attr('name')) {

            case 'button_bg':
              $("#preview").contents().find("#block_" + element.attr('block_key')).find('div.button_block').css('background-image', 'url(' + '/wi.php?id='+data.img_key + ')').attr('button_bg', '/wi.php?id='+data.img_key);
              break;

          }
          $('#save_button', window.parent.document).addClass('save button changed valid')

        } else if (data.state == '400') {
          swal.fire({
                      title: data.title, text: data.msg
                    });
        }

        element.val('')

      }, error: function () {

      }
           });


  });

</script>
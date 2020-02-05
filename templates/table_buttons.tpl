<div id="table_buttons" class="hide">
    {if isset($table_buttons)   }
    {foreach from=$table_buttons item=button }


    {if !empty($button.id) and $button.id=='upload_order_items'}
    <div id="upload_order_items" class="table_button square_button right {if isset($button.class)}{$button.class}{/if}">
        <form method="post" action="/ar_edit.php" enctype="multipart/form-data" novalidate>
            <input style="display:none" type="file" name="upload" id="upload_order_items_upload" class="table_input_file"
                   data-field="{$button.upload_items.field}"
                   data-data='{ "tipo":"add_items_to_order","parent":"{$button.upload_items.parent}","parent_key":"{$button.upload_items.parent_key}","upload_type":"Add_Items"  }'
            />
            <label for="upload_order_items_upload"> <i class="fa fa-upload button"></i></label>
        </form>
    </div>
    {else}


    {if isset($button.move_all_parts_from_location)}{include file="move_all_parts_from_location.tpl" data=$button.move_all_parts_from_location }{/if}


    {if isset($button.inline_new_object)}{include file="inline_new_object.tpl" data=$button.inline_new_object trigger={$button.id}}{/if}


    <div {if isset($button.id) and $button.id }id="{$button.id}"{/if} {if isset($button.attr)}{foreach from=$button.attr key=attr_key item=attr_value }{$attr_key}="{$attr_value}" {/foreach}{/if}
    {if isset($button.data_attr)}{foreach from=$button.data_attr key=attr_key item=attr_value }data-{$attr_key}="{$attr_value}" {/foreach}{/if}


    class="table_button square_button right {if isset($button.class)}{$button.class}{/if}"
    {if isset($button.reference) and $button.reference!=''}onclick="change_view('{$button.reference}')"{elseif isset($button.change_tab) and $button.change_tab!=''}onclick=
    "change_view(state.request + '&tab={$button.change_tab}')"{/if}
    {if isset($button.title)}title="{$button.title}"{/if}
    >

    {if $button.icon=='edit_add'}
    <span id="show_edit_table_dialog_button" class="fa-stack" onclick="show_edit_table_dialog()"><i class="fa fa-plus fa-stack-1x " style="font-size:70%; margin-right:-50%;margin-left:-25%;margin-top:-10%"></i><i
                class="fa fa-pencil fa-stack-1x " style="margin-right:0%;margin-left:0%;"></i></span>
    {elseif $button.icon=='edit'}
    <span id="show_edit_table_dialog_button" onclick="show_edit_table_dialog()">

            <i class="fa fa-fw fa-edit "></i>
                </span>
    {else}
    <i {if isset($button.id) and $button.id }id="icon_{$button.id}"{/if} class="{if isset($button.icon_classes)}{$button.icon_classes}{else}fa fa-{$button.icon} fa-fw{/if}"></i>
    {/if}

</div>
{if $button.icon=='edit_add'}
    <div id="inline_edit_table_items_buttons" class="hide" style="float:right;margin-right: 10px" data-object='{$edit_table_dialog.spreadsheet_edit.object}'>
        <i id="inline_edit_table_items_save_button" onclick="save_table_items()" class="fa fa-cloud button save" aria-hidden="true"></i>
        <i id="inline_edit_table_items_close_button" class="fa fa-window-close button" onclick="close_table_edit_view(this)" style="margin-left:10px;margin-right: 10px" aria-hidden="true"></i> {t}inline editing{/t}

    </div>
{/if}

{if isset($button.add_item)}{include file="add_item.tpl" data=$button.add_item trigger={$button.id}}{/if}
{if isset($button.add_item_to_portfolio)}{include file="add_item_to_portfolio.tpl" data=$button.add_item_to_portfolio trigger={$button.id}}{/if}
{if isset($button.add_allowance_to_order_recursion_deal)}{include file="add_allowance_to_order_recursion_deal.tpl" data=$button.add_allowance_to_order_recursion_deal trigger={$button.id}}{/if}
{if isset($button.add_bulk_deal)}{include file="add_bulk_deal.tpl" data=$button.add_bulk_deal trigger={$button.id}}{/if}
{if isset($button.add_part_to_location)}{include file="add_part_to_location.tpl" data=$button.add_part_to_location trigger={$button.id}}{/if}
{if isset($button.inline_new_object)}
    <span id="inline_new_object_msg" class="invalid"></span>
{elseif isset($button.add_item)}
    <span id="inline_add_item_msg" class="invalid"></span>
{/if}
{/if}
{/foreach}
{/if}
{if isset($upload_file)}
    <div class="square_button right ">
        <form method="post" action="/ar_edit.php" enctype="multipart/form-data" novalidate>

            <input type="file" name="image_upload" id="file_upload" class="input_file" multiple/>
            <label for="file_upload"><i class="fa {if isset($upload_file.icon)}{$upload_file.icon}{else}fa-upload{/if} fa-fw button"></i></label>
        </form>
    </div>
    <span id="file_upload_msg" style="float:right;padding-right:10px"></span>
    <script>
        var droppedFiles = false;

        $('#file_upload').on('change', function (e) {
            upload_file()
        });

        function upload_file() {
            var ajaxData = new FormData();

            //var ajaxData = new FormData( );
            if (droppedFiles) {
                $.each(droppedFiles, function (i, file) {
                    ajaxData.append('files', file);
                });
            }


            $.each($('#file_upload').prop("files"), function (i, file) {
                ajaxData.append("files[" + i + "]", file);

            });


            ajaxData.append("tipo", '{$upload_file.tipo}')
            ajaxData.append("parent", '{$upload_file.parent}')
            ajaxData.append("parent_key", '{$upload_file.parent_key}')
            ajaxData.append("objects", '{$upload_file.object}')

            {if !empty($upload_file.scope)}
            ajaxData.append("parent_object_scope", '{$upload_file.scope}')
            {/if}


            $.ajax({
                url: "/ar_upload.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false,


                complete: function () {

                }, success: function (data) {


                    if (data.state == '200') {

                        if (data.tipo == 'upload_images') {

                            $('.Number_Images').html('(' + data.number_images + ')')

                            rows.url = '/' + rows.ar_file + '?tipo=' + rows.tipo + '&parameters=' + rows.parameters
                            rows.fetch({
                                reset: true
                            });

                            if (data.number_images == 0) {

                                $('div.main_image').addClass('hide')
                                $('form.main_image').removeClass('hide')
                                $('div.main_image img').attr('src', '/art/nopic.png')


                            } else {
                                $('div.main_image').removeClass('hide')
                                $('form.main_image').addClass('hide')
                                $('div.main_image img').attr('src', '/image.php?id=' + data.main_image_key + '&s=270x270')


                            }

                        } else if (data.tipo == 'upload_objects') {
                            change_view(state.request + '/upload/' + data.upload_key);
                        }

                    } else if (data.state == '400') {
                        $('#file_upload_msg').html(data.msg).addClass('error')
                    }


                }, error: function () {

                }
            });
        }


    </script>
{/if}
</div>
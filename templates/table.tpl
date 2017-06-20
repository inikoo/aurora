{if isset($period)  and   !isset($hide_period) }

    {include file="utils/date_chooser.tpl" period=$period from=$from to=$to from_mmddyy=$from_mmddyy  to_mmddyy=$to_mmddyy from_locale=$from_locale  to_locale=$to_locale  }
{/if}

{if isset($table_top_template)}
    {include file=$table_top_template  }
{/if}

{if isset($elements) and count(elements)>0}
    <div id="elements" class="elements tabs ">
        <div id="element_type" onClick="show_elements_types()"><i id="element_type_select_icon" class="fa fa-bars""></i></div>
        {foreach from=$elements item=element_group key=_elements_type}
            <div id="elements_group_{$_elements_type}" elements_type="$_elements_type" class="elements_group {if $_elements_type!=$elements_type}hide{/if}">
                {foreach from=$element_group['items']|@array_reverse item=element key=id}
                    <div id="element_{$id}" item_key="{$id}" class="element right  {if isset($element.selected) and $element.selected}selected{/if}" onclick="change_table_element(event,'{$id}')"
                         title="{$elements[$elements_type]['label']}: {if isset($element.title)}{$element.title}{else}{$element.label}{/if}">
                        <i id="element_checkbox_{$id}" class="fa {if $element.selected}fa-check-square-o{else}fa-square-o{/if}"></i> <span class="label"> {$element.label}  </span> <span class="qty"
                                                                                                                                                                                          id="element_qty_{$id}"></span>
                    </div>
                {/foreach}
            </div>
        {/foreach}
    </div>
{/if}
{if isset($edit_table_dialog)}
    <div id="edit_table_dialog" class="hide">
    <table>
        <tr>
            <td>{t}Edit items{/t}
            </td>
            <td class="button" onclick="table_edit_view()" ><i class="fa  fa-fw fa-i-cursor" aria-hidden="true"></i> {t}inline{/t}
            </td>

            <td><i class="fa  fa-fw fa-table" aria-hidden="true"></i> {t}by{/t} <span onclick="show_download_edit_items_dialog()" class="marked_link">{t}spreadsheet{/t}</span></td>
            <td>

                <form method="post" action="/ar_edit.php" enctype="multipart/form-data" novalidate>

                    <input style="display:none" type="file" name="upload" id="table_edit_items_file_upload" class="table_input_file"
                           data-data='{ "tipo":"{$edit_table_dialog.spreadsheet_edit.tipo}","parent":"{$edit_table_dialog.spreadsheet_edit.parent}","parent_key":"{$edit_table_dialog.spreadsheet_edit.parent_key}", "object":"{$edit_table_dialog.spreadsheet_edit.object}" }'
                    />
                    <label for="table_edit_items_file_upload"> <i class="fa fa-upload button" aria-hidden="true"></i></label>
                </form>



            </td>


        </tr>
        <tr>
            <td>{t}Add items{/t}
            </td>
            <td class="button" onclick="change_view('{$edit_table_dialog.new_item.reference}')"><i class="fa  fa-fw fa-server fa-flip-horizontal " aria-hidden="true"></i> {t}Online form{/t}
            </td>
            <td><i class="fa fa-fw fa-table" aria-hidden="true"></i> {t}from{/t} <a class="marked_link" href="{$edit_table_dialog.upload_items.template_url}" >{t}template{/t}</a>
            </td>
            <td>
                <form method="post" action="/ar_edit.php" enctype="multipart/form-data" novalidate>

                    <input style="display:none" type="file" name="upload" id="table_add_items_file_upload" class="table_input_file"
                           data-data='{ "tipo":"{$edit_table_dialog.upload_items.tipo}","parent":"{$edit_table_dialog.upload_items.parent}","parent_key":"{$edit_table_dialog.upload_items.parent_key}","object":"{$edit_table_dialog.upload_items.object}" }'
                    />
                    <label for="table_add_items_file_upload"> <i class="fa fa-upload button" aria-hidden="true"></i></label>
                </form>

            </td>
        </tr>
    </table>

</div>

    <div id="download_edit_items_dialog_container"  >
        <span class="hide" id="export_queued_msg"><i class="fa background fa-spinner fa-spin"></i> {t}Queued{/t}</span>
        <div id="download_edit_items_dialog" class="export_dialog hide" >
            <table border=0 style="width:100%">
                <tr class="no_border">
                    <td class="export_progress_bar_container" style="width: 100px" >
                        <a href="" id="download_excel" download hidden></a>
                        <span class="hide export_progress_bar_bg" id="export_progress_bar_bg_excel"></span>
                        <div class="hide export_progress_bar" id="export_progress_bar_excel"></div>
                        <div class="export_download hide" id="export_download_excel"> {t}Download{/t}</div>
                    </td>
                    <td class="width_20">
                        <i id="stop_export_table_excel" stop=0 onclick="stop_export('excel')" class="fa button fa-hand-stop-o error hide" title="{t}Stop{/t}"></i>
                    </td>
                    <td id="export_table_excel" class="link" onclick="get_editable_data(this,'excel')"
                        data-data='{ "parent_code":"{$edit_table_dialog.spreadsheet_edit.parent_code}","parent":"{$edit_table_dialog.spreadsheet_edit.parent}","parent_key":"{$edit_table_dialog.spreadsheet_edit.parent_key}","object":"{$edit_table_dialog.spreadsheet_edit.object}" }'


                    ><i class="fa fa-file-excel-o" title="Excel"></i>Excel
                    </td>
                </tr>
                <tr>
                    <td class="export_progress_bar_container"><a href="" id="download_csv" download hidden></a>
                        <span class="hide export_progress_bar_bg" id="export_progress_bar_bg_csv"></span>
                        <div class="hide export_progress_bar" id="export_progress_bar_csv"></div>
                        <div class="export_download hide " id="export_download_csv"> {t}Download{/t}</div>

                    </td>
                    <td class="width_20"><i id="stop_export_table_csv" onclick="stop_export('csv')" class="fa button fa-hand-stop-o error hide" title="{t}Stop{/t}"></i></td>
                    <td id="export_table_csv" class="link" onclick="get_editable_data(this,'csv')"
                        data-data='{ "parent_code":"{$edit_table_dialog.spreadsheet_edit.parent_code}","parent":"{$edit_table_dialog.spreadsheet_edit.parent}","parent_key":"{$edit_table_dialog.spreadsheet_edit.parent_key}","object":"{$edit_table_dialog.spreadsheet_edit.object}" }'

                    ><i class="fa fa-table" title="{t}Comma Separated Value{/t}"></i>CSV
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class=""></i></td>
                    <td>


                        <form method="post" action="/ar_edit.php" enctype="multipart/form-data" novalidate>

                            <input style="display:none" type="file" name="upload" id="table_edit_items_file_upload" class="table_input_file"
                                   data-data='{ "tipo":"{$edit_table_dialog.spreadsheet_edit.tipo}","parent":"{$edit_table_dialog.spreadsheet_edit.parent}","parent_key":"{$edit_table_dialog.spreadsheet_edit.parent_key}", "object":"{$edit_table_dialog.spreadsheet_edit.object}" }'
                            />
                            <label for="table_edit_items_file_upload">
                                <div id="table_edit_items_file_upload2_button" class="button disabled"><i class="fa fa-upload" title="{t}Upload{/t}"></i>{t}Upload{/t}
                                </div>
                            </label>
                        </form>


                    </td>
                </tr>
                <tr>
                    <td colspan="2" class=""></td>
                    <td>
                        <div onclick="hide_export_dialog()" class="button disabled"><i class="fa fa-times" title="{t}Close dialog{/t}"></i>{t}Close{/t}
                        </div>
                    </td>
                </tr>
            </table>

        </div>
        <div id="download_edit_items_dialog_config" style="min-width: 400px" class="export_dialog hide">
            {if isset($edit_fields)}
                <table>
                    <tr class="small_row ">
                        <td></td>
                        <td style="width_20" class="field_export ">
                            <i id="toggle_all_export_fields" onclick="toggle_all_export_fields(this)"
                               class="button fa-fw fa fa-square-o"></i>
                        </td>
                    </tr>
                    <tbody id="export_fields">
                    {foreach from=$edit_fields item=export_field key=_key}
                        <tr class="small_row">
                            <td>{$export_field.label}</td>
                            <td style="width_20" class="field_export">
                                <i id="field_export_{$_key}" onclick="toggle_export_field({$_key})" key="{$_key}"
                                   class="button fa-fw object_field fa {if $export_field.checked }fa-check-square-o{else}fa-square-o{/if}"></i>
                            </td>
                        </tr>
                    {/foreach}
                    </tbody>
                </table>
            {/if}
        </div>
    </div>


    <script>
        var droppedFiles = false;

        $('.table_input_file').on('change', function (e) {




            var ajaxData = new FormData();

            //var ajaxData = new FormData( );
            if (droppedFiles) {
                $.each(droppedFiles, function (i, file) {
                    ajaxData.append('files', file);
                });
            }


            $.each($(this).prop("files"), function (i, file) {
                ajaxData.append("files[" + i + "]", file);

            });



            ajaxData.append("tipo",$(this).data('data').tipo)
            ajaxData.append("parent", $(this).data('data').parent)
            ajaxData.append("parent_key",$(this).data('data').parent_key)
            ajaxData.append("objects",$(this).data('data').object)


            console.log($(this).data('data'))

            $.ajax({
                url: "/ar_upload.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false,


                complete: function () {

                }, success: function (data) {


                    if (data.state == '200') {

                        if (data.tipo == 'upload_images') {

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
                                $('div.main_image img').attr('src', '/image_root.php?id=' + data.main_image_key + '&size=small')


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

        });






    </script>


{/if}


{if isset($table_top_lower_template)}
    {include file=$table_top_lower_template  }
{/if}

<div class="table_info">
    <div id="last_page" onclick="rows.getLastPage()" class="square_button right hide" title="{t}Last page{/t}" style="position:relative">
        <i class="fa fa-chevron-right fa-fw" style="position:absolute;left:2px;bottom:6px"></i> <i style="position:absolute;left:9px;bottom:6px" class="fa fa-chevron-right fa-fw"></i>
    </div>
    <div id="next_page" onclick="rows.getNextPage()" class="square_button right hide" title="{t}Next page{/t}">
        <i class="fa fa-chevron-right fa-fw"></i>
    </div>
    <div id="paginator" style="float:right;padding-left:10px;padding-right:10px;"></div>
    <div id="results_per_page" onclick="show_results_per_page()" class="square_button right hide" title="{t}Results per page{/t} ({$results_per_page})">
        <i class="fa fa-reorder fa-fw"></i>
    </div>
    {foreach from=$results_per_page_options item=results_per_page_option}
    <div id="results_per_page_{$results_per_page_option}" onclick="change_results_per_page({$results_per_page_option})"
         class="square_button right hide results_per_page {if $results_per_page==$results_per_page_option}selected{/if}">
        {$results_per_page_option}
    </div>
    {/foreach}
    <div id="prev_page" onclick="if(rows.state.currentPage==1)return;rows.getPreviousPage()" class="square_button right disabled hide" title="{t}Previous page{/t}">
        <i class="fa fa-chevron-left   fa-fw"></i>
    </div>
    <div id="first_page" onclick="rows.getFirstPage()" class="square_button right hide" title="{t}First page{/t}" style="position:relative">
        <i class="fa fa-chevron-left fa-fw" style="position:absolute;left:2px;bottom:6px"></i> <i style="position:absolute;left:9px;bottom:6px" class="fa fa-chevron-left fa-fw"></i>
    </div>
    <div id="show_export_dialog" class="left square_button  {if !isset($export_fields)}hide{/if}  " title="{t}Export{/t}">
        <i onclick="show_export_dialog_left_button()" class="fa fa-download fa-fw"></i>

    </div>

    <div id="export_dialog_container" style="position:absolute" class="  ">
        <span class="hide" id="export_queued_msg"><i class="fa background fa-spinner fa-spin"></i> {t}Queued{/t}</span>
        <div id="export_dialog" class="export_dialog hide" style="z-index: 2">
            <table border=0 style="width:100%">
                <tr class="no_border">
                    <td class="export_progress_bar_container">
                        <a href="" id="download_excel_export" download hidden></a>
                        <span class="hide export_progress_bar_bg" id="export_progress_bar_bg_excel_export"></span>
                        <div class="hide export_progress_bar" id="export_progress_bar_excel_export"></div>
                        <div class="export_download hide" id="export_download_excel_export"> {t}Download{/t}</div>
                    </td>
                    <td class="width_20">
                        <i id="stop_export_table_excel_export" stop=0 onclick="stop_export('excel')"
                           class="fa button fa-hand-stop-o error hide" title="{t}Stop{/t}"></i>
                    </td>
                    <td id="export_table_excel_export" class="link" onclick="export_table('excel')"><i
                                class="fa fa-file-excel-o" title="Excel"></i>Excel
                    </td>
                </tr>
                <tr>
                    <td class="export_progress_bar_container"><a href="" id="download_csv_export" download hidden></a>
                        <span class="hide export_progress_bar_bg" id="export_progress_bar_bg_csv_export"></span>
                        <div class="hide export_progress_bar" id="export_progress_bar_csv_export"></div>
                        <div class="export_download hide " id="export_download_csv_export"> {t}Download{/t}</div>
                    </td>
                    <td class="width_20"><i id="stop_export_table_csv_export" onclick="stop_export('csv')"
                                            class="fa button fa-hand-stop-o error hide" title="{t}Stop{/t}"></i></td>
                    <td id="export_table_csv_export" class="link" onclick="export_table('csv')"><i class="fa fa-table"
                                                                                                   title="{t}Comma Separated Value{/t}"></i>CSV
                    </td>
                </tr>
                <tr>
                    <td>
                        <div onclick="hide_export_dialog()" class="button disabled"><i class="fa fa-times"
                                                                                       title="{t}Close dialog{/t}"></i>{t}Close{/t}
                        </div>
                    </td>
                    <td colspan="2" class="aright"><i onclick="open_export_config_left_button()"
                                                      class="fa fa-cogs button"></i></td>
                </tr>
            </table>
        </div>
        <div id="export_dialog_config" class="export_dialog hide" style="z-index: 3">
            {if isset($export_fields)}
                <table>
                    <tr class="small_row ">
                        <td></td>
                        <td style="width_20" class="field_export ">
                            <i id="toggle_all_export_fields" onclick="toggle_all_export_fields(this)"
                               class="button fa-fw fa fa-check-square-o"></i>
                        </td>
                    </tr>
                    <tbody id="export_fields">
                    {foreach from=$export_fields item=export_field key=_key}
                        <tr class="small_row">
                            <td>{$export_field.label}</td>
                            <td style="width_20" class="field_export">
                                <i id="field_export_{$_key}" onclick="toggle_export_field({$_key})" key="{$_key}"
                                   class="button fa-fw fa {if $export_field.checked }fa-check-square-o{else}fa-square-o{/if}"></i>
                            </td>
                        </tr>
                    {/foreach}
                    </tbody>
                </table>
            {/if}
        </div>
    </div>

    <div id="filter_container" class="">
        <div id="show_filter" onclick="show_filter()" class="square_button right " title="{t}Filter table{/t}" style="border-left:1px solid #aaa">
            <i class="fa fa-filter fa-fw"></i>
        </div>

        <div id="filter_submit" onclick="$('#filter form.backgrid-filter').submit()" class="square_button right filter hide" title="{t}Apply filter{/t}">
            <i class="fa fa-filter fa-fw"></i>
        </div>
        <div id="filter" class="filter hide" f_field="{$f_field}">

        </div>
        <div id="filter_field" class="filter hide {if $f_options|@count gt 1}button{/if}" onClick="show_f_options()" style="margin-left:10px">

            <span class="label">{$f_label}</span>:
        </div>
        <div>

        </div>



    </div>

    <div id="table_buttons" ">

        {if (isset($table_buttons) and count(table_buttons)>0)  }

        {foreach from=$table_buttons item=button }


        {if isset($button.inline_new_object)}
        {include file="inline_new_object.tpl" data=$button.inline_new_object trigger={$button.id}}
        {/if}


        <div {if isset($button.id) and $button.id }id="{$button.id}"{/if}
        {if isset($button.attr)} {foreach from=$button.attr key=attr_key item=attr_value }{$attr_key}="{$attr_value}" {/foreach}{/if}
        class="table_button square_button right {if isset($button.class)}{$button.class}{/if}"
        {if isset($button.reference) and $button.reference!=''}onclick="change_view('{$button.reference}')"{else if isset($button.change_tab) and $button.change_tab!=''}onclick=
        "change_view(state.request + '&tab={$button.change_tab}')"{/if}
        {if isset($button.title)}title="{$button.title}"{/if}>

        {if $button.icon=='edit_add'}
        <span id="show_edit_table_dialog_button" class="fa-stack" onclick="show_edit_table_dialog()" >
                                        <i class="fa fa-plus fa-stack-1x " style="font-size:70%; margin-right:-50%;margin-left:-25%;margin-top:-10%"></i>

                    <i class="fa fa-pencil fa-stack-1x " style="margin-right:10%;margin-left:10%;"></i>
                </span>


        {else}
        <i {if isset($button.id) and $button.id }id="icon_{$button.id}"{/if} class=" fa fa-{$button.icon} fa-fw"></i>
        {/if}

        </div>
         {if $button.icon=='edit_add'}
        <div id="inline_edit_table_items_buttons" class="hide" style="float:right;margin-right: 10px"
                 data-object='{$edit_table_dialog.spreadsheet_edit.object}'

        >
        <i id="inline_edit_table_items_save_button" onclick="save_table_items()" class="fa fa-cloud button save" aria-hidden="true"></i>
        <i id="inline_edit_table_items_close_button"  class="fa fa-window-close button" onclick="close_table_edit_view(this)" style="margin-left:10px;margin-right: 10px" aria-hidden="true"></i> {t}inline editing{/t}

        </div>

        <script>




            $('#table').on(' paste', '.table_item_editable.editing', function (e) {

                clipboardData = event.clipboardData || window.clipboardData || event.originalEvent.clipboardData;
               e.preventDefault();


        var text = clipboardData.getData("text/plain");
    document.execCommand("insertHTML", false, text);




    })


            $('#table').on('input paste', '.table_item_editable.editing', function () {

                if($(this).html()!= $(this).attr('ovalue')){

                    $(this).addClass('edited')
                    $('#inline_edit_table_items_save_button').addClass('changed valid')
                }else{
                     $(this).removeClass('edited')
                     process_table_item_editable_changes()
                }



               })

             function process_table_item_editable_changes(){

                $('.table_item_editable.editing').each(function(i, obj) {
                    if($(obj).html()!= $(obj).attr('ovalue')){
                         $('#inline_edit_table_items_save_button').addClass('changed valid')
                        return false;
                    }
                });

             }


              function close_table_edit_view(element){

                   if( $( element  ).hasClass('super_discreet')){
                        return;
                    }

                    $('#table .table_item_editable').attr('contenteditable',false).removeClass('editing edited success')

                    $('#inline_edit_table_items_buttons').addClass('hide')
                    $('#show_edit_table_dialog_button').removeClass('hide')




                }

              function process_table_item_editing_changes(){

                $('.table_item_editable.editing').each(function(i, obj) {
                    if($(obj).hasClass('edited')){



                        return false;
                    }
                });
                 $('#inline_edit_table_items_save_button').removeClass('fa-spinner fa-spin').addClass('save')
 $('#inline_edit_table_items_close_button').removeClass('super_discreet').addClass('button')


             }

             function undo_changes_after_error(element){

                  var item_editable= $(element).closest('tr').find('.table_item_editable')

                item_editable.html( item_editable.attr('ovalue')).removeClass('error edited')

                  $(element).prev('.error_msg').remove()
                   $(element).remove()

             }


              function save_table_items(){


                 if(! $('#inline_edit_table_items_save_button').hasClass('valid')){
                     return;
                  }

                    $('#inline_edit_table_items_save_button').removeClass('changed valid save').addClass('fa-spinner fa-spin')
                    $('#inline_edit_table_items_close_button').addClass('super_discreet').removeClass('button')

                $('.table_item_editable.editing').each(function(i, obj) {
                    if($(obj).html()!= $(obj).attr('ovalue')){





                     var ajaxData = new FormData();

                    ajaxData.append("tipo", 'edit_field')
                    ajaxData.append("object", $('#inline_edit_table_items_buttons').data('object'))



                    ajaxData.append("field",  $(obj).data('field')  )
                      ajaxData.append("key",  $(obj).closest('tr').find('.item_data').data('key')  )
                    ajaxData.append("value",  $(obj).html()  )



                    var item_class= $(obj).data('item_class')
                    var key= $(obj).closest('tr').find('.item_data').data('key')

                    $.ajax({
                        url: "/ar_edit.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false,


                        complete: function () { },
                        success: function (data) {
  console.log(data)
                            if (data.state == '200') {



                                $('#item_data_'+key).closest('tr').find('.'+item_class).addClass('success').removeClass('edited').html(data.formatted_value).attr('ovalue',data.formatted_value)
                            process_table_item_editing_changes()


                            } else if (data.state == '400') {

                           $('#item_data_'+key).closest('tr').find('.'+item_class).addClass('error').after('<i onclick="swal(\''+data.msg+'\')" class="fa error_msg button fa-exclamation-circle error padding_left_10" aria-hidden="true"></i> <i onclick="undo_changes_after_error(this)" class="fa button fa-undo" title="{t}Undo{/t}" aria-hidden="true"></i>')


                            }



                    }, error: function () {

                     }
                     });




                    }
                });

                     process_table_item_editing_changes()

             }

        </script>

        {/if}

        {if isset($button.add_item)}{include file="add_item.tpl" data=$button.add_item trigger={$button.id}}{/if}


         {if isset($button.inline_new_object)}
    <span id="inline_new_object_msg" class="invalid"></span>
    {else if isset($button.add_item)}
    <span id="inline_add_item_msg" class="invalid"></span>
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

            $.ajax({
                url: "/ar_upload.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false,


                complete: function () {

                }, success: function (data) {


                    if (data.state == '200') {

                        if (data.tipo == 'upload_images') {

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
                                $('div.main_image img').attr('src', '/image_root.php?id=' + data.main_image_key + '&size=small')


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

</div><span class="padding_left_10" id="rtext"></span></div>

<div id="table_edit_control_panel" class="hide" style="padding:10px 5px;border-bottom:1px solid #ccc">
    <div style="float:left"><i class="fa fa-fw fa-square-o button" style="padding:0px 5px 0px 0px" aria-hidden="true" onClick="select_all_rows(this)"></i> {t}Check/Uncheck all{/t}</div>
    <div style="float:left;margin-left:20px"><span id="selected_checkboxes" data-keys=""></span></div>
    <div style="clear:both"></div>
</div>

{if  (isset($table_views) and count($table_views)>1) or  isset($f_period)  or  isset($frequency) }
    <div class="table_views tabs ">
        {foreach from=$table_views item=view key=id}
            <div id="view_{$id}" class="view tab left {if isset($view.selected) and $view.selected}selected{/if}" onclick="change_table_view('{$id}',true)"
                 title="{if isset($view.title)}{$view.title}{else}{$view.label}{/if}">
                {if isset($view.icon) and $view.icon!=''}<i class="fa fa-{$view.icon}"></i>{/if} <span class="label"> {$view.label}</span>
            </div>
        {/foreach}
        {if isset($f_period) }
            <div id="columns_period" class="hide aright padding_right_10">
                <span class="label realce">{$f_period_label}</span> <i class="fa fa-bars fa-fw padding_left_10 button" aria-hidden="true" onclick="show_columns_period_options()"></i>
            </div>
        {/if}
        {if isset($frequency) }
            <div id="columns_frequency" class=" aright padding_right_10">
                <span class="label realce">{$frequency_label}</span> <i class="fa fa-bars fa-fw padding_left_10 button" aria-hidden="true" onclick="show_columns_frequency_options()"></i>
            </div>
        {/if}


    </div>
{/if}


<div class="table" id="table" data-metadata="{if isset($table_metadata)}{$table_metadata}{/if}"></div>


<script>


    var selected_checkbox ={}

    {if isset($title)}
        $('#nav_title').html("{$title}")
    {/if}
    {if isset($view_position)}
    $('#view_position').html("{$view_position}")
    {/if}

    var HtmlCell = Backgrid.HtmlCell = Backgrid.Cell.extend({

        /** @property */
        className: "html-cell",

        initialize: function () {
            Backgrid.Cell.prototype.initialize.apply(this, arguments);
        },

        render: function () {
            this.$el.empty();


            var rawValue = this.model.get(this.column.get("name"));
            var formattedValue = this.formatter.fromRaw(rawValue, this.model);
            this.$el.append(formattedValue);
            this.delegateEvents();
            return this;
        }
    });

    var HeaderHtmlCell = Backgrid.HeaderCell.extend({

        /** @property */
        className: "html-cell",


        render: function () {
            this.$el.empty();


            if (this.column.get('title')) {
                this.$el.attr('title', this.column.get('title'))

            }
            if (this.column.get('headerClass')) {
                this.$el.addClass(this.column.get('headerClass'));
            }


            this.$el.append(this.column.get("label"))


            this.delegateEvents();
            return this;
        }
    });

    var rightHeaderHtmlCell = Backgrid.HeaderCell.extend({

        /** @property */
        className: "html-cell align-right",


        render: function () {
            this.$el.empty();


            if (this.column.get('title')) {
                this.$el.attr('title', this.column.get('title'))

            }
            if (this.column.get('headerClass')) {
                this.$el.addClass(this.column.get('headerClass'));
            }


            this.$el.append(this.column.get("label"))


            this.delegateEvents();
            return this;
        }
    });


    var integerHeaderCell = Backgrid.HeaderCell.extend({
        className: "align-right",


        render: function () {

            this.constructor.__super__.render.apply(this, arguments);
            this.$el.addClass('align-right');
            return this;
        }
    });


    /*
     Backgrid.HeaderRow = Backgrid.HeaderRow.extend({
     render: function() {
     var that = this;
     Backgrid.HeaderRow.__super__.render.call(this, arguments);
     _.each(this.columns.models, function(modelValue) {

     // that.$el.find('.' + modelValue.get('name')).html(modelValue.get('label'))

     if (modelValue.get('title')) {
     that.$el.find('.' + modelValue.get('name')).attr('title', modelValue.get('title'))

     }

     });
     return this;
     }
     });

     */

    {include file="columns/`$tab`.cols.tpl" }


    var Row = Backbone.Model.extend({});

    var Rows = Backbone.PageableCollection.extend({
        model: Row, url: '{$request}', ar_file: '{$ar_file}', tipo: '{$tipo}', parameters: '{$parameters}', tab: '{$tab}', state: {
            pageSize: {$results_per_page}, sortKey: '{$sort_key}', order: parseInt({$sort_order})
        }, queryParams: {
            totalRecords: null, pageSize: "nr", sortKey: "o", order: "od"
        },


        parseState: function (resp, queryParams, state, options) {
            $('#rtext').html(resp.resultset.rtext)

            var total_pages = Math.ceil(resp.resultset.total_records / state.pageSize)

            // if (resp.resultset.total_records > 20) {
            //     $('#results_per_page').removeClass('hide')
            // }
            if (total_pages > 1 && '{$f_label}' != '') {
                $('#filter_container').removeClass('hide')
            }

            if (total_pages == 1) {
                $('#paginator').html('')
                $('#first_page').addClass('disabled')
                $('#prev_page').addClass('disabled')
                $('#last_page').addClass('disabled')
                $('#next_page').addClass('disabled')

            } else if (total_pages > 1) {

                $('#first_page').removeClass('hide')
                $('#prev_page').removeClass('hide')
                $('#last_page').removeClass('hide')
                $('#next_page').removeClass('hide')


                $('#paginator').html(rows.state.currentPage + '/' + total_pages)

                $('#first_page').removeClass('disabled')
                $('#prev_page').removeClass('disabled')
                $('#last_page').removeClass('disabled')
                $('#next_page').removeClass('disabled')


                if (rows.state.currentPage == 1) {
                    $('#prev_page').addClass('disabled')
                    $('#first_page').addClass('hide')

                } else if (rows.state.currentPage == 2) {

                    $('#first_page').addClass('disabled')

                } else if (rows.state.currentPage == total_pages) {
                    $('#next_page').addClass('disabled')

                    $('#last_page').addClass('disabled')

                }

            }

            $('th.ascending').removeClass('ascending')
            $('th.descending').removeClass('descending')

            if (resp.resultset.sort_dir == 'desc') {
                $('th.' + resp.resultset.sort_key).addClass('descending')
            }else {
                $('th.' + resp.resultset.sort_key).addClass('ascending')
            }

            return {
                totalRecords: parseInt(resp.resultset.total_records), sortKey: resp.resultset.sort_key
            };
        },

        parseRecords: function (resp, options) {

//   console.log(resp.resultset.data)

            return resp.resultset.data;
        }

    });


    var rows = new Rows();
    var grid = new Backgrid.Grid({
        columns: columns, collection: rows,


    });


    var serverSideFilter = new Backgrid.Extension.ServerSideFilter({
        collection: rows, name: "f_value", placeholder: ""
    });


    grid.render()


    $("#table").append(grid.el);


    change_table_view('{$table_view}', false)

    $("#filter").append(serverSideFilter.render().el);

    rows.fetch({
        reset: true, success: function () {

            post_table_rendered(grid.$el)

        }


    });


    {if isset($elements) and count(elements)>0}
    var with_elements = true;
    get_elements_numbers('{$tab}',{$parameters|@json_encode})
    {else}
    var with_elements = false;

    {/if}

    {if isset($js_code) }

    {if $js_code|is_array}
    {foreach from=$js_code item="file"}
    {include file="$file" }
    {/foreach}
    {else}
    {include file="$js_code" }
    {/if}
    {/if}

</script>

<div id="elements_chooser" class="hide panel popout_chooser corner">
    {foreach from=$elements item=element_group key=_elements_type}
        <div onClick="change_elements_type('{$_elements_type}')" id="element_group_option_{$_elements_type}" elements_type="{$_elements_type}" class="{if $_elements_type==$elements_type}selected{/if}">
            <i class="fa fw {if $_elements_type==$elements_type}fa-circle{else}fa-circle-o{/if}"></i> {$element_group['label']}
        </div>
    {/foreach}
</div>

<div id="f_options_chooser" class="hide panel popout_chooser">
    {foreach from=$f_options item=f_option key=_f_option}
        <div onClick="change_f_option(this)" id="element_group_option_{$_f_option}" f_field="{$_f_option}" class="{if $_f_option==$f_field}selected{/if}">
            <i class="fa fw {if $_f_option==$f_field}fa-circle{else}fa-circle-o{/if}"></i> <span class="label">{$f_option['label']}</span>
        </div>
    {/foreach}
</div>


<div id="columns_period_chooser" class="hide panel popout_chooser corner">
    {foreach from=$f_periods item=period_label key=_f_period}
        <div onClick="change_columns_period('{$_f_period}','{$period_label}')" id="element_group_option_{$_f_period}" elements_type="{$_f_period}" class="aright {if $f_period==$_f_period}selected{/if}">
            {$period_label} <i class="fa fw {if $f_period==$_f_period}fa-circle{else}fa-circle-o{/if} padding_left_10 padding_right_10"></i>
        </div>
    {/foreach}
</div>


<div id="columns_frequency_chooser" class="hide panel popout_chooser ">
    {foreach from=$frequencies item=frequency_label key=_f_frequency}
        <div onClick="change_columns_frequency('{$_f_frequency}','{$frequency_label}')" id="element_group_option_{$_f_frequency}" elements_type="{$_f_frequency}"
             class="aright {if $frequency==$_f_frequency}selected{/if}">
            {$frequency_label} <i class="fa fw {if $frequency==$_f_frequency}fa-circle{else}fa-circle-o{/if} padding_left_10 padding_right_10"></i>
        </div>
    {/foreach}

</div>
{if isset($aux_templates) }
    {foreach from=$aux_templates item=aux_template }
        {include file="$aux_template" }
    {/foreach}
{/if}


{if isset($table_top_template)}
    {include file=$table_top_template  }
{/if}

{if !isset($export_omega_invoices)}
    {assign 'export_omega_invoices' 'No' }

{/if}

<section  class="table_block {if isset($table_identification)}{$table_identification}}{/if}">

{if isset($period)  and   !isset($hide_period) }
    {include file="utils/date_chooser.tpl" period=$period from=$from to=$to from_mmddyy=$from_mmddyy  to_mmddyy=$to_mmddyy from_locale=$from_locale  to_locale=$to_locale  }
{/if}

{if isset($elements) and count($elements)>0     }
    <div id="elements" class="elements tabs ">
        <div id="element_type" class="{if count($elements)<2}hide {/if}" onClick="show_elements_types()"><i id="element_type_select_icon" class="fa fa-bars"></i></div>
        {foreach from=$elements item=element_group key=_elements_type}
            <div id="elements_group_{$_elements_type}" elements_type="$_elements_type" class="elements_group {if $_elements_type!=$elements_type}hide{/if}">
                {foreach from=$element_group['items']|@array_reverse item=element key=id}
                    <div id="element_{$id}" item_key="{$id}" class="element right  {if isset($element.selected) and $element.selected}selected{/if}"  data-item="{$id}"
                         title="{$elements[$elements_type]['label']}: {if isset($element.title)}{$element.title|strip_tags}{elseif isset($element.label)}{$element.label|strip_tags}{/if}">
                        <i id="element_checkbox_{$id}" class="far element_checkbox {if $element.selected}fa-check-square{else}fa-square{/if}"></i> <span class="label"> {$element.label}  </span>
                        <span class="qty" id="element_qty_{$id}"></span>
                    </div>
                {/foreach}
            </div>
        {/foreach}
    </div>
{/if}
{if isset($edit_table_dialog)}
    <div id="edit_table_dialog" class="hide edit_table_dialog"  data-metadata="{ }" >
        <div class="small button discreet" onclick="$('#edit_table_dialog').addClass('hide')" style="float:right;margin-left: 30px"><i class="fa fa-times"></i> {t}Close{/t}</div>

        <table>

            {if isset($edit_table_dialog.inline_edit) or isset($edit_table_dialog.spreadsheet_edit)}


            <tr>


                <td>{if isset($edit_table_dialog.labels.edit_items)}{$edit_table_dialog.labels.edit_items}{else}{t}Edit items{/t}{/if}</td>

            {if isset($edit_table_dialog.inline_edit)}
                <td class="button" onclick="table_edit_view()" ><i class="fa  fa-fw fa-i-cursor" aria-hidden="true"></i> {t}inline{/t}</td>
            {else}
                <td></td>
            {/if}
            {if isset($edit_table_dialog.spreadsheet_edit)}
            <td><i class="fa  fa-fw fa-table" aria-hidden="true"></i> {t}by{/t} <span onclick="show_download_edit_items_dialog(this)" class="marked_link">{t}spreadsheet{/t}</span></td>
            <td>
                <form method="post" action="/ar_edit.php" enctype="multipart/form-data" novalidate>
                    <input style="display:none" type="file" name="upload" id="table_edit_items_file_upload" class="table_input_file"
                           data-data='{ "tipo":"{$edit_table_dialog.spreadsheet_edit.tipo}","parent":"{$edit_table_dialog.spreadsheet_edit.parent}","parent_key":"{$edit_table_dialog.spreadsheet_edit.parent_key}", "object":"{$edit_table_dialog.spreadsheet_edit.object}","upload_type":"NewObjects"  }'
                    />
                    <label for="table_edit_items_file_upload"> <i class="fa fa-upload button" aria-hidden="true"></i></label>
                </form>
            </td>




            {else}
                <td></td> <td></td>
            {/if}


        </tr>
         {/if}
            {if isset($edit_table_dialog.variants) }

                <tr>
                    <td>
                        {t}Variants{/t}
                    </td>
                    <td></td>
                    <td><i class="fa  fa-fw fa-table" aria-hidden="true"></i> {t}by{/t} <a class="marked_link" href="/edit_variants.xls.php?id={$edit_table_dialog.variants.parent_key}">{t}spreadsheet{/t}</a></td>

                    <td>
                        <form method="post" action="/ar_edit.php" enctype="multipart/form-data" novalidate>

                            <input style="display:none" type="file" name="upload" id="table_edit_items_file_upload" class="table_input_file"
                                   data-data='{ "tipo":"{$edit_table_dialog.variants.tipo}","parent":"{$edit_table_dialog.variants.parent}","parent_key":"{$edit_table_dialog.variants.parent_key}", "object":"{$edit_table_dialog.variants.object}","upload_type":"NewObjects"  }'
                            />
                            <label for="table_edit_items_file_upload"> <i class="fa fa-upload button" aria-hidden="true"></i></label>
                        </form>

                    </td>
                </tr>

            {/if}




        {if isset($edit_table_dialog.new_item) or isset($edit_table_dialog.upload_items)}
        <tr>
            <td>{if isset($edit_table_dialog.labels.add_items)}{$edit_table_dialog.labels.add_items}{else}{t}Add items{/t}{/if}</td>

            {if isset($edit_table_dialog.new_item)}
            <td class="button" onclick="change_view('{$edit_table_dialog.new_item.reference}')"><i class="fa  fa-fw fa-server fa-flip-horizontal " aria-hidden="true"></i> {t}Online form{/t}
            </td>
            {else}
                <td></td>
            {/if}
            {if isset($edit_table_dialog.upload_items)}
            <td><i class="fa fa-fw fa-table" aria-hidden="true"></i> {t}from{/t} <a class="marked_link" href="{$edit_table_dialog.upload_items.template_url}" >{t}template{/t}</a>
            </td>
            <td>
                <form method="post" action="/ar_edit.php" enctype="multipart/form-data" novalidate>

                    <input style="display:none" type="file" name="upload" id="table_add_items_file_upload" class="table_input_file"
                           data-data='{ "tipo":"{$edit_table_dialog.upload_items.tipo}","parent":"{$edit_table_dialog.upload_items.parent}","parent_key":"{$edit_table_dialog.upload_items.parent_key}","object":"{$edit_table_dialog.upload_items.object}","upload_type":"NewObjects"  }'
                    />
                    <label for="table_add_items_file_upload"> <i class="fa fa-upload button" aria-hidden="true"></i></label>
                </form>



            </td>
                <td class="small {if $edit_table_dialog.upload_items.object!='supplier_part'}hide{/if}"><span class="button" title="{t}Check if you want to add a new supplier to an existing part{/t}" onclick="allow_duplicate_part_reference(this)"><i class="allow_duplicate_part_reference fal fa-square fa-fw " ></i> <span class="discreet">{t}Allow add to existing part{/t}</span></span></td>
            {else}
                <td></td> <td></td><td></td>
            {/if}
        </tr>
        {/if}
    </table>



        <script>
            function allow_duplicate_part_reference(element){

                var icon=$(element).find('i')
                if(icon.hasClass('fa-square')){
                    icon.removeClass('fa-square').addClass('fa-check-square').next('span').removeClass('discreet')
                }else{
                    icon.addClass('fa-square').removeClass('fa-check-square').next('span').addClass('discreet')

                }
            }
        </script>
        </div>
    <div style="position:absolute" class="export_dialog_container  ">


        <div  class="export_dialog export_dialog_block hide" >
            <table border=0 style="width:100%">
                <tr class="no_border">
                    <td class="export_progress_bar_container">
                        <a href="" class="download_export" ></a>
                        <span class="hide export_progress_bar_bg" ></span>
                        <div class="hide export_progress_bar"></div>
                        <div class="export_download hide"> {t}Download{/t}</div>
                    </td>
                    <td class="width_20">
                        <i  data-stop="0" onclick="stop_export(this)" class="stop_export fa button fa-hand-paper error hide" title="{t}Stop{/t}"></i>
                    </td>

                    {if  isset($edit_table_dialog.spreadsheet_edit)}
                        <td class="export_button link"  data-type="excel" onclick="get_editable_data(this)"

                            data-data='{ "parent_code":"{$edit_table_dialog.spreadsheet_edit.parent_code}","parent":"{$edit_table_dialog.spreadsheet_edit.parent}","parent_key":"{$edit_table_dialog.spreadsheet_edit.parent_key}","object":"{$edit_table_dialog.spreadsheet_edit.object}" }'><i class="fa fa-file-excel fa-fw" ></i><span class="excel">Excel</span><span class="csv hide">CSV</span
                        </td>
                    {else}
                        <td></td>
                    {/if}


                </tr>

                <tr>
                    <td>
                        <div onclick="hide_export_dialog($(this).closest('.export_dialog'))" class="button disabled"><i class="fa fa-times" title="{t}Close dialog{/t}"></i>{t}Close{/t}</div>
                    </td>
                    <td colspan="2" class="aright"><i onclick="open_export_config_left_button($(this).closest('.export_dialog_container').find('.export_dialog_config'))" class="fa fa-cogs button"></i></td>
                </tr>
            </table>
        </div>
        <div  class="export_dialog_config export_dialog_block hide" >


            <div class="export_type_options">
                <span onclick="change_export_as(this,'Excel')" class="margin_right_20 button" title="{t}Export as spreadsheet{/t}"><i class="fa fa-fw fa-file-excel" ></i>Excel</span>
                <span onclick="change_export_as(this,'CSV')" class="very_discreet button" title="{t}Export as CSV file{/t}"><i class="fa fa-fw fa-table" ></i>CSV</span>
            </div>

            {if isset($edit_fields)}
                <table>
                    <tr class="small_row ">
                        <td></td>
                        <td style="width_20" class="field_export ">
                            <i  onclick="toggle_all_export_fields(this)"
                               class="button fa-fw far fa-square"></i>
                        </td>
                    </tr>
                    <tbody class="export_fields">
                    {foreach from=$edit_fields item=export_field key=_key}
                        <tr class="small_row">
                            <td>{$export_field.label}</td>
                            <td style="width_20" class="field_export">
                                <i id="field_export_{$_key}" onclick="toggle_export_field(this)" key="{$_key}"
                                   class="button fa-fw far {if $export_field.checked }fa-check-square{else}fa-square{/if}"></i>
                            </td>
                        </tr>
                    {/foreach}
                    </tbody>
                </table>
            {/if}
        </div>
    </div>



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
        <div id="show_export_dialog_omega" class="left square_button  {if $export_omega_invoices=='No'}hide{/if}  " title="{t}Export for Omega accountancy software{/t}">
            <i onclick="export_omega_invoices(this)" class="fa fa-omega fa-fw"></i>
        </div>
        <div id="show_export_dialog" class="left square_button  {if !isset($export_fields)}hide{/if}  " title="{t}Export{/t}">
            <i onclick="show_export_dialog_left_button(this)" class="fa fa-download fa-fw"></i>
        </div>

        <div style="position:absolute" class="export_dialog_container  ">


            <div class="export_dialog export_dialog_block hide">
                <table border=0 style="width:100%">
                    <tr class="no_border">
                        <td class="export_progress_bar_container">
                            <a href="" class="download_export" download hidden></a>
                            <span class="hide export_progress_bar_bg"></span>
                            <div class="hide export_progress_bar"></div>
                            <div class="export_download hide"> {t}Download{/t}</div>
                        </td>
                        <td class="width_20">
                            <i data-stop="0" onclick="stop_export(this)" class="stop_export fa button fa-hand-paper error hide" title="{t}Stop{/t}"></i>
                        </td>
                        <td class="export_button link" data-type="excel" onclick="export_table(this)" data-ar_url="{if $version=='v2'}/ar_export_v2.php{else}/ar_export.php{/if}"><i class="fa fa-file-excel fa-fw"></i><span class="excel">Excel</span><span class="csv hide">CSV</span</td>
                    </tr>

                    <tr>
                        <td>
                            <div onclick="hide_export_dialog($(this).closest('.export_dialog'))" class="button disabled"><i class="fa fa-times" title="{t}Close dialog{/t}"></i>{t}Close{/t}</div>
                        </td>
                        <td colspan="2" class="aright"><i onclick="open_export_config_left_button($(this).closest('.export_dialog_container').find('.export_dialog_config'))" class="fa fa-cogs button"></i></td>
                    </tr>
                </table>
            </div>
            <div class="export_dialog_config export_dialog_block hide">


                <div class="export_type_options ">
                    <span data-type="excel" onclick="change_export_as(this,'Excel');export_table(this)" data-ar_url="{if $version=='v2'}/ar_export_v2.php{else}/ar_export.php{/if}" class="margin_right_20 button" title="{t}Export as spreadsheet{/t}"><i
                                class="far fa-fw fa-file-excel"></i>Excel</span>
                    <span data-type="csv" onclick="change_export_as(this,'Excel'); export_table(this)" data-ar_url="{if $version=='v2'}/ar_export_v2.php{else}/ar_export.php{/if}" class=" button" title="{t}Export as CSV file{/t}"><i class="far fa-fw fa-file-csv"></i>CSV</span>
                </div>

                {if isset($export_fields)}
                    <table>
                        <tr class="small_row ">
                            <td></td>
                            <td style="width_20" class="field_export ">
                                <i onclick="toggle_all_export_fields(this)"
                                   class="button fa-fw far fa-square"></i>
                            </td>
                        </tr>
                        <tbody class="export_fields">
                        {foreach from=$export_fields item=export_field key=_key}
                            <tr class="small_row">
                                <td>{$export_field.label}</td>
                                <td style="width_20" class="field_export">
                                    <i id="field_export_{$_key}" onclick="toggle_export_field(this)" key="{$_key}"
                                       class="button fa-fw far {if $export_field.checked }fa-check-square{else}fa-square{/if}"></i>
                                </td>
                            </tr>
                        {/foreach}
                        </tbody>
                    </table>
                {/if}
            </div>
        </div>


        <div id="filter_container" class="hide {if $f_field==''}hide{/if}">
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

        {include file="table_buttons.tpl"}

        {if isset($table_operation_msg)}
            <span class="{$table_operation_msg}" style="float:right;margin-right: 10px"></span>
        {/if}

        <span class="padding_left_10 rtext_info" id="rtext"></span>

    </div>

<div id="table_edit_control_panel" class="hide" style="padding:10px 5px;border-bottom:1px solid #ccc">
    <div style="float:left"><i class="far fa-square fa-fw button" style="padding:0px 5px 0px 0px" aria-hidden="true" onClick="select_all_rows(this)"></i> {t}Check/Uncheck all{/t}</div>
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
<div class="table {if !empty($table_class)}{$table_class}{/if}" id="table" data-metadata='{if isset($table_metadata)}{$table_metadata}{/if}'></div>
<script>


    var selected_checkbox ={ }

    {if isset($title)}
        $('#nav_title').html("{$title}")
    {/if}
    {if isset($view_position)}
    $('#view_position').html("{$view_position}")
    {/if}



    {include file="columns/`$tab`.cols.tpl" }


    var Row = Backbone.Model.extend({

    });



    var Rows = Backbone.PageableCollection.extend({




        model: Row, url: '{$request}', ar_file: '{$ar_file}', tipo: '{$tipo}', parameters: '{$parameters}', tab: '{$tab}', state: {
            pageSize: {$results_per_page}, sortKey: '{$sort_key}', order: parseInt({$sort_order})
        }, queryParams: {
            totalRecords: null, pageSize: "nr", sortKey: "o", order: "od"
        },


        parseState: function (resp, queryParams, state, options) {



            $('#rtext').html(resp.resultset.rtext)
            $('#table_buttons').removeClass('hide')


            if(resp.resultset.total_records==0){

                $('#table').addClass('hide')
                $('#show_export_dialog').addClass('hide')



            }else {

                $('#table').removeClass('hide')
                {if isset($export_fields)}
                $('#show_export_dialog').removeClass('hide')
                {/if}



                var total_pages = Math.ceil(resp.resultset.total_records / state.pageSize)



                if (total_pages > 0 && '{if isset($f_label)}{$f_label}{/if}' != '') {
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
                        $('#first_page').addClass('disabled')

                    } else if (rows.state.currentPage == 2) {

                        //   $('#first_page').addClass('disabled')

                    } else if (rows.state.currentPage == total_pages) {
                        $('#next_page').addClass('disabled')

                        $('#last_page').addClass('disabled')

                    }

                }

                $('th.ascending').removeClass('ascending')
                $('th.descending').removeClass('descending')

                if (resp.resultset.sort_dir == 'desc') {
                    $('th.' + resp.resultset.sort_key).addClass('descending')
                } else {
                    $('th.' + resp.resultset.sort_key).addClass('ascending')
                }
            }

            return {
                totalRecords: parseInt(resp.resultset.total_records), sortKey: resp.resultset.sort_key
            };
        },

        parseRecords: function (resp, options) {


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


    {if !empty($elements)}


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
            <i class="fa-fw {if $_elements_type==$elements_type}fa fa-circle{else}far fa-circle{/if}"></i> {$element_group['label']}
        </div>
    {/foreach}
</div>
<div id="f_options_chooser" class="hide panel popout_chooser">
    {foreach from=$f_options item=f_option key=_f_option}
        <div onClick="change_f_option(this)" id="element_group_option_{$_f_option}" f_field="{$_f_option}" class="{if $_f_option==$f_field}selected{/if}">
            <i class="fa-fw {if $_f_option==$f_field}fa fa-circle{else}far fa-circle{/if}"></i> <span class="label">{$f_option['label']}</span>
        </div>
    {/foreach}
</div>
<div id="columns_period_chooser" class="hide panel popout_chooser corner">
    {foreach from=$f_periods item=period_label key=_f_period}
        <div onClick="change_columns_period('{$_f_period}','{$period_label}')" id="element_group_option_{$_f_period}" elements_type="{$_f_period}" class="aright {if $f_period==$_f_period}selected{/if}">
            {$period_label} <i class="fa-fw {if $f_period==$_f_period}fa fa-circle{else}far fa-circle{/if} padding_left_10 padding_right_10"></i>
        </div>
    {/foreach}
</div>
<div id="columns_frequency_chooser" class="hide panel popout_chooser ">
    {foreach from=$frequencies item=frequency_label key=_f_frequency}
        <div onClick="change_columns_frequency('{$_f_frequency}','{$frequency_label}')" id="element_group_option_{$_f_frequency}" elements_type="{$_f_frequency}"
             class="aright {if $frequency==$_f_frequency}selected{/if}">
            {$frequency_label} <i class="fa-fw {if $frequency==$_f_frequency}fa fa-circle{else}far fa-circle-o{/if} padding_left_10 padding_right_10"></i>
        </div>
    {/foreach}

</div>
{if isset($aux_templates) }
    {foreach from=$aux_templates item=aux_template }
        {include file="$aux_template" }
    {/foreach}
{/if}

</section>

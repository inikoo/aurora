
{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 5 March 2021 at 14:13:00 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2020, Inikoo

 Version 3
-->
*}

<table border=0 id="raw_materials_list" class="{if $mode=='edit'}hide{/if}  ">
    <tr class="bold">
        <td></td>
        <td class="raw_materials_qty">{t}Qty{/t}</td>
        <td class="raw_materials_unit">{t}Unit{/t}</td>

        <td >{t}Code{/t}</td>
        <td style="width: 600px">{t}Description{/t}</td>

        <td class="notes hide">{t}Notes{/t}</td>
    </tr>
    <tbody id="raw_materials_list_items">
    {include file="raw_materials_list_items.edit.tpl" raw_materials_list=$raw_materials_list}
    </tbody>
    <tr id="new_raw_material_clone" class="raw_material_tr new in_process hide">
        <td>
            <i class="fa fa-trash button" aria-hidden="true" onclick="remove_raw_material(this)"></i>
            <input type="hidden" class="raw_materials_list_value production_part_raw_material_key" value="" ovalue="">
        </td>



        <td style="text-align: right" >
            <input style="width:80px;" class="raw_materials_list_value raw_material_qty" value="1" ovalue="1"/>
        </td>
        <td style="padding-right: 20px;min-width: 100px" class="raw_materials_unit_label"></td>

        <td style="width: 160px" class="raw_materials">
            <input type="hidden" class="raw_materials_list_value raw_material_key" value="" ovalue="">
            <span class="Raw_Material_Code hide"></span>
            <input  style="width: 150px" class="Raw_Material_Code_value" value="" ovalue="" placeholder="{t}Raw material code{/t}" parent_key="1" parent="account" scope="raw_materials">

            <div class="search_results_container">
                <table class="results" border="1">
                    <tr class="hide search_result_template" field="" value="" formatted_value="" data-unit
                        onclick="select_dropdown_raw_material(this)">
                        <td class="code"></td>
                        <td style="width:85%" class="label"></td>
                    </tr>
                </table>
            </div>
        </td>


        <td class="raw_materials_description" style="width: 600px"></td>

        <td class="hide notes"><input class="raw_materials_list_value note" value="" ovalue="" placeholder="{t}Note{/t}">
        </td>

    </tr>
    <tr class="add_new_raw_material_tr">
        <td colspan=4><span onclick="add_raw_material()" class="button">{t}Add a raw material{/t} <i class="fa fa-plus"></i></span></td>
        <td class="aright padding_right_20"><i id="Raw_Materials_save_button" onclick="save_this_raw_materials_list()"
                                               class="fa fa-cloud save {if $mode=='new'}hide{/if} "></i></td>
    </tr>
</table>

<script>
    {if $mode=='new'}

    add_raw_material()
    {/if}

    function remove_raw_material(element) {

        if ($(element).closest('tr').hasClass('new')) {

            $(element).closest('tr').remove()


        } else {
            if ($(element).closest('tr').hasClass('very_discreet')) {

                $(element).closest('tr').removeClass('very_discreet')
                $(element).closest('tr').find('.Raw_Material_Code').removeClass('deleted')
                $(element).closest('tr').find('.raw_materials_list_value').prop('readonly', false);

            } else {
                $(element).closest('tr').addClass('very_discreet')
                $(element).closest('tr').find('.Raw_Material_Code').addClass('deleted')
                $(element).closest('tr').find('.raw_materials_list_value').prop('readonly', true);
            }
        }

        on_change_raw_materials_list()

    }


    function delayed_on_change_raw_materials_dropdown_select_field(object, timeout) {
        //var field = object.attr('id');
        //var field_element = $('#' + field);
        var new_value = $(object).val()


        /*
         key_scope = {
         type: 'dropdown_select',
         field: field_element.attr('field')
         };
         */

        window.clearTimeout(object.data("timeout"));

        object.data("timeout", setTimeout(function () {

            get_raw_materials_dropdown_select(object, new_value)
        }, timeout));
    }

    function get_raw_materials_dropdown_select(object, new_value) {

        var parent_key = $(object).attr('parent_key')
        var parent = $(object).attr('parent')
        var scope = $(object).attr('scope')


        var request = '/ar_find.php?tipo=find_objects&query=' + fixedEncodeURIComponent(new_value) + '&scope=' + scope + '&parent=' + parent + '&parent_key=' + parent_key + '&state=' + JSON.stringify(state)
        //console.log(request)
        $.getJSON(request, function (data) {

            var results_container = $(object).closest('td.raw_materials').find('.search_results_container')


            if (data.number_results > 0) {
                results_container.removeClass('hide').addClass('show')
            } else {


                results_container.addClass('hide').removeClass('show')
                //  $('#' + field).val('')
                // on_changed_value(field, '')
            }


            $(" .result").remove();

            var first = true;

            for (var result_key in data.results) {

                var clone = results_container.find('.search_result_template').clone()
                //         clone.prop('id', field + '_result_' + result_key);


                clone.addClass('result').removeClass('hide search_result_template')
                clone.attr('value', data.results[result_key].value)
                clone.attr('formatted_value', data.results[result_key].formatted_value)


                clone.data('metadata', data.results[result_key].metadata)


                //    clone.attr('field', field)
                if (first) {
                    clone.addClass('selected')
                    first = false
                }

                clone.children(".code").html(data.results[result_key].code)

                clone.children(".label").html(data.results[result_key].description)



                results_container.find(".results").append(clone)

            }

        })


    }

    function select_dropdown_raw_material(element) {

        $(element).closest('.raw_material_tr').removeClass('in_process')


        var td_raw_materials=$(element).closest('td.raw_materials')

        td_raw_materials.find('.Raw_Material_Code').html($(element).attr('formatted_value')).removeClass('hide')
        td_raw_materials.find('.Raw_Material_Code_value').remove()

        td_raw_materials.find('.raw_material_key').val($(element).attr('value'))



        $(element).closest('.raw_material_tr').find('.raw_materials_unit_label').html($(element).data('metadata').unit_label)
        $(element).closest('.raw_material_tr').find('.raw_materials_description').html($(element).data('metadata').description)




        td_raw_materials.find('.search_results_container').remove()





        on_change_raw_materials_list()

    }

    $("#raw_materials_list_items").on("input.Raw_Material_Code_value propertychange", function (evt) {


        var delay = 100;
        if (window.event && event.type == "propertychange" && event.propertyName != "value") return;
        delayed_on_change_raw_materials_dropdown_select_field($(evt.target), delay)
    });


    function add_raw_material() {

        var clone = $('#new_raw_material_clone').clone()
        clone.prop('id', '')
        clone.removeClass('hide')
        console.log(clone)
        $("#raw_materials_list_items").append(clone);
        on_change_raw_materials_list()

    }

    function save_this_raw_materials_list() {
        save_field($('#fields').attr('object'), $('#fields').attr('key'), 'Raw_Materials')
    }


    $(document).on('input propertychange', '.raw_materials_list_value', function (evt) {
        on_change_raw_materials_list()
    });

    function on_change_raw_materials_list(element) {

        var changed = check_changes_raw_materials_list()
        var validation = validate_raw_materials_list()


        var count_raw_materials = 0;
        $('#raw_materials_list_items  tr.raw_material_tr').each(function (i, obj) {
            count_raw_materials++;
        });




        $("#Raw_Materials_field").removeClass('valid invalid')

        if (changed) {
            $("#Raw_Materials_field").addClass('changed')

        } else {
            $("#Raw_Materials_field").removeClass('changed')
        }

        $("#Raw_Materials_field").addClass(validation)
    }


    function check_changes_raw_materials_list() {

        var changed = false;
        $('#raw_materials_list  input.raw_materials_list_value').each(function (i, obj) {
            if ($(obj).val() != $(obj).attr('ovalue')) {
                changed = true;
                return false;
            }
        });

        $('#raw_materials_list_items  tr.raw_material_tr').each(function (i, obj) {

            if ($(obj).hasClass('very_discreet')) {
                changed = true;
                return false;
            } else {
                if ($(obj).hasClass('new') && !$(obj).hasClass('in_process')) {
                    changed = true;
                    return false;
                }

            }
        })

        return changed

    }

    function validate_raw_materials_list() {
        var validation = 'valid';

        $('#raw_materials_list_items  tr.raw_material_tr').each(function (i, obj) {

            var ratio = $(obj).find('.raw_material_qty').val()

            if (ratio != '') {
                ratio_validation = validate_number(ratio, 0,1000000)

                if (ratio_validation.class == 'invalid') {
                    validation = 'invalid';
                }

            }


        })
        console.log(validation)

        return validation;
    }


    function post_save_raw_materials_list(data) {
        $('raw_materials_list_items').html(data.update_metadata.raw_materials_list_items)
    }

</script>
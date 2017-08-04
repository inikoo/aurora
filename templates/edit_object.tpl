<div id="fields" class="edit_object" object="{$state._object->get_object_name()}" key="{$state.key}" form_type="{if isset($form_type)}{$form_type}{else}edit{/if}">
    <span id="invalid_msg" class="hide">{t}Invalid value{/t}</span>

    {if isset($preferred_countries)}
        <input id="preferred_countries" type="hidden" value="{$preferred_countries}">
    {/if}
    <table id="edit_container" border=0
           data-default_telephone_data="{if isset($default_telephone_data)}{$default_telephone_data}{/if}">
        {foreach from=$object_fields item=field_group }

            {if isset($field_group.class)}{assign "field_group_class" $field_group.class}{else}{assign "field_group_class" ""}{/if}
            <tr class="title {$field_group_class}">
                <td colspan=3>{$field_group.label}</td>
            </tr>
            {if isset($field_group.class)}{assign "field_class" $field_group.class}{else}{assign "field_class" ""}{/if}
            {if $field_class=='links'}
                {foreach from=$field_group.fields item=field name=fields}
                    {if isset($field.render)}{assign "render" $field.render}{else}{assign "render" true}{/if}
                    <tr class="link {if !$render}hide{/if}" onClick="change_view('{$field.reference}')">
                        <td colspan=3><i style="margin-right:10px" class="fa fa-link"></i> {$field.label}</td>
                    </tr>
                {/foreach}

            {else}
                {foreach from=$field_group.fields item=field name=fields}
                    {if isset($field.edit)}{assign "edit" $field.edit}{else}{assign "edit" ""}{/if}
                    {if isset($field.field_type)}{assign "field_type" $field.field_type}{else}{assign "field_type" $edit}{/if}

                    {if isset($field.class)}{assign "class" $field.class}{else}{assign "class" ""}{/if}
                    {if isset($field.render)}{assign "render" $field.render}{else}{assign "render" true}{/if}
                    {if isset($field.linked)}{assign "linked" $field.linked}{else}{assign "linked" ""}{/if}
                    {if isset($field.required)}{assign "required" $field.required}{else}{assign "required" true}{/if}
                    {if isset($field.server_validation)}{assign "server_validation" $field.server_validation}{else}{assign "server_validation" ""}{/if}
                    {if isset($field.invalid_msg)}{assign "invalid_msg" $field.invalid_msg}{else}{assign "invalid_msg" ""}{/if}

                    {if $class=='directory'}
                        <tr id="{$field.id}_field" class="{if !$render}hide{/if}  ">
                            <td class="label">{$field.label}</td>
                            <td colspan="3" id="{$field.id}_directory" class="with_vertical_padding">{$field.formatted_value}</td>
                        </tr>
                    {elseif $class=='operation'}
                        <tr id="{$field.id}_field" class="{if !$render}hide{/if} " style="height:auto;padding:10px" >
                            <td colspan="3" class="label" style="height:auto;padding:10px" >{$field.label}</td>
                        </tr>




                    {elseif $class=='operation_date_interval'}
                        <tr id="{$field.id}_field" class="{if !$render}hide{/if} ">
                            <td class="label">{$field.label}</td>
                            <td></td>
                            <td>

                        <div class="date_chooser_in_edit hide">

                                <input id="select_interval_from" type="hidden" value="{$field.from_mmddyy}" has_been_valid="0"/>

                                <input id="select_interval_to" type="hidden" value="{$field.to_mmddyy}" has_been_valid="0"/>



                                <div id="select_interval_control_panel" class="">
                                    <div id="select_interval_datepicker_edit_object" class="datepicker" style="float:left">
                                    </div>
                                    <div class="date_chooser_form">
                                        <div class="label from">{t}From{/t}</div>
                                        <input id="select_interval_from_formatted" style="width:100px" class="" value="{$field.from_locale}" readonly/>
                                        <div class="label until">{t}Until{/t}</div>
                                        <input id="select_interval_to_formatted" style="width:100px"  class="" value="{$field.to_locale}" readonly/>
                                        <i onclick="submit_interval()" id="select_interval_save" class="fa button fa-play save"></i>
                                    </div>
                                    <div style="clear:both"></div>
                                </div>
                            </div>
                            </td>
                        </tr>


                    <script>


                $(function () {


                    $("#select_interval_datepicker_edit_object").datepicker({

                        altFormat: "yy-mm-dd",
                        defaultDate: new Date("{$field.from}"),

                        numberOfMonths: 2,



                        beforeShowDay: function (date) {

                           // console.log( $.datepicker._defaults.dateFormat)
                          //  console.log( $("#select_interval_from").val())

                            var date1 = $.datepicker.parseDate($.datepicker._defaults.dateFormat, $("#select_interval_from").val());
                            var date2 = $.datepicker.parseDate($.datepicker._defaults.dateFormat, $("#select_interval_to").val());
                            return [true, date1 && ((date.getTime() == date1.getTime()) || (date2 && date >= date1 && date <= date2)) ? "dp-highlight" : ""];
                        },
                        onSelect: function (dateText, inst) {
                            var date1 = $.datepicker.parseDate($.datepicker._defaults.dateFormat, $("#select_interval_from").val());
                            var date2 = $.datepicker.parseDate($.datepicker._defaults.dateFormat, $("#select_interval_to").val());
                            var selectedDate = $.datepicker.parseDate($.datepicker._defaults.dateFormat, dateText);

                            date_iso_formatted = $.datepicker.formatDate("yy-mm-dd", $(this).datepicker("getDate"))
                            date_formatted = $.datepicker.formatDate("dd/mm/yy", $(this).datepicker("getDate"))

                            if (!date1 || date2) {
                                $("#select_interval_from").val(dateText);
                                $("#select_interval_to").val("");
                                $("#select_interval_from_formatted").val(date_formatted);
                                $("#select_interval_to_formatted").val('');

                                $(this).datepicker();
                            } else if (selectedDate < date1) {
                                $("#select_interval_to").val($("#select_interval_from").val());
                                $("#select_interval_from").val(dateText);

                                $("#select_interval_to_formatted").val($("#select_interval_from_formatted").val());
                                $("#select_interval_from_formatted").val(date_formatted);

                                $(this).datepicker();
                            } else {
                                $("#select_interval_to").val(dateText);
                                $("#select_interval_to_formatted").val(date_formatted);

                                $(this).datepicker();
                            }


                            validate_interval()
                        }


                    });

                })


                function validate_interval() {
                    $('#select_interval_save').removeClass('possible_valid valid invalid')

                    if ($("#select_interval_from_formatted").val() == '' || $("#select_interval_to_formatted").val() == '') {
                        validation = 'possible_valid';
                    } else {
                        validation = 'valid';

                    }
                    $('#select_interval_save').addClass(validation)

                }


                function submit_interval() {
                    if ($('#select_interval_save').hasClass('valid')) {

                        var request = '/ar_edit_employees.php?tipo=edit_field&object=' + object + '&key=' + key + '&field=' + field + '&value=' + fixedEncodeURIComponent(value) + '&metadata=' + JSON.stringify(metadata)
                        //  console.log(request)

                        $.getJSON(request, function (data) {

                            $('#' + field + '_save_button').addClass('fa-cloud').removeClass('fa-spinner fa-spin')
                            if (data.state == 100) {
                                pre_save_actions(field, data)

                            }
                            if (data.state == 200) {


                                //  console.log(data)

                                $('#' + field + '_msg').html(data.msg).addClass('success').removeClass('hide')
                                $('#' + field + '_value').val(data.value)


                                $("#" + field + '_field').removeClass('changed')
                                $("#" + field + '_field').removeClass('valid')


//            console.log(data.formatted_value)
                                $('.' + field).html(data.formatted_value)
                                if (type == 'option') {

                                    //   $('#' + field + '_options li .current_mark')
                                    $('#' + field + '_options li i.current_mark').removeClass('current')
                                    $('#' + field + '_options li.selected  i.current_mark').addClass('current')

                                    // console.log('#' + field + '_option_' + value + ' .current_mark')
                                    //  $('#' + field + '_option_' + value + ' .current_mark').addClass('current')
                                } else if (type == 'option_multiple_choices') {
                                    $('#' + field + '_options li .current_mark').removeClass('current')
                                    $('#' + field + '_option_' + value + ' .current_mark').addClass('current')
                                } else if (type == 'dropdown_select') {
                                    //  $('#' + field + '').removeClass('current')
                                    $('#' + field + '_msg').html(data.msg).addClass('success').removeClass('hide')
                                } else {
                                    $('#' + field + '_msg').html(data.msg).addClass('success').removeClass('hide')
                                }

                                if (data.action == 'deleted') {
                                    $('#' + field + '_edit_button').parent('.show_buttons').css('visibility', 'hidden')
                                    $('#' + field + '_label').find('.button').addClass('hide')
                                }

                                if (data.directory_field != '') {
                                    $('#' + data.directory_field + '_directory').html(data.directory)
                                    if (data.items_in_directory == 0) {
                                        $('#' + data.directory_field + '_field').addClass('hide')
                                    } else {
                                        $('#' + data.directory_field + '_field').removeClass('hide')
                                    }
                                }
                                if (data.action == 'new_field') {
                                    if (data.new_fields) {
                                        for (var key in data.new_fields) {
                                            create_new_field(data.new_fields[key])
                                        }
                                    }
                                }


                                close_edit_field(field)

                                if (data.other_fields) {
                                    for (var key in data.other_fields) {
                                        update_field(data.other_fields[key])
                                    }
                                }

                                if (data.deleted_fields) {
                                    for (var key in data.deleted_fields) {
                                        delete_field(data.deleted_fields[key])
                                    }
                                }

                                for (var key in data.update_metadata.class_html) {
                                    $('.' + key).html(data.update_metadata.class_html[key])
                                }

                                post_save_actions(field, data)

                            } else if (data.state == 400) {
                                $('#' + field + '_editor').removeClass('valid potentially_valid').addClass('invalid')

                                $('#' + field + '_msg').html(data.msg).removeClass('hide')

                            }
                        })

                    }

                }


            </script>

                    {else}
                        <tr id="{$field.id}_field" field="{$field.id}" class="{if $smarty.foreach.fields.last}last{/if} {if !$render}hide{/if}  {$class} "
                            {if $class=='new' and $field.reference!=''}onClick="change_view('{$field.reference}')"{/if} >
                            <td id="{$field.id}_label" class="label"><span>{$field.label}</span>
                                {if $edit=='editor'}
                                    <span id="{$field.id}_msg" class="msg"></span>
                                {/if}
                            </td>
                            <td class="show_buttons  {if $edit=='address'}address {/if}">

                                <i id="{$field.id}_lock"
                                   class="fa fa-lock fw {if $edit!='' or $class=='new'  or $class=='operation'  }hide{/if} edit lock"></i>
                                <i class="fa fa-lock fw {if !$linked  }hide{/if} edit"></i>

                                <i id="{$field.id}_reset_button"
                                   class="fa fa-sign-out fa-flip-horizontal fw reset hide reset_button"
                                   onclick="close_edit_this_field(this)"></i>
                                <i id="{$field.id}_edit_button"
                                   class="fa fa-pencil fw edit {if $edit=='' or $linked!=''}hide{/if} edit_button"
                                   onclick="open_edit_this_field(this)"></i>

                            </td>
                            <td id="{$field.id}_container" class="container value  " _required="{$required}"
                                field_type='{$field_type}' server_validation='{$server_validation}'
                                object='{$state._object->get_object_name()}' key='{$state.key}' parent='{$state.parent}'
                                parent_key='{$state.parent_key}'>

                                {if $edit=='editor'}
                                    <div id="{$field.id}_formatted_value"
                                         class="{$field.id} {$edit} fr-view  formatted_value "
                                         ondblclick="open_edit_this_field(this)">{$field.formatted_value}</div>
                                {else}
                                     <span id="{$field.id}_formatted_value" class="{$field.id} {$edit} formatted_value " ondblclick="open_edit_this_field(this)">{if isset($field.formatted_value)}{$field.formatted_value}{else}{$field.value}{/if}</span>
                                {/if}
                                <input id="{$field.id}_value" type='hidden' class="unformatted_value" value="{$field.value}"/>


                                {if $edit=='string' or   $edit=='dimensions' or $edit=='handle' or  $edit=='email' or $edit=='new_email' or  $edit=='int_unsigned' or $edit=='smallint_unsigned' or $edit=='mediumint_unsigned' or $edit=='int' or   $edit=='percentage' or $edit=='smallint' or $edit=='mediumint' or $edit=='anything' or $edit=='numeric_unsigned'  or $edit=='numeric'  or $edit=='amount'  or $edit=='amount_margin'  or $edit=='amount_percentage'}
                                    <input id="{$field.id}" class="input_field hide" value="{$field.value}"
                                           has_been_valid="0"
                                           {if isset($field.placeholder)}placeholder="{$field.placeholder}"{/if} />
                                    <i id="{$field.id}_save_button" class="fa fa-cloud save {$edit} hide"
                                       onclick="save_this_field(this)"></i>
                                    <span id="{$field.id}_msg" class="msg"></span>
                                    <span id="{$field.id}_info" class="hide"></span>


                                {elseif $edit=='barcode'  }
                                    <span id="{$field.id}_assign_available_barcode" class="fa-stack fa-lg button hide"
                                          available_barcodes="{$available_barcodes}"
                                          title="{t}Assign next available barcode{/t}"
                                          onClick="assign_available_barcode('{$field.id}')">
  <i class="fa fa-barcode fa-stack-1x"></i>
  <i class="fa fa-bolt fa-inverse fa-stack-1x"></i>
</span>
                                    <input id="{$field.id}" class="input_field hide" value="{$field.value}"
                                           has_been_valid="0"
                                           {if isset($field.placeholder)}placeholder="{$field.placeholder}"{/if} />
                                    <i id="{$field.id}_save_button" class="fa fa-cloud save {$edit} hide"
                                       onclick="save_this_field(this)"></i>
                                    <span id="{$field.id}_msg" class="msg"></span>
                                    <span id="{$field.id}_info" class="hide"></span>
                                {elseif $edit=='telephone'  or $edit=='new_telephone' }
                                    <input id="{$field.id}" class="input_field telephone_input_field hide" value=""
                                           has_been_valid="0"/>
                                    <i id="{$field.id}_save_button" class="fa fa-cloud  save {$edit} hide"
                                       onclick="save_this_field(this)"></i>
                                    <span id="{$field.id}_msg" class="msg"></span>
                                {if !isset($field.clone_template)}
                                    <script>

                                        var default_telephone_data = JSON.parse(atob($('#edit_container').data("default_telephone_data")))

                                        //console.log(default_telephone_data.default_country)
                                        $("#{$field.id}").intlTelInput(
                                                {
                                                    utilsScript: "/js/libs/telephone_utils.js",
                                                    numberType: {if isset($field.mobile)}'MOBILE'
                                                    {else}'FIXED_LINE'{/if},
                                                    initialCountry: default_telephone_data.default_country,
                                                    preferredCountries: default_telephone_data.preferred_countries
                                                }
                                        );
                                        $("#{$field.id}").intlTelInput("setNumber", "{$field.value}");

                                        $("#{$field.id}").on("countrychange", function (e, countryData) {
                                            on_changed_value('{$field.id}', $('#{$field.id}').intlTelInput("getNumber"))


                                            update_related_fields(countryData)

                                        });

                                    </script>
                                {/if}

                                {elseif $edit=='editor'  }
                                    <div id="editor_container_{$field.id}" class="hide">
                                        {include file="editor.tpl" editor_data=$field.editor_data }
                                    </div>
                                {elseif $edit=='upload'  }
                                    <div id="edit_object_upload_{$field.id}" class="hide">
                                        {include file="edit_object_upload.tpl" upload_data=$field.upload_data field=$field.id}
                                    </div>
                                {elseif $edit=='country_select'  }
                                    <input id="{$field.id}" class="input_field hide width_500" value=""/>
                                    <i id="{$field.id}_save_button" class="fa fa-cloud save {$edit} hide"
                                       onclick="save_this_field(this)"></i>
                                    <span id="{$field.id}_msg" class="msg"></span>
                                    <span id="{$field.id}_info" class="hide"></span>
                                    <script>
                                        $.fn.countrySelect.setCountryData({$field.options});
                                        $("#{$field.id}").countrySelect();
                                        $("#{$field.id}").countrySelect("selectCountryfromCode", '{$field.value}');
                                        $("#{$field.id}").on("change", function (event, arg) {
                                            on_changed_value('{$field.id}', $("#{$field.id}").countrySelect("getSelectedCountryData").code)
                                        })

                                    </script>
                                {elseif $edit=='dropdown_select'  }
                                    <input id="{$field.id}" type="hidden" class=" input_field" value="{$field.value}"
                                           has_been_valid="0"/>
                                    <input id="{$field.id}_dropdown_select_label" field="{$field.id}"
                                           scope="{$field.scope}" parent="{$field.parent}"
                                           parent_key="{$field.parent_key}" class="hide dropdown_select"
                                           value="{$field.formatted_value}" has_been_valid="0"
                                           placeholder="{if isset($field.placeholder)}{$field.placeholder}{/if}"/>
                                    <span id="{$field.id}_msg" class="msg"></span>
                                    <i id="{$field.id}_save_button" class="fa fa-cloud save {$edit} hide"
                                       onclick="save_this_field(this)"></i>
                                    <div id="{$field.id}_results_container" class="search_results_container">

                                        <table id="{$field.id}_results" border="0">
                                            <tr class="hide" id="{$field.id}_search_result_template" field="" value=""
                                                formatted_value="" onClick="select_dropdown_option(this)">
                                                <td class="code"></td>
                                                <td style="width:85%" class="label"></td>

                                            </tr>
                                        </table>

                                    </div>
                                    <script>
                                        $("#{$field.id}_dropdown_select_label").on("input propertychange", function (evt) {

                                            var delay = 100;
                                            if (window.event && event.type == "propertychange" && event.propertyName != "value") return;
                                            delayed_on_change_dropdown_select_field($(this), delay)
                                        });
                                    </script>
                                {elseif $edit=='working_hours'  }
                                    {include file="working_hours.edit.tpl" field=$field working_hours=$working_hours }
                                {elseif $edit=='salary'  }
                                    {include file="salary.edit.tpl" field=$field salary=$salary }
                                {elseif $edit=='parts_list'  }

                                    {include file="parts_list.edit.tpl" field=$field parts_list=$object->get_parts_data(true) mode='edit'}

                                {elseif $edit=='webpage_see_also'  }
                                    <div class="webpage_see_also_editor">
                                        {include file="webpage_see_also.edit.tpl"  data=$object->get_see_also_data() mode='edit'}
                                    </div>
                                {elseif $edit=='webpage_related_products'  }
                                    <div class="webpage_related_products_editor">
                                        {include file="webpage_related_products.edit.tpl"  data=$object->get_related_products_data() mode='edit'}
                                    </div>
                                {elseif $edit=='textarea'  }
                                    <textarea id="{$field.id}" class="input_field hide"
                                              has_been_valid="0">{$field.value}</textarea>
                                    <i id="{$field.id}_save_button" class="fa fa-cloud  save {$edit} hide"
                                       onclick="save_field('{$state._object->get_object_name()}','{$state.key}','{$field.id}')"></i>
                                    <span id="{$field.id}_msg" class="msg"></span>
                                {elseif $edit=='html_editor'  }
                                    <div>
                                        <i id="{$field.id}_save_button" class="fa fa-cloud  save {$edit} hide"
                                           onclick="save_field('{$state._object->get_object_name()}','{$state.key}','{$field.id}')"></i>
                                        <span id="{$field.id}_msg" class="msg"></span>
                                    </div>
                                    <div>
                                        <textarea id="{$field.id}" class="input_field hide" style="width:90%"
                                                  has_been_valid="0">{$field.value}</textarea>
                                    </div>
                                {elseif $edit=='address'  or $edit=='new_delivery_address' or $edit=='address_to_clone' }
                                    <div class="address_edit_fields_container">
                                        <table id="{$field.id}" border=0 class="address hide" field="{$field.id}">

                                            <tr id="{$field.id}_recipient" class="recipient">
                                                <td class="show_buttons error super_discreet"><i class="fa fa-asterisk"></i></td>
                                                <td class="label">{t}Recipient{/t}</td>
                                                <td><input value="" class="address_input_field" field_name="Address Recipient"></td>
                                            </tr>
                                            <tr id="{$field.id}_organization" class="organization">
                                                <td class="show_buttons error super_discreet"><i class="fa fa-asterisk"></i></td>
                                                <td class="label">{t}Organization{/t}</td>
                                                <td><input value="" class="address_input_field" field_name="Address Organization"></td>
                                            </tr>
                                            <tr id="{$field.id}_addressLine1" class="addressLine1">
                                                <td class="show_buttons error super_discreet"><i class="fa fa-asterisk"></i></td>
                                                <td>{t}Line 1{/t}</td>
                                                <td><input value="" class="address_input_field" field_name="Address Line 1"></td>
                                            </tr>

                                            <tr id="{$field.id}_addressLine2" class="addressLine2">
                                                <td class="show_buttons error super_discreet"><i class="fa fa-asterisk"></i></td>
                                                <td class="label">{t}Line 2{/t}</td>
                                                <td><input value="" class="address_input_field" field_name="Address Line 2"></td>
                                            </tr>
                                            <tr id="{$field.id}_sortingCode" class="sortingCode">
                                                <td class="show_buttons error super_discreet"><i class="fa fa-asterisk"></i></td>
                                                <td class="label">{t}Sorting code{/t}</td>
                                                <td><input value="" class="address_input_field" field_name="Address Sorting Code"></td>
                                            </tr>

                                            <tr id="{$field.id}_postalCode" class="postalCode">
                                                <td class="show_buttons error super_discreet"><i class="fa fa-asterisk"></i></td>
                                                <td class="label">{t}Postal code{/t}</td>
                                                <td><input value="" class="address_input_field" field_name="Address Postal Code"></td>
                                            </tr>
                                            <tr id="{$field.id}_dependentLocality" class="dependentLocality">
                                                <td class="show_buttons error super_discreet"><i class="fa fa-asterisk"></i></td>
                                                <td class="label">{t}Dependent locality{/t}</td>
                                                <td><input value="" class="address_input_field" field_name="Address Dependent Locality"></td>
                                            </tr>
                                            <tr id="{$field.id}_locality" class="locality">
                                                <td class="show_buttons error super_discreet"><i class="fa fa-asterisk"></i></td>
                                                <td class="label">{t}Locality (City){/t}</td>
                                                <td><input value="" class="address_input_field"
                                                           field_name="Address Locality"></td>
                                            </tr>
                                            <tr id="{$field.id}_administrativeArea" class="administrativeArea">
                                                <td class="show_buttons error super_discreet"><i class="fa fa-asterisk"></i></td>
                                                <td class="label">{t}Administrative area{/t}</td>
                                                <td><input value="" class="address_input_field" field_name="Address Administrative Area"></td>
                                            </tr>
                                            <tr id="{$field.id}_country" class="country">
                                                <td class="show_buttons error super_discreet"><i
                                                            class="fa fa-asterisk"></i></td>
                                                <td class="label">{t}Country{/t}</td>
                                                <td>
                                                    <input value="" class="address_input_field" type="hidden" field_name="Address Country 2 Alpha Code">
                                                    <input id="{$field.id}_country_select" value="" class="country_select">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan=2></td>
                                                <td>
                                                    <i id="{$field.id}_save_button" class="fa fa-cloud save {$edit}"
                                                       onclick="save_this_address(this)"></i>

                                                </td>
                                            </tr>

                                        </table>
                                    </div>
                                    <div><span id="{$field.id}_msg" class="msg"></span></div>
                                {if  $edit!='address_to_clone'}
                                    <script>


                                        {if $edit=='address' and  $field.value!='' }



                                        var address_fields = jQuery.parseJSON($('#{$field.id}_value').val())





                                        $('#{$field.id}_recipient  input ').val(decodeEntities(address_fields['Address Recipient']))

                                        $('#{$field.id}_organization  input ').val(decodeEntities(address_fields['Address Organization']))
                                        $('#{$field.id}_addressLine1  input ').val(decodeEntities(address_fields['Address Line 1']))
                                        $('#{$field.id}_addressLine2  input ').val(decodeEntities(address_fields['Address Line 2']))
                                        $('#{$field.id}_sortingCode  input ').val(decodeEntities(address_fields['Address Sorting Code']))
                                        $('#{$field.id}_postalCode  input ').val(decodeEntities(address_fields['Address Postal Code']))
                                        $('#{$field.id}_dependentLocality  input ').val(decodeEntities(address_fields['Address Dependent Locality']))
                                        $('#{$field.id}_locality  input ').val(decodeEntities(address_fields['Address Locality']))
                                        $('#{$field.id}_administrativeArea  input ').val(decodeEntities(address_fields['Address Administrative Area']))

                                        var initial_country = address_fields['Address Country 2 Alpha Code'].toLowerCase();

                                        {else}

                                        var initial_country = '{$default_country|lower}';
                                        {/if}

                                        //caca
                                        $.fn.countrySelect.setCountryData({$field.countries});

                                        var {$field.id}country_select = $("#{$field.id}_country_select")

                                        {$field.id}country_select.countrySelect();
                                        {$field.id}country_select.countrySelect("selectCountry", initial_country);

                                        update_address_fields('{$field.id}', initial_country, hide_recipient_fields = false)
                                        $('#{$field.id}_country  input.address_input_field ').val(initial_country.toUpperCase())

                                        {$field.id}country_select.on("change", function (event, arg) {




                                            var country_code = {$field.id}country_select.countrySelect("getSelectedCountryData").iso2

                                            console.log(country_code)

                                            update_address_fields('{$field.id}', country_code, hide_recipient_fields = false)
                                            $('#{$field.id}_country  input.address_input_field ').val(country_code.toUpperCase())
                                            on_changed_address_value("{$field.id}", '{$field.id}_country', country_code)

                                        })

                                    </script>
                                {/if}




                                {elseif $edit=='pin' or  $edit=='password'}
                                    <input id="{$field.id}" type="password" class="input_field hide"
                                           value="{$field.value}" has_been_valid="0"/>
                                    <i id="{$field.id}_save_button" class="fa fa-cloud  save {$edit} hide"
                                       onclick="save_field('{$state._object->get_object_name()}','{$state.key}','{$field.id}')"></i>
                                    <span id="{$field.id}_msg" class="msg"></span>
                                {elseif $edit=='pin_with_confirmation' or  $edit=='password_with_confirmation'}
                                    <span id="not_match_invalid_msg" class="hide">{t}Values don't match{/t}</span>
                                    <span id="{$field.id}_cancel_confirm_button" class="hide"><span class="link"
                                                                                                    onclick="cancel_confirm_field('{$field.id}')">({t}start again{/t}
                                            )</span> </span>
                                    <input id="{$field.id}" type="password" class="input_field hide"
                                           value="{$field.value}" has_been_valid="0"/>
                                    <input id="{$field.id}_confirm" placeholder="{t}Retype new password{/t}"
                                           type="password" confirm_field="{$field.id}" class="confirm_input_field hide"
                                           value="{$field.value}"/>
                                    <i id="{$field.id}_confirm_button" class="fa fa-repeat  save {$edit} hide"
                                       onclick="confirm_field('{$field.id}')"></i>
                                    <i id="{$field.id}_save_button" class="fa fa-cloud  save {$edit} hide"
                                       onclick="save_field('{$state._object->get_object_name()}','{$state.key}','{$field.id}')"></i>
                                    <span id="{$field.id}_msg" class="msg"></span>
                                {elseif $edit=='option' }
                                    <input id="{$field.id}" class="hide" value="{$field.value}" has_been_valid="0"/>
                                    <i id="{$field.id}_save_button" class="fa fa-cloud  save {$edit} option_multiple_choices hide"
                                       xstyle="margin-left:5px"
                                       onclick="save_field('{$state._object->get_object_name()}','{$state.key}','{$field.id}')"></i>
                                    <span id="{$field.id}_msg" class="msg"></span>
                                {if isset($field.allow_other) and  $field.allow_other}
                                    <i id="{$field.id}_add_other_option" class="fa fa-plus fw button hide"
                                       onClick="show_add_other_option('{$field.id}')"
                                       style="cursor:pointer;float:left;margin-right:5px;padding-top:8px"></i>
                                {/if}
                                    <div id="{$field.id}_options" class="dropcontainer option_multiple_choices hide"
                                         style="width:310px;xmargin-left:20px">
                                        <ul id="{$field.id}_options_ul">
                                            {foreach from=$field.options item=option key=value}
                                                <li class="{if $value==$field.value}selected{/if}"
                                                    onclick="select_option(this,'{$field.id}','{$value}' )">{$option}<i
                                                            class="fa fa-circle fw padding_left_5 current_mark {if $value==$field.value}current{/if}"></i>
                                                </li>
                                            {/foreach}
                                        </ul>
                                    </div>
                                {elseif $edit=='option_multiple_choices' }
                                    <input id="{$field.id}" type="hidden" value="{$field.value}" has_been_valid="0"/>
                                    <i id="{$field.id}_save_button" class="fa fa-cloud save {$edit} hide"
                                       onclick="save_field('{$state._object->get_object_name()}','{$state.key}','{$field.id}')"></i>
                                    <span id="{$field.id}_msg" class="msg"></span>
                                    <div id="{$field.id}_options" class="dropcontainer option_multiple_choices hide">
                                        <ul>
                                            {foreach from=$field.options item=option key=value}
                                                <li id="{$field.id}_option_{$value}" label="{$option.label}"
                                                    value="{$value}" is_selected="{$option.selected}"
                                                    onclick="select_option_multiple_choices('{$field.id}','{$value}','{$option.label}' )">
                                                    <i class="fa fa-fw checkbox {if $option.selected}fa-check-square-o{else}fa-square-o{/if}"></i> {$option.label}
                                                    <i class="fa fa-circle fw current_mark {if $option.selected}current{/if}"></i>
                                                </li>
                                            {/foreach}
                                        </ul>
                                    </div>
                                {elseif $edit=='date' }
                                    <input id="{$field.id}" type="hidden" value="{$field.value}" has_been_valid="0"/>
                                    <input id="{$field.id}_time" type="hidden" value="{$field.time}"/>
                                    <input id="{$field.id}_formatted" class="option_input_field hide" value="{$field.formatted_value}"/>
                                    <i id="{$field.id}_eraser" onClick="erase_date_field('{$field.id}')"  display="{if isset($field.display_eraser)}yes{/if}"   class="fa fa-eraser padding_right_10 button  hide" aria-hidden="true"></i>

                                    <i id="{$field.id}_save_button" class="fa fa-cloud save {$edit} hide" onclick="save_field('{$state._object->get_object_name()}','{$state.key}','{$field.id}')"></i>
                                    <span id="{$field.id}_msg" class="msg"></span>

                                    <div id="{$field.id}_datepicker" class="hide datepicker"></div>
                                    <script>
                                        $(function () {
                                            $("#{$field.id}_datepicker").datepicker({
                                                showOtherMonths: true,
                                                selectOtherMonths: true,
                                                defaultDate: new Date('{$field.value}'),
                                                altField: "#{$field.id}",
                                                altFormat: "yy-mm-dd",

                                                {if isset($field.min_date)}   minDate: {$field.min_date},{/if}
                                                {if isset($field.max_date)}   maxDate: {$field.max_date},{/if}

                                                onSelect: function () {
                                                    $('#{$field.id}').change();
                                                    $('#{$field.id}_formatted').val($.datepicker.formatDate("dd/mm/yy", $(this).datepicker("getDate")))
                                                }
                                            });
                                        });
                                        $('#{$field.id}_formatted').on('input', function () {

                                            var _moment = moment($('#{$field.id}_formatted').val(), ["DD-MM-YYYY", "MM-DD-YYYY"], 'en');


                                            if (_moment.isValid()) {
                                                var date = new Date(_moment)
                                            } else {
                                                var date = chrono.parseDate($('#{$field.id}_formatted').val())
                                            }

                                            if (date == null) {
                                                var value = '';
                                            } else {
                                                var value = date.toISOString().slice(0, 10)
                                                $("#{$field.id}_datepicker").datepicker("setDate", date);
                                            }
                                            $('#{$field.id}').val(value)
                                            $('#{$field.id}').change();

                                        });
                                        $('#{$field.id}').on('change', function () {
                                            on_changed_value('{$field.id}', $('#{$field.id}').val())
                                        });

                                    </script>

                                {elseif $edit=='' }
                                {if $class=='new'}
                                    <span id="{$field.id}_msg" class="msg"></span>
                                {/if}
                                {/if}




                                {if isset($field.invalid_msg)}
                                    {foreach from=$field.invalid_msg item=msg key=msg_key }
                                        <span id="{$field.id}_{$msg_key}_invalid_msg" class="hide">{$msg}</span>
                                    {/foreach}
                                {/if}
                            </td>
                        </tr>
                    {/if}
                {/foreach}
            {/if}

        {/foreach}

    </table>
</div>
<script>
    $(document).on('input propertychange', '.input_field', function (evt) {

        if ($('#' + $(this).attr('id') + '_container').attr('server_validation')) {
            var delay = 200;
        } else {
            var delay = 10;
        }
        if (window.event && event.type == "propertychange" && event.propertyName != "value") return;
        delayed_on_change_field($(this), delay)
    });


    $(document).on('input propertychange', '.address_input_field', function (evt) {
        if ($('#' + $(this).attr('id') + '_container').attr('server_validation')) {
            var delay = 200;
        } else {
            var delay = 10;
        }
        if (window.event && event.type == "propertychange" && event.propertyName != "value") return;
        delayed_on_change_address_field($(this), delay)
    });

    $(".confirm_input_field").on("input propertychange", function (evt) {
        if (window.event && event.type == "propertychange" && event.propertyName != "value") return;
        on_changed_confirm_value($(this).attr('confirm_field'), $(this).val())
    });

    $("#fields").on("click", "#show_new_email_field", function () {

        $('#new_email_field').removeClass('hide')
        open_edit_field('{$state._object->get_object_name()}', '{$state.key}', 'new_email')
        $('#show_new_email_field').addClass('hide')
    });

    $("#fields").on("click", "#show_new_telephone_field", function () {

        $('#new_telephone_field').removeClass('hide')
        open_edit_field('{$state._object->get_object_name()}', '{$state.key}', 'new_telephone')
        $('#show_new_telephone_field').addClass('hide')
    });

    $("#fields").on("click", "#show_new_delivery_address_field", function () {

        $('#new_delivery_address_field').removeClass('hide')
        open_edit_field('{$state._object->get_object_name()}', '{$state.key}', 'new_delivery_address')
        $('#show_new_delivery_address_field').addClass('hide')
    });



    {if isset($js_code) }{fetch file="$js_code"}{/if}


</script> 
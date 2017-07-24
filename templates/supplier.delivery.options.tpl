{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 23 July 2017 at 18:07:29 CEST, Trnava, Slovakia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}

<span id="dn_data" class="hide"  dn_key="{$dn->id}"   picker_key="{$dn->get('Delivery Note Assigned Picker Key')}"  packer_key="{$dn->get('Delivery Note Assigned Packer Key')}"
      no_picker_msg="{t}Please assign picker{/t}"
      no_packer_msg="{t}Please assign packer{/t}"

></span>
<div class="table_new_fields" style="border-bottom:1px solid #ccc;">


    <div id="picking_options" class="picking_options" style="align-items: stretch;flex: 1;border-left:1px solid #ccc">


        <table style="width:50%;float:right;width:100%;min-height: 100px;" border="0">
            <tbody class="{if $dn->get('State Index')>=50}hide{/if}">
            <tr>
                <td class="invisible" style="width: 30%;padding:10px;border-right:1px solid whitesmoke" >

                    <label>{t}Checked/placed by{/t}</label>

                    <input id="set_picker" type="hidden" class=" input_field" value="" has_been_valid="0"/>

                    <input id="set_picker_dropdown_select_label" field="set_picker" style="width:170px"
                           scope="employee" parent="account"
                           parent_key="1" class="dropdown_select"
                           data-metadata='{ "option":"only_working"}'
                           value="{$dn->get('Delivery Note Assigned Picker Alias')}" has_been_valid="0"
                           placeholder="{t}Name{/t}"/>
                    <span id="set_picker_msg" class="msg"></span>
                    <i id="set_picker_save_button" class="fa fa-cloud save dropdown_select hide"
                       onclick="save_this_field(this)"></i>
                    <div id="set_picker_results_container" class="search_results_container hide">

                        <table id="set_picker_results" border="0"  >

                            <tr class="hide" id="set_picker_search_result_template" field="" value=""
                                formatted_value="" onClick="select_dropdown_handler('picker',this)">
                                <td class="code"></td>
                                <td style="width:85%" class="label"></td>

                            </tr>
                        </table>

                    </div>
                    <script>
                        $("#set_picker_dropdown_select_label").on("input propertychange", function (evt) {

                            var delay = 100;
                            if (window.event && event.type == "propertychange" && event.propertyName != "value") return;
                            delayed_on_change_dropdown_select_field($(this), delay)
                        });
                    </script>

                    <div style="display:flex;margin-top:10px">
                        <div  class=" very_discreet Delivery_Note_Start_Picking_Datetime" style="align-items: stretch;flex: 1;border-left:1px solid #eee;text-align:center">
                            {if $dn->get('State Index')==10}<span id="start_picking" class="button"><i class="fa fa-clock-o" title="{t}Start picking{/t}" aria-hidden="true"></i> {t}Start picking{/t}</span>
                            {else}
                                {$dn->get('Start Picking Datetime')}

                            {/if}


                        </div>

                        <div class=" very_discreet hide" style="align-items: stretch;flex: 1;border-left:1px solid #eee;text-align:center"><i class="fa fa-barcode" title="{t}Scan mode{/t}" aria-hidden="true"></i> {t}Scan mode{/t}</div>
                        <div class=" very_discreet hide" style="align-items: stretch;flex: 1;border-left:1px solid #eeee;text-align:center"> <i class="fa fa-square-o"  title="{t}Set all as picked{/t}" aria-hidden="true"></i>  {t}mark all as picked{/t}</div>

                        <div class="hide  " style="align-items: stretch;flex: 1;border-left:1px solid #eeee;text-align:center">

                            <a class="pdf_link" target='_blank' href="/pdf/order_pick_aid.pdf.php?id={$delivery_note->id}"> <img src="/art/pdf.gif"></a>

                        </div>

                    </div>


                </td>

                <td id="booking_in_barcode_feedback" style="position:relative;padding:0px;padding-right:50px">
                    <i  class="fa fa-barcode button" aria-hidden="true"   onclick="$(this).css({ opacity:1})" style="opacity:.5;position:absolute;top:10px;right:10px"></i>


                    <div class="barcode_found  hide">

                        <div style="display:flex;"  >
                            <div style="align-items: stretch;flex: 0">


                                <img id="booking_in_barcode_part_image_src" src="/art/nopic.png" style="max-height: 70px;max-width: 100px">
                            </div>
                            <div style="align-items: stretch;flex: 1">
                                <span id="booking_in_barcode_part_reference"></span>
                                <p style="padding:0px;margin: 0px;margin-bottom:4px;font-size:90%" id="booking_in_barcode_part_description"></p>

                                <span id="copy_qty_from_barcode_feedback" onclick="copy_qty_from_barcode_feedback(this)"  qty=""  class="button" >   <span>
                            </div>
                            <div style="align-items: stretch;flex: 1">

                                 <input id="booking_in_barcode_qty_input" val="" style="width: 50px"> <i class="fa fa-plus" aria-hidden="true"></i>

                            </div>

                            <div style="align-items: stretch;flex: 1">

                                <i id="reading_location_barcode" class="fa fa-barcode invisible"  aria-hidden="true"></i> <i class="fa fa-map-marker" aria-hidden="true"></i>
                                <input id="booking_in_barcode_location_input" val="" style="width: 150px"> <i class="fa fa-cloud" aria-hidden="true"></i>

                            </div>

                        </div>

                    </div>

                    <div class="barcode_not_found  hide">





                        <i class="fa error fa-exclamation-circle" aria-hidden="true"></i> <span class="small">{t}SKO with this barcode not found in delivery{/t}</span><br>
                                <span class="strong not_found_barcode_number"></span>



                        <div class="find_outside_order hide"></div>
                        <div class="assign_barcode hide" style="margin-top:10px">

                           {t}Assign to part in delivery{/t}  <input id="assign_barcode_to_item" placeholder="{t}Part reference{/t}" >

                        </div>



                    </div>
                </td>


            </tr>

            </tbody>

        </table>
    </div>





    
     </div>





<script>




    function delayed_on_assign_barcode_to_item(object, timeout) {

        window.clearTimeout(object.data("timeout"));

        object.data("timeout", setTimeout(function () {

            get_assign_barcode_to_item_select(object)
        }, timeout));
    }

    function get_assign_barcode_to_item_select(object) {

        object.removeClass('invalid')

        var metadata={
            'supplier_delivery_key':{$dn->id}
        }

        var request = '/ar_find.php?tipo=find_objects&query=' + fixedEncodeURIComponent(object.val()) + '&scope=part_in_supplier_delivery&state=' + JSON.stringify(state)+'&metadata=' + JSON.stringify(metadata)
        console.log(request)
        $.getJSON(request, function (data) {


            var offset = object.offset();


            if (data.number_results > 0) {
                $('#location_results_container').removeClass('hide').addClass('show')
                $('#location_results_container').offset({
                    top: (offset.top + object.outerHeight() - 1),
                    left: offset.left
                })

            } else {


                $('#location_results_container').addClass('hide').removeClass('show')
                if (object.val() != '') {
                    object.addClass('invalid')
                }


            }


            $("#location_results .result").remove();

            var first = true;

            for (var result_key in data.results) {

                //console.log(result_key)
                var clone = $("#location_search_result_template").clone()
                clone.prop('id', 'location_result_' + result_key);
                clone.addClass('result').removeClass('hide')
                clone.attr('value', data.results[result_key].value)
                clone.attr('transaction_key', object.closest('.place_item').attr('transaction_key'))
                clone.attr('formatted_value', data.results[result_key].formatted_value)
                // clone.attr('field', field)
                if (first) {
                    clone.addClass('selected')
                    first = false
                }

                // clone.children(".code").html(data.results[result_key].code)
                clone.children(".label").html(data.results[result_key].description)

                $("#location_results").append(clone)


            }

        })


    }

    function select_location_option(element) {
        var container = $('#place_item_' + $(element).attr('transaction_key'))
        container.find('.location_code').val($(element).attr('formatted_value'))
        container.find('i.save').attr('location_key', $(element).attr('value'))
        $('#location_results_container').addClass('hide').removeClass('show')
        validate_place_item(container)
    }



    var out_of_stock_dialog_open=false;


    $('#table').on('click', 'span.item_quantity', function() {
        if(out_of_stock_dialog_open){
        }else{
            $(this).closest('tr').find('.picking').val($(this).attr('qty')).trigger('propertychange')
        }

    });

    $('#table').on('click', 'i.no_stock_location', function() {

        if($('#set_out_of_stock_items_dialog').hasClass('hide')) {

            var settings = $(this).closest('tr').find('.picking').parent().data('settings')
            var offset = $(this).offset()
            $('#set_out_of_stock_items_dialog').removeClass('hide').offset({
                top: offset.top - 15,
                left: offset.left - $('#set_out_of_stock_items_dialog').width() - 50.0
            }).attr('transaction_key', settings.transaction_key).attr('item_key', settings.item_key)
            out_of_stock_dialog_open = true;
        }else{
            $('#set_out_of_stock_items_dialog').addClass('hide')
            out_of_stock_dialog_open = false;
        }

    });



    $(document).on('input propertychange', '#assign_barcode_to_item', function (evt) {
        var delay = 100;
        if (window.event && event.type == "propertychange" && event.propertyName != "value") return;

        delayed_on_assign_barcode_to_item($(this), delay)
    });


    $('#table').on('input propertychange', '.picking', function() {
       if($(this).val()!=$(this).attr('ovalue')){
            $(this).next('i').removeClass('fa-plus').addClass('fa-cloud')
       }

    });


    function select_dropdown_handler(type,element) {


        field = $(element).attr('field')
        value = $(element).attr('value')

        if(value==0){
            return;
        }



        formatted_value = $(element).attr('formatted_value')
        //metadata = $(element).data('metadata')


        $('#' + field + '_dropdown_select_label').val(formatted_value)


        $('#' + field).val(value)

        $('#' + field + '_results_container').addClass('hide').removeClass('show')





        var request = '/ar_edit_orders.php?tipo=set_'+type+'&delivery_note_key='+$('#dn_data').attr('dn_key')+'&staff_key='+value
        console.log(request)




        $.getJSON(request, function (data) {

            if(data.state==200){

                $('#dn_data').attr(type+'_key',data.staff_key)






            }

        })



    }

    $( "#start_picking" ).click(function() {

        var request = '/ar_edit_orders.php?tipo=set_state&object=delivery_note&key='+$('#dn_data').attr('dn_key')+'&value=Picking'
        $.getJSON(request, function (data) {
            if(data.state==200){


                for (var key in data.metadata.class_html) {
                    $('.' + key).html(data.metadata.class_html[key])
                }


                for (var key in data.metadata.hide) {
                    $('#' + data.metadata.hide[key]).addClass('hide')
                }
                for (var key in data.metadata.show) {
                    $('#' + data.metadata.show[key]).removeClass('hide')
                }
            }
        })
    })

    $( "#start_packing" ).click(function() {

        var request = '/ar_edit_orders.php?tipo=set_state&object=delivery_note&key='+$('#dn_data').attr('dn_key')+'&value=Packing'
        $.getJSON(request, function (data) {
            if(data.state==200){


                for (var key in data.metadata.class_html) {
                    $('.' + key).html(data.metadata.class_html[key])
                }


                for (var key in data.metadata.hide) {
                    $('#' + data.metadata.hide[key]).addClass('hide')
                }
                for (var key in data.metadata.show) {
                    $('#' + data.metadata.show[key]).removeClass('hide')
                }
            }
        })
    })

</script>
{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 23 July 2017 at 18:07:29 CEST, Trnava, Slovakia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}

<style>
    .booking_in_barcode_feedback_block{
        margin-left:70px
    }
</style>

<div id="booking_in_barcode_feedback"  class="hide" style="border-bottom:1px solid #ccc;padding:20px;position: relative;min-height: 60px">



                    <span style="position:absolute;top:10px;left:10px">
                    <i id="close_booking_in_barcode_feedback" class="fa fa-window-close button" aria-hidden="true"   ></i>
                        <i class="fa fa-barcode" style="margin-left:10px" aria-hidden="true"   ></i> <span style="margin-left:20px" id="ready_for_scan_label">{t}Ready for scan{/t}</span>

                    </span>


                    <div class="placement_success  hide  booking_in_barcode_feedback_block">

                        <i class="fa fa-check success" aria-hidden="true"></i> {t}Item placed{/t}
                        <div class="placement" style="width:300px"></div>

                    </div>


                    <div class="barcode_found  hide booking_in_barcode_feedback_block">

                        <div style="display:flex;">
                            <div   style="align-items: stretch;flex: 0">


                                <img id="booking_in_barcode_part_image_src" src="/art/nopic.png" style="max-height: 70px;max-width: 100px">
                            </div>
                            <div style="align-items: stretch;flex: 1">
                                <span id="booking_in_barcode_part_reference"></span>
                                <p style="padding:0px;margin: 0px;margin-bottom:4px;font-size:90%" id="booking_in_barcode_part_description"></p>

                                <span id="copy_qty_from_barcode_feedback" onclick="copy_qty_from_barcode_feedback(this)" qty="" class="button fast_track">   <span>
                            </div>

                            <div class="fast_track" style="align-items: stretch;flex: 1">

                                <input id="booking_in_barcode_qty_input" val="" style="width: 50px"> <i class="fa fa-plus" aria-hidden="true"></i>

                            </div>

                            <div  class="fast_track" style="align-items: stretch;flex: 1">

                                <i id="reading_location_barcode" class="fa fa-barcode invisible" aria-hidden="true"></i> <i class="fa fa-inventory" aria-hidden="true"></i>
                                <input id="booking_in_barcode_location_input" val="" style="width: 150px"> <i class="fa fa-cloud" aria-hidden="true"></i>

                            </div>

                        </div>

                    </div>

                    <div class="barcode_not_found  hide booking_in_barcode_feedback_block">


                        <i class="fa error fa-exclamation-circle" aria-hidden="true"></i> <span class="small">{t}SKO with this barcode not found in delivery{/t}</span><br>
                        <span id="not_found_barcode_number" class="strong not_found_barcode_number"></span>


                        <div class="find_outside_order hide"></div>
                        <div class="assign_barcode hide" style="margin-top:10px">

                            {t}Assign to part{/t} <input id="assign_barcode_to_item" placeholder="{t}Part reference{/t}">

                        </div>


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

        var metadata = {
            'supplier_delivery_key':{$dn->id},
            'with_no_sko_barcodes':true
        }

        var request = '/ar_find.php?tipo=find_objects&query=' + fixedEncodeURIComponent(object.val()) + '&scope=part_in_supplier_delivery&state=' + JSON.stringify(state) + '&metadata=' + JSON.stringify(metadata)
        console.log(request)
        $.getJSON(request, function (data) {


            var offset = object.offset();


            if (data.number_results > 0) {
                $('#assign_barcode_to_part_results_container').removeClass('hide').addClass('show')
                $('#assign_barcode_to_part_results_container').offset({
                    top: (offset.top + object.outerHeight() - 1), left: offset.left
                })

            } else {


                $('#assign_barcode_to_part_results_container').addClass('hide').removeClass('show')
                if (object.val() != '') {
                    object.addClass('invalid')
                }


            }


            $("#assign_barcode_to_part_results .result").remove();

            var first = true;

            for (var result_key in data.results) {

                //console.log(result_key)
                var clone = $("#assign_barcode_to_part_search_result_template").clone()
                clone.prop('id', 'location_result_' + result_key);
                clone.addClass('result').removeClass('hide')
                clone.attr('value', data.results[result_key].value)

                clone.attr('formatted_value', data.results[result_key].formatted_value)
                // clone.attr('field', field)
                if (first) {
                    clone.addClass('selected')
                    first = false
                }

                clone.children(".code").attr('barcode',data.results[result_key].barcode)
                 clone.children(".code").html(data.results[result_key].code)
                clone.children(".label").html(data.results[result_key].description)

                $("#assign_barcode_to_part_results").append(clone)


            }

        })


    }



    var out_of_stock_dialog_open = false;


    $('#table').on('click', 'span.item_quantity', function () {
        if (out_of_stock_dialog_open) {
        } else {
            $(this).closest('tr').find('.picking').val($(this).attr('qty')).trigger('propertychange')
        }

    });

    $('#table').on('click', 'i.no_stock_location', function () {

        if ($('#set_out_of_stock_items_dialog').hasClass('hide')) {

            var settings = $(this).closest('tr').find('.picking').parent().data('settings')
            var offset = $(this).offset()
            $('#set_out_of_stock_items_dialog').removeClass('hide').offset({
                top: offset.top - 15, left: offset.left - $('#set_out_of_stock_items_dialog').width() - 50.0
            }).attr('transaction_key', settings.transaction_key).attr('item_key', settings.item_key)
            out_of_stock_dialog_open = true;
        } else {
            $('#set_out_of_stock_items_dialog').addClass('hide')
            out_of_stock_dialog_open = false;
        }

    });


    $(document).on('input propertychange', '#assign_barcode_to_item', function (evt) {
        var delay = 100;
        if (window.event && event.type == "propertychange" && event.propertyName != "value") return;

        delayed_on_assign_barcode_to_item($(this), delay)
    });


    $('#table').on('input propertychange', '.picking', function () {
        if ($(this).val() != $(this).attr('ovalue')) {
            $(this).next('i').removeClass('fa-plus').addClass('fa-cloud')
        }

    });



    $("#start_picking").on( 'click',function () {

        var request = '/ar_edit_orders.php?tipo=set_state&object=delivery_note&key=' + $('#dn_data').attr('dn_key') + '&value=Picking'
        $.getJSON(request, function (data) {
            if (data.state == 200) {


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

    $("#start_packing").on( 'click',function () {

        var request = '/ar_edit_orders.php?tipo=set_state&object=delivery_note&key=' + $('#dn_data').attr('dn_key') + '&value=Packing'
        $.getJSON(request, function (data) {
            if (data.state == 200) {


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
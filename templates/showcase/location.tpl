<div class="sticky_notes" style="border-top: 1px solid #ccc">
    {include file="sticky_note.tpl" value=$location->get('Sticky Note') object="Location" key="{$location->id}" field="Location_Sticky_Note"  }
</div>
<div class="subject_profile">


    <ul class="tags Warehouse_Area {if !$location->get('Location Warehouse Area Key')}hide{/if}" style="float:right">

            <li><span class="button" onclick="change_view('warehouse/{$location->get('Warehouse Key')}/areas/{$location->get('Location Warehouse Area Key')}')" title="{$location->get('Warehouse Area Code')}">{$location->get('Warehouse Area Name')}</span></li>

    </ul>
    <div style="clear:both">
    </div>

    <div id="contact_data"></div>
    <div id="info">
        <div id="overviews">

            <table class="overview">

                <tr>
                    <td>{t}Stock value{/t}:</td>
                    <td class="aright"><span class="Stock_Value">{$location->get('Stock Value')}</span></td>
                </tr>


            </table>


            <table id="barcode_data" class="overview  ">
                <tr class="main">
                    <td class="label">
                        <i  class="fa fa-barcode"></i>
                    </td>
                    <td class="barcode_labels aleft ">

                        <a class="padding_left_10" title="{t}Location barcode{/t}" href="/location_label.php?object=location&key={$location->id}">{t}Barcode{/t}</a>
                    </td>

                </tr>


            </table>


        </div>
    </div>
    <div style="clear:both">
    </div>
</div>





<div id="set_location_part_stock_quantity_dialog" class="hide" style="position:absolute;border:1px solid #ccc;background-color: white;padding:10px 10px;z-index: 100">
    <table>
        <tr>
            <td><i style="position:relative;top:-7px;margin-right:10px" class="fa fa-window-close button" onClick="close_location_part_stock_quantity_dialog()" aria-hidden="true"></i></td>
            <td> {t}Stock{/t} </td>
            <td><input id="set_location_part_stock_quantity_value" class=" width_75" value="" ovalue="" part_sku="" location_key="" element=""/></td>

            <td><i id="set_location_part_stock_quantity_save" onClick="save_location_part_stock_quantity_price(this)" class="fa  fa-cloud fa-fw button  save    " aria-hidden="true"></i></td>

        </tr>
        <tr>
            <td></td>
            <td> {t}Note{/t} </td>
            <td><input id="set_location_part_stock_note_value" class=" width_200" value=""/></td>
            <td></td>

        </tr>
    </table>
</div>


<div style="padding:10px;border-bottom:1px solid #ccc;display:flex;justify-content: space-around;">
    <div style="text-align: left;">

    </div>

</div>


<script>


    function open_location_part_stock_quantity_dialog(element) {

        var element = $(element)
        var offset = element.offset()
        $('#set_location_part_stock_quantity_value').val(element.attr('qty')).attr('part_sku', element.attr('part_sku')).attr('ovalue', element.attr('qty')).attr('location_key', element.attr('location_key')).data('element', element).focus()
        $('#set_location_part_stock_quantity_dialog').removeClass('hide').offset({
            top: offset.top - 7.5, left: offset.left + element.width() - $('#set_location_part_stock_quantity_dialog').width() - 20
        })
    }


    var location_part_stock_quantity_timeout = false;

    $("#set_location_part_stock_quantity_value").on("input propertychange", function (evt) {
        window.clearTimeout(location_part_stock_quantity_timeout);

        var element = this
        location_part_stock_quantity_timeout = setTimeout(function () {
            location_part_stock_quantity_changed(element)
        }, 400);
    })

    function location_part_stock_quantity_changed(e) {


        new_value = $(e).val();

        if ((new_value - $(e).attr('ovalue')) == 0) {
            $('#set_location_part_stock_quantity_save').removeClass('changed invalid valid')
            $('#set_location_part_stock_quantity_value').removeClass('invalid')
        } else {
            $('#set_location_part_stock_quantity_save').addClass('changed')
            var validation = client_validation('numeric_unsigned', true, new_value, '')

            element = $(e).data('element');
            var tr = element.closest('tr')
            if (validation.class == 'invalid') {

                $('#set_location_part_stock_quantity_value').addClass('invalid')
                $('#set_location_part_stock_quantity_save').addClass('invalid')
            } else if (validation.class == 'valid') {
                $('#set_location_part_stock_quantity_save').removeClass('invalid').addClass('valid')
                $('#set_location_part_stock_quantity_value').removeClass('invalid')

            }
        }


    }

    function save_location_part_stock_quantity_price() {

        if ($('#set_location_part_stock_quantity_save').hasClass('valid')) {
            $('#set_location_part_stock_quantity_save').addClass('fa-spinner fa-spin').removeClass('valid changed')
            var request = '/ar_edit_stock.php?tipo=edit_part_location_stock&part_sku=' + $('#set_location_part_stock_quantity_value').attr('part_sku')
                +'&location_key=' + $('#set_location_part_stock_quantity_value').attr('location_key')
                +'&qty='  + $('#set_location_part_stock_quantity_value').val()
                +'&note='  + $('#set_location_part_stock_note_value').val()
            console.log(request)

            $.getJSON(request, function (r) {

                $('#set_location_part_stock_quantity_save').removeClass('fa-spinner fa-spin')

                console.log(r)
                element = $('#set_location_part_stock_quantity_value').data('element');
                var tr = element.closest('tr')
                tr.find('#quantity_'+r.part_sku).html(r.update_metadata.location_part_stock_cell)
                tr.find('#link_'+r.part_sku).html(r.update_metadata.location_part_link_cell)
                tr.find('#stock_value_'+r.part_sku).html(r.update_metadata.location_part_stock_value_cell)





                $('.table_edit_cell').awesomeCursor('pencil', {
                    color: 'rgba(0, 0, 0, 0.5)'
                })
                //  tr.find('.product_margin').parent().html(r.update_metadata.margin_cell)
                close_location_part_stock_quantity_dialog();

            });
        }
    }


    function close_location_part_stock_quantity_dialog() {
        $('#set_location_part_stock_quantity_dialog').addClass('hide')
    }


    function  location_part_disassociate_from_table(element) {


        var ajaxData = new FormData();

        ajaxData.append("tipo", 'disassociate_location_part')
        ajaxData.append("location_key", '{$location->id}')
        ajaxData.append("part_sku", $(element).attr('part_sku'))


        $.ajax({
            url: "/ar_edit_stock.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false, complete: function () {
            }, success: function (data) {

                if (data.state == '200') {


                    $(element).closest('tr').addClass('hide')
                    $('#rtext').html(data.rtext)

                    // $('#save_button', window.parent.document).removeClass('save').find('i').removeClass('fa-spinner fa-spin')

                } else if (data.state == '400') {
                    swal({
                        title: data.title, text: data.msg, confirmButtonText: "OK"
                    });
                }


            }, error: function () {

            }

        })

    }

</script>

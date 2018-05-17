{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 30 April 2018 at 15:17:42 BST, Sheffield, UK
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}

<style>
    .booking_in_barcode_feedback_block{
        margin-left:70px
    }
</style>

<div  style="border-bottom:1px solid #ccc;padding:20px;position: relative;min-height: 60px">

<div  style="float: left;width: 600px">
    <table>
        <tr>
            <td>
                {t}Exchange{/t} 1{$account->get('Currency Code')}=
            </td>
            <td style="padding-left: 10px">
                <input id="edit_exchange" value="{math equation="1/x" x=$delivery->get('Supplier Delivery Currency Exchange') format="%.5f"}">{$delivery->get('Supplier Delivery Currency Code')}
            </td>
        </tr>

    </table>

</div>

    <span id="save_button" class="save" style="float:right" onClick="save_costing()"><i class="fa fa-cloud  " aria-hidden="true"></i> {t}Save{/t}</span>




</div>


<script>


    $(document).on('input propertychange,change', '#edit_exchange', function (evt) {


        edit_exchange(this)


    });


    $(document).on('input propertychange,change', '.items_amount', function (evt) {

        if (!validate_number($(this).val(), 0)   ) {
            $(this).removeClass('error')


            var sku = $(this).data('sku')

            element = $('#total_paid_' + sku)


            var sko_in = parseFloat(element.data('sko_in'))

            var items_amount = $(this).val()
            var extra_amount = parseFloat(element.data('extra_amount'))
            var extra_amount_account_currency = parseFloat(element.data('extra_amount_account_currency'))
            var exchange = parseFloat(element.data('exchange'))


            var paid = (items_amount + extra_amount) / exchange + extra_amount_account_currency


            element.html(paid).formatCurrency({ symbol: '{$currency_symbol}'})


            if (sko_in != 0) {
                $('#sko_cost_' + sku).html(paid / sko_in).formatCurrency({ symbol: '{$currency_symbol}'})
                $('#sko_cost_' + sku).html($('#sko_cost_' + sku).html() + '/sko')
            } else {
                $('#sko_cost_' + sku).html('')

            }
        }else{
            $(this).addClass('error')
        }

        $('#save_button').addClass('save button changed valid')


    });



    $(document).on('input propertychange,change', '.extra_amount', function (evt) {

        if($(this).val()==''){
            value=0
        }else{
            value=$(this).val()
        }


        if (!validate_number(value, 0)   ) {
            $(this).removeClass('error')


            var sku = $(this).data('sku')

            element = $('#total_paid_' + sku)


            var sko_in = parseFloat(element.data('sko_in'))

            var items_amount=parseFloat(element.data('items_amount'))


            var extra_amount = value
            var extra_amount_account_currency = parseFloat(element.data('extra_amount_account_currency'))
            var exchange = parseFloat(element.data('exchange'))


            var paid = (items_amount + extra_amount) / exchange + extra_amount_account_currency


            element.html(paid).formatCurrency({ symbol: '{$currency_symbol}'})


            if (sko_in != 0) {
                $('#sko_cost_' + sku).html(paid / sko_in).formatCurrency({ symbol: '{$currency_symbol}'})
                $('#sko_cost_' + sku).html($('#sko_cost_' + sku).html() + '/sko')
            } else {
                $('#sko_cost_' + sku).html('')

            }
        }else{
            $(this).addClass('error')
        }

        $('#save_button').addClass('save button changed valid')


    });


    $(document).on('input propertychange,change', '.extra_amount_account_currency', function (evt) {

        if($(this).val()==''){
            value=0
        }else{
            value=$(this).val()
        }


        if (!validate_number(value, 0)   ) {
            $(this).removeClass('error')


            var sku = $(this).data('sku')

            element = $('#total_paid_' + sku)


            var sko_in = parseFloat(element.data('sko_in'))

            var items_amount=parseFloat(element.data('items_amount'))


            var extra_amount =parseFloat(element.data('extra_amount'))
            var extra_amount_account_currency=parseFloat(value)



            var exchange = parseFloat(element.data('exchange'))
            console.log(exchange)

            console.log( (items_amount + extra_amount) / exchange )

            var paid =( (items_amount + extra_amount) / exchange )+ extra_amount_account_currency


            element.html(paid).formatCurrency({ symbol: '{$currency_symbol}'})


            if (sko_in != 0) {
                $('#sko_cost_' + sku).html(paid / sko_in).formatCurrency({ symbol: '{$currency_symbol}'})
                $('#sko_cost_' + sku).html($('#sko_cost_' + sku).html() + '/sko')
            } else {
                $('#sko_cost_' + sku).html('')

            }
        }else{
            $(this).addClass('error')
        }

        $('#save_button').addClass('save button changed valid')


    });



    function edit_exchange(input){

        if (!validate_number($(input).val(), 0)  &&  parseFloat($(input).val())!=0  ) {
            $(input).removeClass('error')
            var value = parseFloat($(input).val())




            $('.total_paid_amount').each(function(i, obj) {




                var sko_in=parseFloat($(obj).data('sko_in'))

                var items_amount=parseFloat($(obj).data('items_amount'))
                var extra_amount=parseFloat($(obj).data('extra_amount'))
                var extra_amount_account_currency=parseFloat($(obj).data('extra_amount_account_currency'))


                var paid=(items_amount+extra_amount)/value+extra_amount_account_currency

                $(obj).data('exchange',value)
                
                $(obj).html(paid).formatCurrency({ symbol:'{$currency_symbol}'})


                if(sko_in!=0){
                    $('#sko_cost_'+$(obj).data('sku')).html(paid/sko_in).formatCurrency({ symbol:'{$currency_symbol}'})
                    $('#sko_cost_'+$(obj).data('sku')).html( $('#sko_cost_'+$(obj).data('sku')).html()+'/sko')
                }else{
                    $('#sko_cost_'+$(obj).data('sku')).html('')

                }




            });

            $('#save_button').addClass('valid changed')


        } else {


            $(input).addClass('error')
        }










    }


    function save_costing(){


        if(!$('#save_button').hasClass('valid')){
            return;
        }


        $('#save_button').removeClass('valid').find('i').addClass('fa-spin fa-spinner')


var items_data={

}
        $('.total_paid_amount').each(function (i, obj) {

            var sku=$(obj).data('sku')
            items_data[sku]=[
                $('#items_amount_'+sku).val(),
                $('#extra_amount_'+sku).val(),
                $('#extra_amount_account_currency_'+sku).val(),
            ]



        })

        var ajaxData = new FormData();

        ajaxData.append("tipo", 'set_delivery_costing')
        ajaxData.append("key", {$delivery->id})
        ajaxData.append("items_data", JSON.stringify(items_data))
        ajaxData.append("exchange", $('#edit_exchange').val())



        console.log(items_data)

        $.ajax({
            url: "/ar_edit_stock.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false, complete: function () {
            }, success: function (data) {

                console.log(data)

                if (data.state == '200') {

                    change_view(state.request,{ 'reload_showcase': 1})

                    //$('#save_button').removeClass('save').find('i').removeClass('fa-spinner fa-spin')
                } else if (data.state == '400') {
                    swal(data.msg);
                }


            }, error: function () {

            }
        });


    }


</script>
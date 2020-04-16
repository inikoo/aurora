{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 11 March 2017 at 10:59:28 GMT+8, Sanur, Bali, Indonesia
 Copyright (c) 2016, Inikoo

 Version 3
-->
*}



<div id="set_cost_dialog" class="hide" style="position:absolute;border:1px solid #ccc;background-color: white;padding:10px 10px;z-index: 100">
    <i style="position:relative;top:-7px;margin-right:10px" class="fa fa-window-close button" onClick="close_product_cost_dialog()" ></i>
    {t}cost{/t} (<span id="set_cost_currency"></span>)
    <input id="set_cost_value" class=" width_205" value="" old_margin="" ovalue="" exchange="" cost="" product_id="" />
    <i id="set_cost_save" onClick="save_part_cost(this)" class="fa fa-cloud fa-fw button save"></i>
</div>





<script>

    function open_edit_cost(element){

        var element=$(element)
        var offset = element.offset()
        $('#set_cost_currency').html(element.attr('currency'))
        $('#set_cost_value').val(element.attr('cost')).attr('product_id',element.attr('pid')).attr('ovalue',element.attr('cost')).attr('old_margin',element.attr('old_margin')).attr('exchange',element.attr('exchange')).attr('cost',element.attr('cost')).data('element',element).focus()
        $('#set_cost_dialog').removeClass('hide').offset({
            top: offset.top -7.5,
            left: offset.left +element.width()- $('#set_cost_dialog').width()-20
        })
    }

    var cost_timeout=false;

    $("#set_cost_value").on("input propertychange", function (evt) {
        window.clearTimeout(cost_timeout);

        var element=this
        cost_timeout=setTimeout(function() {
            cost_changed(element)
        }, 400);
    })
    
    function cost_changed(e){


        new_cost=$(e).val();

        if( (new_cost-$(e).attr('ovalue'))==0){
            $('#set_cost_save').removeClass('changed invalid valid')
            $('#set_cost_value').removeClass('invalid')
        }else{
            $('#set_cost_save').addClass('changed')
            var validation= client_validation('amount', true, new_cost, '')

            element = $(e).data('element');
            var tr = element.closest('tr')
            if(validation.class=='invalid'){

                $('#set_cost_value').addClass('invalid')
                $('#set_cost_save').addClass('invalid')
            }
            else if(validation.class=='valid') {
                $('#set_cost_save').removeClass('invalid').addClass('valid')
                $('#set_cost_value').removeClass('invalid')
                element = $(e).data('element');
                cost = $(e).attr('cost');
                exchange = $(e).attr('exchange');
                new_margin = ((exchange * new_cost - cost) / (exchange * new_cost) * 100).toFixed(2) + '%'
                tr.find('.product_margin').html(new_margin)
            }
        }


    }
    
    function  save_part_cost(){

        if($('#set_cost_save').hasClass('valid')){
            $('#set_cost_save').addClass('fa-spinner fa-spin').removeClass('valid changed')
            var request='/ar_edit.php?tipo=edit_field&object=Supplier Part&key='+ $('#set_cost_value').attr('product_id')+'&field=Supplier_Part_Unit_Cost&value='+ $('#set_cost_value').val()
            console.log(request)
            $.getJSON(request, function (r) {

                $('#set_cost_save').removeClass('fa-spinner fa-spin')

                console.log(r)
                element = $('#set_cost_value').data('element');
                var tr = element.closest('tr')
                tr.find('.part_cost').parent().html(r.update_metadata.cost_cell)
                close_product_cost_dialog();
                console.log(r)
            });
        }
    }
    
    


    function close_product_cost_dialog(){
        element = $('#set_cost_value').data('element');
        var tr = element.closest('tr')
        tr.find('.product_margin').html( $('#set_cost_value').attr('old_margin'))
        $('#set_cost_dialog').addClass('hide')
    }



</script>
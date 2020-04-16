{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:27 February 2018 at 14:38:25 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3
-->
*}

<style>
    .supplier_parts_table{
        font-size: 90%;
    }

.supplier_parts_table td{
    padding:3px 15px


}
.supplier_parts_table tr{
  border-bottom:1px solid #ccc;
    height: auto;


}
</style>

<div id="set_cost_dialog" class="hide" style="position:absolute;border:1px solid #ccc;background-color: white;padding:10px 10px;z-index: 100">
    <i style="position:relative;top:-7px;margin-right:10px" class="fa fa-window-close button" onClick="close_cost_dialog()" aria-hidden="true"></i>
    {t}Total cost{/t} ({$account->get('Account Currency Code')})
    <input id="set_cost_value" class=" width_75" value="" old_cost_per_sko="" data-ovalue=""  data-cost="" data-itf_key="" /> <i id="set_cost_save" onClick="save_cost(this)" class="fa  fa-cloud fa-fw button  save    " aria-hidden="true"></i>
</div>



<script>


    function open_edit_cost(element){

        var element=$(element)
        var offset = element.offset()
        $('#set_cost_value').val(element.data('cost')).data('itf_key',element.data('itf_key')).data('ovalue',element.data('cost')).data('old_cost_per_sko',element.data('cost_per_sko')).data('currency_symbol',element.data('currency_symbol')).data('skos',element.data('skos')).data('element',element).focus()
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
                skos = $(e).data('skos');
                new_cost_per_sko =   (new_cost/skos).toFixed(2)
                tr.find('.part_cost_per_sko').addClass('italic discreet').html($(e).data('currency_symbol')+new_cost_per_sko)
            }
        }


    }
    
    function  save_cost(){

        if($('#set_cost_save').hasClass('valid')){
            $('#set_cost_save').addClass('fa-spinner fa-spin').removeClass('valid changed')
            var request='/ar_edit_stock.php?tipo=itf_cost&key='+ $('#set_cost_value').data('itf_key')+'&value='+ $('#set_cost_value').val()
            console.log(request)
            $.getJSON(request, function (r) {

                $('#set_cost_save').removeClass('fa-spinner fa-spin')

                console.log(r)
                element = $('#set_cost_value').data('element');
                var tr = element.closest('tr')
                tr.find('.part_cost').parent().html(r.part_cost_cell)
                tr.find('.part_cost_per_sko').parent().html(r.part_cost_per_sko_cell)

                for (var key in r.class_html) {
                    $('.' + key).html(r.class_html[key])
                }


                close_cost_dialog();
                console.log(r)
            });
        }
    }
    
    

    
    

    function close_cost_dialog(){
        element = $('#set_cost_value').data('element');
        var tr = element.closest('tr')
        tr.find('.part_cost_per_sko').html( $('#set_cost_value').data('old_cost_per_sko')).removeClass('italic discreet')
        $('#set_cost_dialog').addClass('hide')
    }



</script>
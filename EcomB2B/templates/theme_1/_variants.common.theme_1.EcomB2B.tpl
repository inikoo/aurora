{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: Thu 12 May 14:10 2022 Sheffield Uk
 Copyright (c) 2018, Inikoo

 Version 3
-->
*}

<style>
    .variant_chooser{
        border:1px solid #ccc;border-bottom: 1px solid #ccc;
    }

    .variant_chooser tr {
        cursor: pointer;
    }
    .variant_chooser td {
        border-bottom: 1px solid #eee;padding:4px 10px;
    }
    .variant_chooser tr:hover {
        color:#333;
    }

    .variant_chooser tr.current td {
        color:#000;
    }


    {assign current $variants[0]}

    .variant_chooser th {
        border-bottom: 1px solid #ccc;font-size: small;padding:4px 10px;
    }
</style>


<script>

    function open_variant_chooser(element,master_id){

        let dialog=$('#variant_chooser_dialog_'+master_id)

        let offset = $(element).offset();


        dialog.removeClass('hide').offset({
            top: (offset.top+ $(element).height())+7, left: offset.left-dialog.width()+$(element).width()+12
        })

    }


    $('.variant_chooser .variant_option').on('click',function() {

        let parent = $('.product.product_container')

        parent.find('.Product_Code').html($(this).data('code'))
        parent.find('.Product_Name').html($(this).data('name'))
        parent.find('.Package_Weight').html($(this).data('weight'))
        if($(this).data('weight')==''){
            parent.find('.Package_Weight_Container').addClass('hide')
        }else{
            parent.find('.Package_Weight_Container').removeClass('hide')

        }


        parent.find('.ordering_variant').addClass('hide')
        parent.find('#ordering_variant_'+$(this).data('id')).removeClass('hide')

        parent.find('.variant_chooser tr').removeClass('current')
        $(this).closest('tr').addClass('current');


        parent.find('.variant_chooser_dialog').addClass('hide')


        parent.data('product_id',$(this).data('id'))




    });
</script>
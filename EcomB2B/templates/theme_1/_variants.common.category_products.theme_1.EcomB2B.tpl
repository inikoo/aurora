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
        font-size: x-small;
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

    .variant_chooser th {
        border-bottom: 1px solid #ccc;font-size: xx-small;padding:4px 10px;
    }
    .variant_chooser_dialog{
        width:226px;position: absolute;z-index: 4000;background: #FFF;
    }

    .variant_chooser td.smaller_font {
        font-size: xx-small
    }

    {if $device=='mobile'}

    .variant_chooser_dialog {
        width: 100%;
    }

    .variant_chooser{
        font-size: medium;

    }
    .variant_chooser th {
        font-size: medium;
    }
    .variant_chooser td.smaller_font {
        font-size: medium;padding-top:20px;padding-bottom:20px;
    }



    {/if}
</style>


<script>

    function zxcvbzxcvb(fff, ggg) {
        console.log('fff', fff)
        console.log('ggg', ggg)
    }

    function open_variant_chooser(element,master_id){

        let icon=$(element).find('i');
        if(icon.hasClass('fa-angle-down')){


            $('#variant_chooser_dialog_'+master_id).addClass('hide')

            icon.addClass('fa-angle-up').removeClass('fa-angle-down')
            return;
        }


        let dialog=$('#variant_chooser_dialog_'+master_id)

        let offset = $(element).offset();


        icon.removeClass('fa-angle-up').addClass('fa-angle-down')

        console.log('dialog cat prod', dialog)

        dialog.removeClass('hide').offset({
            top: offset.top-$(dialog).height()+2,
            left: dialog.left
        })
    }


    $('.variant_chooser .variant_option').on('click',function() {
        console.log('clicked 1')
        variant_selected_in_family(this)
    });


    function variant_selected_in_family(element){

      console.log('xx var sel fam')

      let parent = $(element).closest('.product_block.product_container')


      console.log(parent)

      parent.find('.Product_Code').html($(element).data('code'))
      parent.find('.Mobil_Product_Price').html($(element).data('price'))




      parent.find('.Product_Name').html($(element).data('name'))
      parent.find('.Package_Weight').html($(element).data('weight'))
      if($(element).data('weight')==''){
        parent.find('.Package_Weight_Container').addClass('hide')
      }else{
        parent.find('.Package_Weight_Container').removeClass('hide')

      }


      parent.find('.ordering_variant').addClass('hide')
      parent.find('#ordering_variant_'+$(element).data('id')).removeClass('hide')

      parent.find('.variant_chooser tr').removeClass('current')

      $(element).closest('tr').addClass('current');


      parent.find('.variant_chooser_dialog').addClass('hide')

      console.log('-----')

      parent.data('product_id',$(element).data('id'))

    }



      $( document ).ready(function() {
      let elements=$('.variant_chooser  tr:nth-child(2)').each(function( index ) {
        variant_selected_in_family(this)

      });
    });
</script>

</script>

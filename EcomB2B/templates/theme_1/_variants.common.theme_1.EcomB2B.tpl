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
        border-bottom: 1px solid #eee;padding:4px 30px;
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

    {if $device=='mobile'}

    .variant_chooser_dialog {
        width: 100%
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

    function open_variant_chooser(element,master_id){


        let icon = $(element).find('i');
        if(icon.hasClass('fa-angle-up')){
            $('#variant_chooser_dialog_'+master_id).addClass('hide')

            icon.addClass('fa-angle-down').removeClass('fa-angle-up')
            return;
        }

        icon.removeClass('fa-angle-down').addClass('fa-angle-up')
        let dialog = $('#variant_chooser_dialog_'+master_id)
        
        console.log('dialog theme', dialog)
        let offset = $(element).offset();

        // console.log('offset left', offset.left)
        // console.log('dialog width', dialog.width())
        // console.log('element width', $(element).width())
        // console.log('zzzz', offset.left-dialog.width()+$(element).width()+12)


        dialog.removeClass('hide').offset({
            top: (offset.top+ $(element).height())+7,
            // left: offset.left-dialog.width()+$(element).width()+12
        })

        // console.log('dialog 1c', dialog)
    }


    $('.variant_chooser .variant_option').on('click',function() {
        console.log('clicked 2')
        variant_selected(this)
    });

    function variant_selected(element){
        console.log('var sel', element)
       console.log($(element).data('code'))
       console.log($(element).data('name'))
       console.log($(element).data('weight'))

      let parent = $('.product.product_container')

      parent.find('.Product_Code').html($(element).data('code'))
      parent.find('.Product_Name').html($(element).data('name'))
      parent.find('.Package_Weight').html($(element).data('weight'))
      if($(element).data('weight')==''){
        parent.find('.Package_Weight_Container').addClass('hide')
      }else{
        parent.find('.Package_Weight_Container').removeClass('hide')

      }

      parent.find('.ordering_variant').addClass('hide')
      
    //   console.log('elementttt', element)
    //   console.log('aaaaxzcxz', '#ordering_variant_'+$(element).data('id'))

      parent.find('#ordering_variant_'+$(element).data('id')).removeClass('hide')
      parent.find('.variant_chooser tr').removeClass('current')
      $(element).closest('tr').addClass('current');
      parent.find('.variant_chooser_dialog').addClass('hide')
      parent.data('product_id',$(element).data('id'))
    }

</script>
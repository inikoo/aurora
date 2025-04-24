{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 25 May 2017 at 19:41:41 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}

<div class="sticky_notes">
    {include file="sticky_note.tpl" _scope="order_sticky_note" value=$webpage->get('Sticky Note') object="Webpage" key="{$webpage->id}" field="Webpage_Sticky_Note"  }
</div>


<div style="padding:20px 20px 10px 20px;border-bottom:1px solid #ccc;" class="control_panel"  data-webpage_key="{$webpage->id}"  >


    <a id="link_to_live_webpage" target="_blank"  class="{if $webpage->get('Webpage State')=='Offline'}invisible{/if}"  href="{$webpage->get('URL')}" ><i class="fa fa-external-link" aria-hidden="true"  style="float:right;margin-left:20px;position:relative;top:2px"></i>   </a>


    {if !empty($offline)}
        <span id="save_button"  data-store_key="{$webpage->get('Store Key')}" class="unselectable" style="float:right;" onClick="$('#preview')[0].contentWindow.save()">{t}Save{/t} <i class="fa fa-cloud  " aria-hidden="true"></i></span>

    {else}
    <span id="save_button"  data-store_key="{$webpage->get('Store Key')}" class="unselectable" style="float:right;" onClick="$('#preview')[0].contentWindow.save()">{t}Publish{/t} <i class="fal fa-rocket  " aria-hidden="true"></i></span>
    {/if}
    {if isset($control_template)}
        {include file=$control_template content=$content}

    {/if}




    <div style="clear:both"></div>

</div>



<div id="images_layout_ideas" class="hide">
    <div style="text-align: right;margin-bottom: 10px;padding-right: 5px">
        <i class="fa fa-window-close button" onclick="$('#images_layout_ideas').addClass('hide')"></i>
    </div>

    <div class="options">
        <img class="button" template="1" src="/art/images_layout_1.png">
        <img class="button" template="2" src="/art/images_layout_2.png">
        <img class="button" template="3" src="/art/images_layout_3.png">
        <img class="button" template="4" src="/art/images_layout_4.png">
        <img class="button" template="12" src="/art/images_layout_12.png">
        <img class="button" template="21" src="/art/images_layout_21.png">
        <img class="button" template="13" src="/art/images_layout_13.png">
        <img class="button" template="31" src="/art/images_layout_31.png">
        <img class="button" template="211" src="/art/images_layout_211.png">


    </div>

</div>
<div id="text_layout_ideas" class="hide">
    <div style="text-align: right;margin-bottom: 10px;padding-right: 5px">
        <i class="fa fa-window-close button" onclick="$('#text_layout_ideas').addClass('hide')"></i>
    </div>

    <div class="options">
        <img class="button" template="1" src="/art/images_layout_1.png">
        <img class="button" template="2" src="/art/images_layout_2.png">
        <img class="button" template="3" src="/art/images_layout_3.png">
        <img class="button" template="4" src="/art/images_layout_4.png">
        <img class="button" template="12" src="/art/images_layout_12.png">
        <img class="button" template="21" src="/art/images_layout_21.png">
        <img class="button" template="13" src="/art/images_layout_13.png">
        <img class="button" template="31" src="/art/images_layout_31.png">
        <img class="button" template="211" src="/art/images_layout_211.png">


    </div>

</div>




<div id="see_also_add_item_dialog" class="hide" style="width:300px;position:absolute;border:1px solid #ccc;background-color:white;padding:20px;z-index: 1002">
    <div style="margin-bottom:5px">  <i  onClick="close_add_category_dialog()" style="position:relative;top:-5px" class="button fa fa-fw fa-window-close" aria-hidden="true"></i>  </div>
    <table class="edit_container" >
        <tr>
            <td>


                <input id="add_category" type="hidden" class=" input_field" value="" has_been_valid="0"/>
                <input id="add_category_dropdown_select_label_see_also" field="add_category" style="width:200px" scope="category_webpages" parent="store" data-metadata='{ "splinter":"see_also_item","parent_category_key":"{$webpage->get('Webpage Scope Key')}"}'

                       parent_key="{$website->get('Website Store Key')}" class=" dropdown_select" value="" has_been_valid="0" placeholder="{t}Family / category code{/t}" action="add_category_to_webpage"

                />
                <span id="add_category_msg" class="msg"></span>
                <div id="add_category_results_container" class="search_results_container hide" style="position: relative;left:-430px" >
                    **
                    <table id="add_category_results" >

                        <tr class="hide" id="add_category_search_result_template" field="" value="" formatted_value="" onClick="select_dropdown_add_item_to_see_also(this)">
                            <td class="code"></td>
                            <td style="width:85%" class="label"></td>

                        </tr>
                    </table>

                </div>
                <script>
                  console.log('hello')
                    $("#add_category_dropdown_select_label_see_also").on("input propertychange", function (evt) {
                        console.log('aaa')
                        var delay = 100;
                        if (window.event && event.type == "propertychange" && event.propertyName != "value") return;
                        delayed_on_change_dropdown_select_field($(this), delay)
                    });
                </script>


            </td>


        </tr>
    </table>


</div>


<iframe id="preview" style="width:100%;height: 2500px;" frameBorder="1" src="/webpage.php?webpage_key={$webpage->id}&theme={$theme}"></iframe>

<script>

    // common blk.items & blk.products

    function toggle_block_title(element){

        var block_key=$(element).closest('.edit_mode').attr('key')

        var icon=$(element).find('i')


        if(icon.hasClass('fa-toggle-on')){
            icon.removeClass('fa-toggle-on').addClass('fa-toggle-off')
            var value=false
        }else{
            icon.addClass('fa-toggle-on').removeClass('fa-toggle-off')
            var value=true

        }


        $('#preview')[0].contentWindow.toggle_block_title(block_key,value);

    }

    // blk.see_also


    function toggle_see_also_auto(element){

        var block_key=$(element).closest('.edit_mode').attr('key')

        var icon=$(element).find('i')


        if(icon.hasClass('fa-toggle-on')){
            icon.removeClass('fa-toggle-on').addClass('fa-toggle-off')
            $('.auto_controls').addClass('hide')
            $('.manual_controls').removeClass('hide')
            var value=false
        }else{
            icon.addClass('fa-toggle-on').removeClass('fa-toggle-off')
            var value=true
            $('.auto_controls').removeClass('hide')
            $('.manual_controls').addClass('hide')



            $(element).closest('.edit_mode').find('.edit_see_also_auto_number_items').val($('#preview').contents().find('#block_'+block_key+' .see_also').children().length)






        }
        $('#preview')[0].contentWindow.toggle_see_also_auto(block_key,value);

    }

    function apply_see_also_changes(element){
        $(element).closest('.auto_controls').find('.apply_auto_see_also_items').addClass('hide')
        $(element).closest('.auto_controls').find('.refresh_auto_see_also_items').removeClass('hide')

        refresh_see_also(element)
    }

    function refresh_see_also(element){

        $(element).closest('.auto_controls').find('.refresh_auto_see_also_items').addClass('fa-spin')

        $(element).closest('.auto_controls').find('.edit_see_also_auto_number_items').prop('readonly', true);


        var block_key=$(element).closest('.edit_mode').attr('key')

        var number_items=$(element).closest('.auto_controls').find('.edit_see_also_auto_number_items').val()

        $.getJSON( "/ar_website.php?tipo=see_also&webpage_key="+$('#blocks_showcase').attr('webpage_key')+'&number_items='+number_items, function( data ) {

            $('#preview')[0].contentWindow.refresh_see_also(block_key,data.html,number_items,data.update_date);

            $(element).closest('.auto_controls').find('.refresh_auto_see_also_items').removeClass('fa-spin')
            $(element).closest('.auto_controls').find('.edit_see_also_auto_number_items').prop('readonly', false);

        });

    }

    $(document).on('input propertychange', '.edit_see_also_auto_number_items', function (evt) {

        if (!validate_signed_integer($(this).val(), 50)    ) {
            $(this).removeClass('error')
            var value = $(this).val()

        } else {
            value = 0;

            $(this).addClass('error')
        }



        if(value){

            $(this).closest('.auto_controls').find('.refresh_auto_see_also_items').addClass('hide')
            $(this).closest('.auto_controls').find('.apply_auto_see_also_items').removeClass('hide')

        }





        $('#save_button').addClass('save button changed valid')




    });



    function open_add_category_dialog(element){

        $('#see_also_add_item_dialog').data('block_key',$(element).closest('.edit_mode').attr('key')).removeClass('hide').offset({
            left: $(element).offset().left,top: $(element).offset().top
        }).find('input').focus()




    }

    function close_add_category_dialog(){

        $('#see_also_add_item_dialog').addClass('hide')
        $('#add_category_dropdown_select_label').val('');


        $('#add_category_results .result').remove();

        $('#add_category_results_container').addClass('hide').removeClass('show')

    }

    function select_dropdown_add_item_to_see_also(element){

        block_key=$('#see_also_add_item_dialog').data('block_key')



        var data=JSON.parse($(element).data('metadata'))
        var value = $(element).attr('value')

        if (value == 0) {
            return
        }



        new_category=data.html;



        $('#preview')[0].contentWindow.add_item_to_see_also(block_key,new_category);
        close_add_category_dialog();
        $('#save_button').addClass('save button changed valid')


    }

    // blk.products




    function open_products_add_product_dialog(element){

        var block_key=$(element).closest('.edit_mode').attr('key')

        $('#products_add_product_dialog').removeClass('hide').offset({
            left: $(element).offset().left,top: $(element).offset().top
        }).find('input').focus()


        $('#products_add_product_dialog').data('block_key',block_key)

    }


    function close_products_add_product_dialog(){
        $('#products_add_product_dialog').addClass('hide')
        $('#add_product_dropdown_select_label').val('');


        $('#add_product_results .result').remove();

        $('#add_product_results_container').addClass('hide').removeClass('show')

    }

    function select_dropdown_add_product_to_products_webpage_block(element){


        block_key=$('#products_add_product_dialog').data('block_key')

        var data=JSON.parse($(element).data('metadata'))
        var value = $(element).attr('value')

        if (value == 0) {
            return
        }


        var new_product=$(
            '<div class="product_wrap wrap type_product" data-type="product"  data-sort_code="" data-sort_name="" >' +
            '<div class="product_block item"'+
            'data-product_id="'+data.product_id+'" '+
            'data-web_state="'+data.web_state+'" '+
            'data-price="'+data.price+'" '+
            'data-rrp="'+data.rrp+'" '+
            'data-code="'+data.code+'" '+
            'data-name="'+data.name+'" '+
            'data-link="'+data.link+'" '+
            'data-webpage_code="'+data.webpage_code+'" '+
            'data-webpage_key="'+data.webpage_key+'" '+
            'data-out_of_stock_class="'+data.out_of_stock_class+'" '+
            'data-out_of_stock_label=""   >'+
            '<div class="panel_txt_control hide" ><i onclick="close_product_header_text(this)" class="fa fa-window-close button" style="float: right;margin-top:6px" title="{t}Close text edit mode{/t}"></i></div>'+
            '<div class="product_header_text fr-view" ></div>'+
            '<div class="wrap_to_center product_image" >'+
            '<i class="fal fa-fw fa-external-link-square more_info" aria-hidden="true"></i>'+
            '<i onclick="remove_product_from_products(this)" style="top:50px;color:red" class="far fa-fw fa-trash-alt more_info " title="{t}Remove product{/t}" aria-hidden="true"></i>'+
            '<i class="far fa-fw  fa-heart favourite" aria-hidden="true"></i>'+
            '<img src="'+data.image_src+'" data-src="'+data.image_src+'"  data-image_website="" />'+
            '</div>'+
            '<div class="product_description"  ><span class="code">'+data.code+'</span><div class="name item_name">'+data.name+'</div> </div>'+
            '<div class="product_prices log_in " ><div class="product_price">{if empty($labels._product_price)}{t}Price{/t}{else}{$labels._product_price}{/if}: '+data.price+'</div>'+
            (data.rrp?'<div>{if empty($labels._product_rrp)}{t}RRP{/t}{else}{$labels._product_rrp}{/if}: '+data.rrp+'</div>':'')+
            '</div>'+
            (data.web_state=='Out of Stock'?'<div class="ordering log_in can_not_order  out_of_stock_row  '+data.out_of_stock_class+' "><span class="product_footer label ">'+data.out_of_stock_label+'</span><span class="product_footer reminder"><i class="fa fa-envelope hide" aria-hidden="true"></i>  </span></div>':'')+
            (data.web_state=='For Sale'?'<div class="order_row empty"><input maxlength=6 class=\'order_input \' type="text"\' size=\'2\' value=\'\' data-ovalue=\'\'><div class="label sim_button" style="margin-left:57px"><i class="fa fa-hand-pointer fa-fw" aria-hidden="true"></i> <span >{if empty($labels._ordering_order_now)}{t}Order now{/t}{else}{$labels._ordering_order_now}{/if}</span></div>':'')+
            '</div>'+
            '</div>'


        );







        $('#preview')[0].contentWindow.add_product_to_products_block(block_key,new_product);
        close_products_add_product_dialog();
        $('#save_button').addClass('save button changed valid')


    }


    $('.webpage_showcase').addClass('hide')

    $('.hide_webpage_editor').removeClass('hide')
    $('.show_webpage_editor').addClass('hide')
    $('#tabs').addClass('hide')



</script>
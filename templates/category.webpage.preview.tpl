{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 24 November 2016 at 17:33:42 GMT+8, Yiwu , China
 Copyright (c) 2016, Inikoo

 Version 3
-->
*}


<div style="padding:20px;border-bottom:1px solid #ccc">



<span style="margin-right:20px" class="hide">
    <i class="fa fa-desktop padding_right_5 " aria-hidden="true"></i>
    <i class="fa fa-tablet padding_right_5" aria-hidden="true"></i>
    <i class="fa fa-mobile" aria-hidden="true"></i>
</span>

<span class="button " onclick="toggle_logged_in_view(this)"><i class="fa fa-toggle-on " aria-hidden="true" alt="{t}On{/t}"></i> <span class="unselectable">{t}Logged in{/t}</span></span>


    <span id="publish" class="button save {if $webpage->get('Publish')}changed valid{/if}" style="float:right" onclick="publish(this)"><span class="unselectable">{t}Publish{/t}</span> <i class="fa fa-rocket" aria-hidden="true"></i></span>

<span style="float:right;margin-right:60px" >
    <i id="description_block_on" class="fa toggle_description_block fa-header fa-fw button" aria-hidden="true"  ></i>


    <span id="description_block_off"  class="toggle_description_block fa-stack hide button" style="position:relative;top:-5px;left:5px"  >
  <i class="fa fa-header fa-stack-1x"></i>
  <i class="fa fa-close fa-stack-2x very_discreet error"></i>
</span>

</span>









</div>


{assign 'see_also'  $category->webpage->get_see_also() }

<style>






    input {
        position: relative;
        bottom: 2px;

        padding: 4px 6px;
        font-weight: normal;
        color: #555;
        vertical-align: middle;
        background-color: #fff;
        border: 1px solid #ccc;
        margin: 0px;
        font-family: inherit;
        font-size: 100%;
        line-height: normal;
        outline: none;
        -webkit-box-sizing: content-box;
        -moz-box-sizing: content-box;
        box-sizing: content-box;
        -webkit-appearance: none;
    }


    h1{

        font-family: "Ubuntu",Helvetica,Arial,sans-serif;
        font-weight: 800;
        font-size:21px;
        padding:0;
        margin:17.4333px 0px;

    }






    #page_content{
        padding:20px;
        border:1px solid #d0d0d0;
        border-top:none;
        width:992px;
        margin:auto;

        font-family: "Ubuntu",Helvetica,Arial,sans-serif;

    }




    #description_block{
        position:relative; width:935px;margin:auto;
        padding:0px;margin-bottom:20px;

    }

    .webpage_content_header{
        position:relative;float:left}

    #webpage_content_header_image{
        width:250px;left:20px
    }

    #webpage_content_header_text{
        left:100px; width:450px

    }

    .webpage_content_header_text,.webpage_content_header_image {
        border: 1px dashed transparent;
    }


    .product_blocks{
        width:970px;margin:auto;

    }

    .product_blocks    .block {
        border: 1px solid #ccc;
        background:#fff;
        padding:0px 0px 0px 0px;

    }

    .product_blocks .block:hover{
        border:1px solid #A3C5CC;
    }

    .block.four{
        float:left;width:218px;margin-left:18px;
    }


    .block.product_showcase{
        height:319px}


    .wrap_to_center {

        display: table-cell;
        text-align: center;
        vertical-align: middle;
        width: 218px;
        height: 160px;
        margin-bottom:10px
    }
    .wrap_to_center img {
        vertical-align: bottom;max-width:218px;max-height:160px
    }






    .product_description{
        padding-left:10px;padding-right:10px;display:block;height:51px;
    }

    .product_prices{
        padding-left:10px;padding-right:10px;display:block;height:37px;
    }


    .product_prices.log_out{
        text-align: center;font-style: italic; color:#236E4B

    }

    .product_prices .product_price{
        color:#236E4B
    }



    .more_info{
        cursor:pointer;position:absolute;width:40px;top:-1px;left:179px}





    .description_block{
        margin-bottom:20px;background:#fff;padding:10px 20px;border:1px solid #eee}




    .ordering.log_out div {
        float:left;width: 50%;background-color: darkorange;color:whitesmoke;text-align: center;
        }

    .ordering.log_out span {
        height:28px;
        padding:7px 20px 5px 10px;
        display:block;height:20px;cursor:pointer;
        font-weight: 800;
    }

    .ordering.log_out span.login_button{
        border-right:1px solid white;
    }


    .ordering.log_out span:hover {
        background-color: brown;
    }


    .ordering{
    }




    .order_input{
        float:left;position:relative;top:2px;border-right:none;border-left:none;height:20px;width:40px
    }



    .product_footer{
        height:28px;position:relative;top:2px;
       padding:7px 20px 3px 10px;
        display:block;height:20px;cursor:pointer;
        float:left;font-weight: 800;
    }


    .can_not_order .product_footer{
        color:#fff;
        background-color: darkgray;cursor:auto;

    }

    .can_not_order.out_of_stock .product_footer{
        color:#fff;
        background-color: darkgray;

    }

    .can_not_order.launching_soon .product_footer{
        color:#fff;
        background-color: darkseagreen;

    }


    .can_not_order .product_footer.label{
        width:154px;
    }

    .can_not_order .product_footer.reminder{
        padding:6px 10px 2px 10px;
        background-color: inherit;
        color: darkgray;border-top:1px solid darkgray;cursor:pointer;
    }

    .can_not_order.launching_soon .product_footer.reminder{
        border-top:1px solid darkseagreen;
        color: darkseagreen;
    }




    .product_footer.favorite{
        padding:6px 10px 2px 10px;
        background-color: inherit;
        color: darkgray;border-top:1px solid #ccc
    }

    .product_footer.favorite.marked{

        color: deeppink;
    }

    .product_footer.order_button{
        width:102px;
        color:#fff;
        background-color: orange;
    }

    .product_footer.order_button:hover{
        color:#fff;
        background-color: maroon;
    }

    .product_footer.order_button.ordered{
        color:#fff;
        background-color: maroon;
    }


    .product_image{
        cursor:pointer}


     .title{
        font-weight:800;font-size:120%;padding-bottom:10px;margin-left:20px
    }

    #related_products .title{
        margin-left:20px
    }


    #bottom_see_also{
        margin:auto;padding:0px;margin-top:10px;width:935px
    }



    #bottom_see_also .item{
        height:220px;width:170px;float:left;text-align:center;margin-left:20px

    }
    #bottom_see_also .item:first-of-type{
        margin-left:0px

    }

    #bottom_see_also .item  .image_container{
    border:1px solid #ccc;height:170px;width:170px;;vertical-align:middle;text-align:center;display: table-cell;

    }

    #bottom_see_also .item  .label{
        font-size:90%;margin-top:5px

    }

    #bottom_see_also  img{
        max-height:168px;max-width: 168px;overflow:hidden;}


    .editing{
        border: 1px dashed lightgrey;
    }

    .product_header_text{
        padding:4px;height:30px;color:brown ;
        border:1px solid transparent;cursor:text;

    }

    .product_header_text p{
        padding:0px ; margin:0px;text-align: center;
        z-index: 100;position:relative;
    }

    .product_header_text:hover{
        border:1px solid lightskyblue;
    }


    {$category->webpage->get('CSS')}

</style>





<span id="ordering_settings" class="hide" data-labels='{ "ordered":"<i class=\"fa fa-thumbs-o-up fa-flip-horizontal \" aria-hidden=\"true\"></i> {t}Ordered{/t}", "order":"<i class=\"fa fa-hand-pointer-o\" aria-hidden=\"true\"></i>  {t}Order now{/t}", "update":"<i class=\"fa fa-hand-pointer-o\" aria-hidden=\"true\"></i>  {t}Update{/t}"  }'></span>

{assign 'content_data' $category->webpage->get('Content Data')}

<div id="page_content" style="position:relative">


    <div id="description_block" class="section description_block {$content_data.description_block.class} " >


        <i class="create_text fa fa-align-center fa-fw button" aria-hidden="true" style="position:absolute;left:-25px;top:10px"></i>
        <i class="create_image fa fa-picture-o fa-fw button" aria-hidden="true" style="position:absolute;left:-25px;top:30px"></i>


        <div id="image_edit_toolbar" class="edit_toolbar hide" section="description_block" style=" z-index: 200;position:relative;">
            <i class="fa fa-window-close fa-fw button" style="margin-bottom:10px" aria-hidden="true"></i><br>

            <form method="post" action="/ar_edit.php" enctype="multipart/form-data" novalidate>
                <input type="file" name="image_upload" id="file_upload" class="input_file" multiple/>
                <label for="file_upload">
                    <i class="fa  fa-picture-o fa-fw button" aria-hidden="true"></i><br>
                </label>
            </form>



            <i class="fa caption_icon fa-comment  fa-fw button " style="margin-top:5px"  aria-hidden="true"></i><br>
            <div class="caption hide" style="position:relative;z-index: 100;border:1px solid #ccc;background-color: white;padding:10px 10px 10px 5px">
                <input id="caption_input" value="" style="width:400px">
            </div>
            <i class="fa  fa-trash error fa-fw button   " style="margin-top:20px" aria-hidden="true"></i><br>
      </div>


        <div id="text_edit_toolbar" class="edit_toolbar hide" section="description_block"  style=" z-index: 200;position:relative;">
            <i class="fa close_edit_text fa-window-close fa-fw button" style="margin-bottom:10px" aria-hidden="true"></i><br>
            <i class="fa  fa-trash error fa-fw button   " style="margin-top:20px" aria-hidden="true"></i><br>

        </div>


        {foreach from=$content_data.description_block.blocks key=id item=data}
            {if $data.type=='text'}

                <div id="{$id}" class="webpage_content_header webpage_content_header_text">
                    {$data.content}
                </div>
            {elseif $data.type=='image'}


                <div  id="{$id}" class="webpage_content_header webpage_content_header_image"  >
                   <img  src="{$data.image_src}"  style="width:100%"  title="{if isset($data.caption)}{$data.caption}{/if}" />
                </div>
            {/if}
        {/foreach}
        <div style="clear:both"></div>
    </div>

    <div id="products" class="product_blocks">
    {foreach from=$products item=product_data key=stack_index}
        <div class="product_wrap">
            {assign 'product' $product_data.object}
            <div id="product_target_div_{$stack_index}" stack_index="{$stack_index}" draggable="{if $product->get('Web State')=='For Sale' }true{else}false{/if}" ondragstart="drag(event)" product_code="{$product->get('Code')}"  index_key="{$product_data.index_key}" product_id="{$product->id}" ondrop="drop(event)" ondragover="allowDrop(event)" class="block four product_showcase " style="margin-bottom:20px;position:relative">

                <div class="product_header_text fr-view" >
                    {$product_data.header_text}
                </div>


                <div class="wrap_to_center product_image" onCLick="console.log('move')">
                    <img draggable="false" class="more_info" src="/art/moreinfo_corner1.png">
                    <img draggable="false" src="{$product->get('Image')}" />
                 </div>


                <div class="product_description"  >
                    <span class="code">{$product->get('Code')}</span>
                    <div class="name">{$product->get('Name')}</div>

                </div>


                <div class="product_prices log_in " >
                    <div class="product_price">{t}Price{/t}: {$product->get('Price')}</div>
                    {assign 'rrp' $product->get('RRP')}
                    {if $rrp!=''}<div>{t}RRP{/t}: {$rrp}</div>{/if}
                </div>

                <div class="product_prices log_out hide" >
                    <div >{t}For prices, please login or register{/t}</div>
                 </div>


                {if $product->get('Web State')=='Out of Stock'}
                    <div class="ordering log_in can_not_order {$product->get('Out of Stock Class')} ">

                        <span class="product_footer label ">{$product->get('Out of Stock Label')}</span>
                        <span class="product_footer reminder"><i class="fa fa-envelope-o" aria-hidden="true"></i>  </span>


                    </div>
                {else if $product->get('Web State')=='For Sale'}

                <div class="ordering log_in " >
                    <input maxlength=6  class='order_input ' id='but_qty{$product->id}'   type="text" size='2'  value='{$product->get('Ordered Quantity')}' ovalue='{$product->get('Ordered Quantity')}'>
                     <span class="product_footer order_button"   ><i class="fa fa-hand-pointer-o" aria-hidden="true"></i> {t}Order now{/t}</span>
                     <span class="product_footer  favorite "><i class="fa fa-heart-o" aria-hidden="true"></i>  </span>


                </div>



                {/if}
            <div class="ordering log_out hide" >
                <div ><span class="login_button" >{t}Login{/t}</span></div>
                <div ><span class="register_button" >{t}Register{/t}</span></div>
            </div>


            </div>


        </div>
    {/foreach}
    <div style="clear:both"></div>
    </div>

    <div id="related_products"  class="product_blocks {if $related_products|@count eq 0}hide{/if}">
        <div class="title">{t}See also{/t}:</div>
        {foreach from=$related_products item=product key=stack_index}
    <div class="product_wrap">

        <div id="product_target_div_{$stack_index}" stack_index="{$stack_index}" xdraggable="{if $product->get('Web State')=='For Sale' }true{else}false{/if}" xondragstart="drag(event)" product_code="{$product->get('Code')}" product_id="{$product->id}" xondrop="drop(event)" xondragover="allowDrop(event)" class="block four product_showcase " style="margin-bottom:20px;position:relative">

            <div style=padding:4px;height:30px;color:brown ;">
        </div>


        <div class="wrap_to_center product_image" onCLick="console.log('move')">
            <img draggable="false" class="more_info" src="/art/moreinfo_corner1.png">
            <img draggable="false" src="{$product->get('Image')}" />
        </div>


        <div class="product_description"  >
            <span class="code">{$product->get('Code')}</span>
            <div class="name">{$product->get('Name')}</div>

        </div>


        <div class="product_prices log_in " >
            <div class="product_price">{t}Price{/t}: {$product->get('Price')}</div>
            {assign 'rrp' $product->get('RRP')}
            {if $rrp!=''}<div>{t}RRP{/t}: {$rrp}</div>{/if}
        </div>

        <div class="product_prices log_out hide" >
            <div >{t}For prices, please login or register{/t}</div>
        </div>


        {if $product->get('Web State')=='Out of Stock'}
            <div class="ordering log_in can_not_order {$product->get('Out of Stock Class')} ">

                <span class="product_footer label ">{$product->get('Out of Stock Label')}</span>
                <span class="product_footer reminder"><i class="fa fa-envelope-o" aria-hidden="true"></i>  </span>


            </div>
        {else if $product->get('Web State')=='For Sale'}

            <div class="ordering log_in " >
                <input maxlength=6  class='order_input ' id='but_qty{$product->id}'   type="text" size='2'  value='{$product->get('Ordered Quantity')}' ovalue='{$product->get('Ordered Quantity')}'>
                <span class="product_footer order_button"   ><i class="fa fa-hand-pointer-o" aria-hidden="true"></i> {t}Order now{/t}</span>
                <span class="product_footer  favorite "><i class="fa fa-heart-o" aria-hidden="true"></i>  </span>


            </div>



        {/if}
        <div class="ordering log_out hide" >
            <div ><span class="login_button" >{t}Login{/t}</span></div>
            <div ><span class="register_button" >{t}Register{/t}</span></div>
        </div>


    </div>


</div>
{/foreach}
        <div style="clear:both"></div>
    </div>

     <div id="bottom_see_also"  class="{if $see_also|@count eq 0}hide{/if}">
         <div class="title">{t}See also{/t}:</div>
         <div>
         {foreach from=$see_also item=see_also_item name=foo}
                <div class="item" >
                    <div class="image_container" >
                        <a href="http://{$see_also_item->get('URL')}"> <img src="{$see_also_item->get('Image')}" style="" /> </a>
                    </div>
                    <div class="label" >
                        {$see_also_item->get('Name')}
                    </div>
                </div>
            {/foreach}
             </div>
            <div style="clear:both">
            </div>

    </div>

</div>

<script>

    
    var save_webpage_content_header_state_timer=false;
    var save_image_caption=false;

    count=1;



    if($('#description_block').hasClass('hide')){

        $('#description_block_on').addClass('hide')
        $('#description_block_off').removeClass('hide')


    }


    var droppedFiles = false;

    $('#file_upload').on('change', function (e) {



            var ajaxData = new FormData();

            //var ajaxData = new FormData( );
            if (droppedFiles) {
                $.each(droppedFiles, function (i, file) {
                    ajaxData.append('files', file);
                });
            }


            $.each($('#file_upload').prop("files"), function (i, file) {
                ajaxData.append("files[" + i + "]", file);

            });


            ajaxData.append("tipo", 'upload_images')
            ajaxData.append("parent", 'old_page')
            ajaxData.append("parent_key", '{$webpage->id}')
           ajaxData.append("parent_object_scope", JSON.stringify( {
               scope:'content',
               section:  $('#image_edit_toolbar').attr('section'),
               block: $('#image_edit_toolbar').attr('block')

           } ) )

            var image=$('#'+$('#image_edit_toolbar').attr('block')+' img')

            $.ajax({
                url: "/ar_upload.php",
                type: 'POST',
                data: ajaxData,
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,


                complete: function () {

                },
                success: function (data) {

                    console.log(data)

                    if (data.state == '200') {
                    console.log(data.image_src)
                        image.attr('src',data.image_src)

                        if($('#publish').find('i').hasClass('fa-rocket')) {

                            if (data.publish) {
                                $('#publish').addClass('changed valid')
                            } else {
                                $('#publish').removeClass('changed valid')
                            }
                        }




                    } else if (data.state == '400') {

                    }


                },
                error: function () {

                }
            });



    });

    function allowDrop(ev) {
        ev.preventDefault();
    }

    function drag(ev) {

       // console.log(ev.target.id)

        ev.dataTransfer.setData("id", ev.target.id);

        ev.dataTransfer.setData("stack_index", ev.target.getAttribute('stack_index'));
        ev.dataTransfer.setData("product_id", ev.target.getAttribute('product_id'));

        ev.dataTransfer.setData("product_code", ev.target.getAttribute('product_code'));


        ev.dataTransfer.setData("html",  ev.target.innerHTML );



    }

    function drop(ev) {

        ev.preventDefault();
        var product_showcase=  $( ev.target).closest('div.product_wrap').find('.product_showcase')
        var target_stack_index=product_showcase.attr('stack_index')

        if(target_stack_index==ev.dataTransfer.getData("stack_index")){
            return;
        }else if(target_stack_index<ev.dataTransfer.getData("stack_index")){

            var tmp_html = product_showcase.html();
            var tmp_product_code = product_showcase.attr('product_code');
            var tmp_product_id = product_showcase.attr('product_id');

            product_showcase.html(ev.dataTransfer.getData("html"))
            product_showcase.attr('product_code',ev.dataTransfer.getData("product_code"))
            product_showcase.attr('product_id',ev.dataTransfer.getData("product_id"))

            change_next(  ev.dataTransfer.getData("stack_index") , tmp_html ,tmp_product_code,tmp_product_id,product_showcase.closest('.product_wrap').next().find('.product_showcase'))
            save_stack_index(target_stack_index,ev.dataTransfer.getData("product_id"))

        }else{


        }



    }

    function change_next(pivot_id,html,product_code,product_id,element){

        if(element.attr('id')==undefined   ){
            return;
        }

        var tmp_html = element.html();
        var tmp_product_code = element.attr('product_code');
        var tmp_product_id = element.attr('product_id');

        element.html(html)
        element.attr('product_code',product_code)
        element.attr('product_id',product_id)

        if( element.attr('stack_index')==pivot_id  ){
            return;
        }
        change_next(pivot_id,tmp_html,tmp_product_code,tmp_product_id,element.closest('.product_wrap').next().find('.product_showcase'))


    }

    function  save_stack_index(stack_index,product_id) {

        var request = '/ar_edit.php?tipo=edit_category_stack_index&key=' + {$category->id} + '&stack_index=' + (stack_index-0.5) + '&subject_key='+product_id+ '&webpage_key='+{$webpage->id}
        console.log(request)
        $.getJSON(request, function (data) {

            if(data.state==200){


                if($('#publish').find('i').hasClass('fa-rocket')) {

                    if (data.publish) {
                        $('#publish').addClass('changed valid')
                    } else {
                        $('#publish').removeClass('changed valid')
                    }
                }

            }

        })
    }


    function publish(element){


        var icon=$(element).find('i')

        if(icon.hasClass('fa-spin')) return;


        icon.removeClass('fa-rocket').addClass('fa-spinner fa-spin')

        var request = '/ar_edit.php?tipo=publish_webpage&parent_key=' + {$webpage->id}
        console.log(request)
        $.getJSON(request, function (data) {

            icon.addClass('fa-rocket').removeClass('fa-spinner fa-spin')
            $(element).removeClass('changed valid')

        })


    }

   
    function save_webpage_content_header_state(){

        var css='';

        webpage_content_header_state={}


                css+='#description_block{ ' +
                    'height:'+$('#description_block').height()+'px}'



        $( ".webpage_content_header" ).each(function( index ) {
            webpage_content_header_state.top= $( this ).offset().top-$('#description_block').offset().top-1
            webpage_content_header_state.left= $( this ).offset().left-$('#description_block').offset().left-1
            webpage_content_header_state.width=$(this).width()
            webpage_content_header_state.height=$(this).height()


            css+='#'+$( this ).attr('id')+'{ position:absolute;margin-left:0px; top:'+webpage_content_header_state.top+'px;left:'+webpage_content_header_state.left+'px;width:'+webpage_content_header_state.width+'px;height:'+webpage_content_header_state.height+'px}'

        });



        var request = '/ar_edit.php?tipo=edit_webpage&key=' + {$category->webpage->id} + '&field=css&value=' + btoa(css)
       // console.log(request)
        $.getJSON(request, function (data) {
            console.log(data)
            if(data.state==200){



                if($('#publish').find('i').hasClass('fa-rocket')) {

                    if (data.publish) {
                        $('#publish').addClass('changed valid')
                    } else {
                        $('#publish').removeClass('changed valid')
                    }
                }

            }

        })


}

    $('#description_block').resizable(

            {
                minWidth:935,
                maxWidth:935,
                stop: function (event, ui) {




                    if(save_webpage_content_header_state_timer)
                        clearTimeout(save_webpage_content_header_state_timer);
                    save_webpage_content_header_state_timer = setTimeout(function(){ save_webpage_content_header_state(); }, 750);

                }

            }

    );


    $('.product_header_text').dblclick(function() {


     $(this).froalaEditor({


         toolbarInline: true,
         charCounterCount: false,
         toolbarButtons: ['bold', 'italic', 'underline', 'strikeThrough', 'color', 'emoticons', '-', 'paragraphFormat', 'align', 'formatOL', 'formatUL', 'indent', 'outdent', '-', 'undo', 'redo'],

         saveInterval: 500,
         saveParam: 'value',

         saveURL: '/ar_edit.php',

         saveMethod: 'POST',

         saveParams: {
             webpage_key:  {$category->webpage->id},
             key: $(this).closest('.product_showcase').attr('index_key'),
             tipo: 'update_product_category_index',
             type: 'header_text',



         }


     }).on('froalaEditor.save.after', function (e, editor, response) {


         var data=jQuery.parseJSON(response)

         if(data.state==200){



             if($('#publish').find('i').hasClass('fa-rocket')) {

                 if (data.publish) {
                     $('#publish').addClass('changed valid')
                 } else {
                     $('#publish').removeClass('changed valid')
                 }
             }

         }
     })

    })

   $('.toggle_description_block').click(function(){
        if($('#description_block_on').hasClass('hide')){

            $('#description_block_on').removeClass('hide')
            $('#description_block_off').addClass('hide')
            $('#description_block').removeClass('hide');

            var type='remove_class'

        }else{
            $('#description_block_on').addClass('hide')
            $('#description_block_off').removeClass('hide')
            $('#description_block').addClass('hide');
            var type='add_class'

        }



       var request = '/ar_edit.php?tipo=webpage_content_data&parent=page&parent_key=' + {$category->webpage->id} +'&section=description_block&block=&type='+type+'&value=hide'


       $.getJSON(request, function (data) {

           if (data.state == 200) {



               if ($('#publish').find('i').hasClass('fa-rocket')) {
                   if (data.publish) {
                       $('#publish').addClass('changed valid')
                   } else {
                       $('#publish').removeClass('changed valid')
                   }
               }

           }

       })


       }
   )


    $('#page_content').on( "dblclick", ".webpage_content_header_image", function() {


var position=$(this).position();


        $('#image_edit_toolbar').removeClass('hide').css({
            position: 'absolute',
            left:position.left - 25 + "px",
            top: position.top + 5 + "px"
        }).attr('block',$(this).attr('id'))

        $(this).draggable( 'disable' ).resizable('destroy').addClass('editing')


    })


    $('#image_edit_toolbar .fa-window-close').click(function() {
        $('#image_edit_toolbar').addClass('hide')
        $('#'+$('#image_edit_toolbar').attr('block')).removeClass('editing').draggable( 'enable' ).resizable(
            {
                stop: function (event, ui) {
                    if(save_webpage_content_header_state_timer)
                        clearTimeout(save_webpage_content_header_state_timer);
                    save_webpage_content_header_state_timer = setTimeout(function(){ save_webpage_content_header_state(); }, 750);

                }

            }

        );

    })


    $('#image_edit_toolbar .fa-trash,#text_edit_toolbar .fa-trash').click(function() {

      var toolbar=$(this).closest('.edit_toolbar')

        toolbar.addClass('hide')
        $('#'+toolbar.attr('block')).remove()

        var request = '/ar_edit.php?tipo=webpage_content_data&parent=page&parent_key=' + {$category->webpage->id} +'&section=description_block&block='+toolbar.attr('block')+'&type=remove_block&value='


        $.getJSON(request, function (data) {

            if (data.state == 200) {



                if ($('#publish').find('i').hasClass('fa-rocket')) {
                    if (data.publish) {
                        $('#publish').addClass('changed valid')
                    } else {
                        $('#publish').removeClass('changed valid')
                    }
                }

            }

        })



    })


    $('.create_text').click(function() {


    var section=$(this).closest('div.section');

        var datetime = new Date();


    text = $('<div class="webpage_content_header webpage_content_header_text" style="width:150px;height:100px;text-align:center" ><h1>Bla bla</h1><p>bla bla bla</p></div>')
        .attr('id', 'text'+datetime.getTime())
        .draggable(
        {
            containment: "#description_block",
            scroll: false,
            stop: function (event, ui) {

                if(save_webpage_content_header_state_timer)
                    clearTimeout(save_webpage_content_header_state_timer);
                save_webpage_content_header_state_timer = setTimeout(function(){ save_webpage_content_header_state(); }, 750);



            }
        }
    )
        .resizable(
            {
                stop: function (event, ui) {

                    // console.log(this.id)
                    // console.log(ui.size)

                    if(save_webpage_content_header_state_timer)
                        clearTimeout(save_webpage_content_header_state_timer);
                    save_webpage_content_header_state_timer = setTimeout(function(){ save_webpage_content_header_state(); }, 750);

                }

            }
        ) .appendTo(section)



    var request = '/ar_edit.php?tipo=webpage_content_data&parent=page&parent_key=' + {$category->webpage->id} +'&section=description_block&block='+text.attr('id')+'&type=text&value='+text.html()


    $.getJSON(request, function (data) {

        if (data.state == 200) {



            if ($('#publish').find('i').hasClass('fa-rocket')) {
                if (data.publish) {
                    $('#publish').addClass('changed valid')
                } else {
                    $('#publish').removeClass('changed valid')
                }
            }

        }

    })




})

    $('.create_image').click(function() {


        var section=$(this).closest('div.section');
        var datetime = new Date();

        var img = $('<div class="webpage_content_header webpage_content_header_image" ><img src="/art/nopic.png" style="width:100%"></div>')
            .attr('id', 'image'+datetime.getTime())
            .draggable(
            {
                containment: "#description_block",
                scroll: false,
                stop: function (event, ui) {

                    if(save_webpage_content_header_state_timer)
                        clearTimeout(save_webpage_content_header_state_timer);
                    save_webpage_content_header_state_timer = setTimeout(function(){ save_webpage_content_header_state(); }, 750);



                }
            }
        )
            .resizable(
                {
                    stop: function (event, ui) {

                        // console.log(this.id)
                        // console.log(ui.size)

                        if(save_webpage_content_header_state_timer)
                            clearTimeout(save_webpage_content_header_state_timer);
                        save_webpage_content_header_state_timer = setTimeout(function(){ save_webpage_content_header_state(); }, 750);

                    }

                }
            ) .appendTo(section)



        var request = '/ar_edit.php?tipo=webpage_content_data&parent=page&parent_key=' + {$category->webpage->id} +'&section=description_block&block='+img.attr('id')+'&type=add_image&value='+img.find('img').attr('src')


        $.getJSON(request, function (data) {

            if (data.state == 200) {



                if ($('#publish').find('i').hasClass('fa-rocket')) {
                    if (data.publish) {
                        $('#publish').addClass('changed valid')
                    } else {
                        $('#publish').removeClass('changed valid')
                    }
                }

            }

        })




    })


    $('#image_edit_toolbar .fa-comment').click(function() {

        var caption= $(this).closest('.edit_toolbar').find('div.caption')

        if(caption.hasClass('hide')) {

            caption.removeClass('hide').css({
                position: 'absolute', left: $(this).position().left + 25 + "px", top: $(this).position().top - 10 + "px"
            }).find('input').val($('#' + $(this).closest('.edit_toolbar').attr('block') + ' img').attr('title'))
        }else{

            clearTimeout(save_image_caption);
            save_caption()
            caption.addClass('hide')

        }


    })


    $("#caption_input").on('input propertychange', function(){



        $(this).closest('.edit_toolbar').find('.caption_icon').removeClass('fa-comment').addClass('fa-spinner fa-spin')

        if(save_image_caption)
            clearTimeout(save_image_caption);
        save_image_caption = setTimeout(function(){ save_caption(); }, 400);


    })


    function save_caption() {

        var caption=$('#caption_input').val()
        var block=$('#image_edit_toolbar').attr('block')

        var request = '/ar_edit.php?tipo=webpage_content_data&parent=page&parent_key=' + {$category->webpage->id} +'&section=description_block&block=' + block + '&type=caption&value=' + caption

        $.getJSON(request, function (data) {

            if (data.state == 200) {

                $('#image_edit_toolbar').find('.caption_icon').addClass('fa-comment').removeClass('fa-spinner fa-spin')
                $('#'+block+' img').attr('title',caption)

                if ($('#publish').find('i').hasClass('fa-rocket')) {
                    if (data.publish) {
                        $('#publish').addClass('changed valid')
                    } else {
                        $('#publish').removeClass('changed valid')
                    }
                }

            }

        })

    }


        $('#page_content').on( "dblclick", ".webpage_content_header_text", function() {


           if(! $('#text_edit_toolbar').hasClass('hide')){
               return
           }

            var position=$(this).position();

        $(this).draggable( 'disable' ).resizable('destroy').addClass('editing')

            console.log($(this).position())

        $('#text_edit_toolbar').removeClass('hide').css({
            position: 'absolute',
            left:position.left - 25 + "px",
            top: position.top + 5 + "px"
        }).attr('block',$(this).attr('id'))




        $(this).froalaEditor({


            toolbarInline: true,
            charCounterCount: false,
            toolbarButtons: ['bold', 'italic', 'underline', 'strikeThrough', 'color', 'emoticons', '-', 'paragraphFormat', 'align', 'formatOL', 'formatUL', 'indent', 'outdent', '-', 'undo', 'redo'],

            saveInterval: 500,
            saveParam: 'value',

            saveURL: '/ar_edit.php',

            saveMethod: 'POST',

            saveParams: {
                tipo: 'webpage_content_data',
                parent: 'page',
                parent_key:  {$category->webpage->id},
                section: 'description_block',
                block: $(this).attr('id'),
                type: 'text'

            }


        }).on('froalaEditor.save.after', function (e, editor, response) {


            var data=jQuery.parseJSON(response)

            if(data.state==200){



                if($('#publish').find('i').hasClass('fa-rocket')) {

                    if (data.publish) {
                        $('#publish').addClass('changed valid')
                    } else {
                        $('#publish').removeClass('changed valid')
                    }
                }

            }
        })











    });





    $('#text_edit_toolbar .fa-window-close').click(function() {

        $('#text_edit_toolbar').addClass('hide')

       var block= $('#'+$('#text_edit_toolbar').attr('block'))

        block.froalaEditor('destroy')

        block.draggable( 'enable' ).removeClass('editing')

        block.resizable(
            {
                stop: function (event, ui) {
                    if(save_webpage_content_header_state_timer)
                        clearTimeout(save_webpage_content_header_state_timer);
                    save_webpage_content_header_state_timer = setTimeout(function(){ save_webpage_content_header_state(); }, 750);

                }

            }

        );

     //



    });



    $('.webpage_content_header')
            .draggable(
                    {
                        containment: "#description_block",
                        scroll: false,
                        stop: function (event, ui) {

                            if(save_webpage_content_header_state_timer)
                                clearTimeout(save_webpage_content_header_state_timer);
                            save_webpage_content_header_state_timer = setTimeout(function(){ save_webpage_content_header_state(); }, 750);



                        }
                    }
            )
            .resizable(
                    {
                        stop: function (event, ui) {


                            if(save_webpage_content_header_state_timer)
                                clearTimeout(save_webpage_content_header_state_timer);
                            save_webpage_content_header_state_timer = setTimeout(function(){ save_webpage_content_header_state(); }, 750);

                        }

                    }
            );





    $('.favorite').click(function() {
        var icon= $( this ).find('i');


        if(icon.hasClass('fa-heart-o')){
            $( this ).addClass('marked')
            icon.removeClass('fa-heart-o').addClass('fa-heart')

        }else{
            $( this ).removeClass('marked')

            icon.removeClass('fa-heart').addClass('fa-heart-o')


        }

    });



    $('.reminder').click(function() {
        var icon= $( this ).find('i');


        if(icon.hasClass('fa-envelope-o')){
            $( this ).addClass('marked')
            icon.removeClass('fa-envelope-o').addClass('fa-envelope')

        }else{
            $( this ).removeClass('marked')

            icon.removeClass('fa-envelope').addClass('fa-envelope-o')


        }

    });

    $('.order_button').hover(
        function() {
            var input= $( this ).closest('.ordering').find('.order_input');
            if(input.val()==''){
                $( this ).closest('.ordering').find('.order_input').val(1)
            }


        }, function() {
            var input= $( this ).closest('.ordering').find('.order_input');
            if(input.attr('ovalue')==''){
                $( this ).closest('.ordering').find('.order_input').val('')
            }
        }
    );

    $('.order_button').click(function() {
        var input= $( this ).closest('.ordering').find('.order_input');

        var order_qty=input.val()

        input.attr('ovalue',order_qty)



        if(order_qty>0){
            $(this).html($('#ordering_settings').data('labels').ordered).addClass('ordered')
        }else{
            $(this).html($('#ordering_settings').data('labels').order).removeClass('ordered')

        }


    });


    $(".order_input").on('input propertychange', function(){



        $(this).val( $(this).val().replace(/[^\d]/g,'') )

        var order_qty=$(this).val()

        var button=$( this ).closest('.ordering').find('.order_button');

        if(order_qty!=$(this).attr('ovalue')){


            button.html( $('#ordering_settings').data('labels').update).addClass('ordered')
        }else{

            if(order_qty>0){
                button.html($('#ordering_settings').data('labels').ordered).addClass('ordered')
            }else{
                button.html($('#ordering_settings').data('labels').order).removeClass('ordered')

            }

        }

    });

    function toggle_logged_in_view(element){

        var icon=$(element).find('i')
        if(icon.hasClass('fa-toggle-on')){
            icon.removeClass('fa-toggle-on').addClass('fa-toggle-off')

            $('.product_prices.log_in').addClass('hide')
            $('.product_prices.log_out').removeClass('hide')

            $('.ordering.log_in').addClass('hide')
            $('.ordering.log_out').removeClass('hide')


        }else{
            icon.addClass('fa-toggle-on').removeClass('fa-toggle-off')

            $('.product_prices.log_in').removeClass('hide')
            $('.product_prices.log_out').addClass('hide')
            $('.ordering.log_in').removeClass('hide')
            $('.ordering.log_out').addClass('hide')

        }



    }


    $('.image_upload').on('change', function (e) {

        console.log('caca')

    });




</script>
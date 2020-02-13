{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 17 July 2017 at 10:04:33 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}{include file="theme_1/_head.theme_1.EcomB2B.tpl"}
<body xmlns="http://www.w3.org/1999/html" data-device_prefix="" class="{$website->get('background_type')}" data-ws="{if $logged_in and $website->get('Website Type')=='EcomDS'}y{else}n{/if}"  {if $logged_in}   data-ws_key="{$ws_key}" {/if}>
{include file="analytics.tpl"}

{if $website->get('Website Status')=='InProcess'}
    {foreach from=$content.blocks item=$block key=key}
        {include file="theme_1/blk.{$block.type}.theme_1.EcomB2B.tpl" data=$block key=$key  }
    {/foreach}
{else}
{if $logged_in}
    <span id="ordering_settings" class="hide"  data-website_key="{$website->id}" data-labels='{
    "zero_money":"{$zero_money}",
    "ordered":"<i class=\"fa fa-thumbs-up fa-flip-horizontal fa-fw \" aria-hidden=\"true\"></i> <span class=\"order_button_text\"> {if empty($labels._ordering_ordered)}{t}Ordered{/t}{else}{$labels._ordering_ordered}{/if}</span>",
    "order":"<i class=\"fa fa-hand-pointer fa-fw \" aria-hidden=\"true\"></i>  <span class=\"order_button_text\">{if empty($labels._ordering_order_now)}{t}Order now{/t}{else}{$labels._ordering_order_now}{/if}</span>",
    "update":"<i class=\"fa fa-hand-pointer fa-fw \" aria-hidden=\"true\"></i>  <span class=\"order_button_text\">{if empty($labels._ordering_updated)}{t}Updated{/t}{else}{$labels._ordering_updated}{/if}</span>"
    }'></span>
{/if}
<div class="wrapper_boxed">
    <div class="site_wrapper">
        {include file="theme_1/header.theme_1.EcomB2B.tpl"}
        <div id="body" class="{$website->get('content_background_type')}">
            {if $navigation.show}
                <div class="navigation top_body">
                    <div class="breadcrumbs">
                    {foreach from=$navigation.breadcrumbs item=$breadcrumb name=breadcrumbs}
                    <span class="breadcrumb {if isset($breadcrumb.class)}{$breadcrumb.class}{/if} "><a href="{$breadcrumb.link}" title="{$breadcrumb.title}">{$breadcrumb.label}</a> </span>
                        {if !$smarty.foreach.breadcrumbs.last}
                            <i class="fas padding_left_10 padding_right_10 fa-angle-double-right"></i>
                        {/if}
                    {/foreach}
                    </div>
                    <div class="nav">{if $navigation.prev}<a href="{$navigation.prev.link}" title="{$navigation.prev.title}"><i class="fas fa-arrow-left"></i></a>{/if} {if $navigation.next}<a
                            href="{$navigation.next.link}" title="{$navigation.next.title}"><i class="fas fa-arrow-right next"></i></a>{/if}</div>
                    <div style="clear:both"></div>
                </div>
            {/if}
            {if isset($discounts) and count($discounts.deals)>0 }
                <div class="discounts top_body"  >
                    {foreach from=$discounts.deals item=deal_data }
                    <div class="discount_card" key="{$deal_data.key}">
                        <div class="discount_icon" >{$deal_data.icon}</div>
                        <span  class="discount_name">{$deal_data.name}</span>
                        {if  $deal_data.until!=''}
                            <small class="padding_left_10"><span id="_offer_valid_until" class="website_localized_label" >
                                {if !empty($labels._offer_valid_until)}{$labels._offer_valid_until}{else}{t}Valid until{/t}{/if}</span>: {$deal_data.until_formatted}
                            </small>
                        {/if}
                        <br/>
                        <span  class="discount_term">{$deal_data.term}</span>
                        <span  class="discount_allowance">{$deal_data.allowance}</span>
                    </div>
                    {/foreach}
                    <div style="clear:both"></div>
                </div>
            {/if}

            {assign "with_iframe" false}
            {assign "with_login" false}
            {assign "with_register" false}
            {assign "with_basket" false}
            {assign "with_client_basket" false}
            {assign "with_checkout" false}
            {assign "with_profile" false}
            {assign "with_client" false}
            {assign "with_favourites" false}
            {assign "with_portfolio" false}
            {assign "with_products_portfolio" false}
            {assign "with_clients" false}
            {assign "with_clients_orders" false}
            {assign "with_client_order_new" false}
            {assign "with_search" false}
            {assign "with_thanks" false}
            {assign "with_gallery" false}
            {assign "with_product_order_input" false}
            {assign "with_reset_password" false}
            {assign "with_unsubscribe" false}
            {assign "with_category_products" false}
            {assign "with_datatables" false}

            {if !empty($content.blocks) and  $content.blocks|is_array}
            {foreach from=$content.blocks item=$block key=key}


                {if $block.show}

                    {if $block.type=='basket' }
                        {if $logged_in}{assign "with_basket" 1}
                            <div id="basket">
                                <div style="text-align: center">
                                    <i style="font-size: 60px;padding:100px" class="fa fa-spinner fa-spin"></i>
                                </div>

                            </div>
                        {else}
                            {include file="theme_1/blk.forbidden.theme_1.EcomB2B.tpl" data=$block key=$key   }
                        {/if}
                        {elseif $block.type=='client_basket'}
                            {if $logged_in}{assign "with_client_basket" 1}
                                <div id="client_basket">
                                    <div style="text-align: center">
                                        <i style="font-size: 60px;padding:100px" class="fa fa-spinner fa-spin"></i>
                                    </div>

                                </div>
                            {else}
                                {include file="theme_1/blk.forbidden.theme_1.EcomB2B.tpl" data=$block key=$key   }
                            {/if}
                    {elseif $block.type=='profile'}
                        {if $logged_in}
                            {assign "with_profile" 1}
                            <div id="profile">
                                <div style="text-align: center">
                                    <i style="font-size: 60px;padding:100px" class="fa fa-spinner fa-spin"></i>
                                </div>
                            </div>
                        {else}
                            {include file="theme_1/blk.forbidden.theme_1.EcomB2B.tpl" data=$block key=$key   }
                        {/if}
                    {elseif $block.type=='client'}
                        {if $logged_in}
                            {assign "with_client" 1}
                            <div id="client">
                                <div style="text-align: center">
                                    <i style="font-size: 60px;padding:100px" class="fa fa-spinner fa-spin"></i>
                                </div>
                            </div>
                        {else}
                            {include file="theme_1/blk.forbidden.theme_1.EcomB2B.tpl" data=$block key=$key   }
                        {/if}



                    {elseif $block.type=='checkout'}
                        {if $logged_in}{assign "with_checkout" 1}
                            <div id="checkout">
                                <div style="text-align: center">
                                    <i style="font-size: 60px;padding:100px" class="fa fa-spinner fa-spin"></i>
                                </div>
                            </div>
                        {else}
                            {include file="theme_1/blk.forbidden.theme_1.EcomB2B.tpl" data=$block key=$key   }
                        {/if}
                    {elseif $block.type=='favourites'}

                        {if $logged_in}
                            {assign "with_favourites" 1}
                            {assign "with_category_products" 1}
                            <div id="favourites">
                                <div style="text-align: center">
                                    <i style="font-size: 60px;padding:100px" class="fa fa-spinner fa-spin"></i>
                                </div>
                            </div>
                        {else}
                            {include file="theme_1/blk.forbidden.theme_1.EcomB2B.tpl" data=$block key=$key   }
                        {/if}
                    {elseif $block.type=='portfolio'}

                        {if $logged_in}
                            {assign "with_portfolio" 1}
                            {assign "with_datatables" 1}
                            {include file="theme_1/blk.{$block.type}.theme_1.EcomB2B.tpl" data=$block key=$key  }

                        {else}
                            {include file="theme_1/blk.forbidden.theme_1.EcomB2B.tpl" data=$block key=$key   }
                        {/if}
                    {elseif $block.type=='clients'}

                        {if $logged_in}
                            {assign "with_clients" 1}
                            {assign "with_datatables" 1}
                            {include file="theme_1/blk.{$block.type}.theme_1.EcomB2B.tpl" data=$block key=$key  }

                        {else}
                            {include file="theme_1/blk.forbidden.theme_1.EcomB2B.tpl" data=$block key=$key   }
                        {/if}
                    {elseif $block.type=='clients_orders'}

                        {if $logged_in}
                            {assign "with_clients_orders" 1}
                            {assign "with_datatables" 1}
                            {include file="theme_1/blk.{$block.type}.theme_1.EcomB2B.tpl" data=$block key=$key  }

                        {else}
                            {include file="theme_1/blk.forbidden.theme_1.EcomB2B.tpl" data=$block key=$key   }
                        {/if}

                    {elseif $block.type=='client_order_new'}

                        {if $logged_in}
                            {assign "with_client_order_new" 1}
                            {assign "with_datatables" 1}
                            {include file="theme_1/blk.{$block.type}.theme_1.EcomB2B.tpl" data=$block key=$key  }

                        {else}
                            {include file="theme_1/blk.forbidden.theme_1.EcomB2B.tpl" data=$block key=$key   }
                        {/if}

                    {elseif $block.type=='thanks'}



                        {if $logged_in}{assign "with_thanks" 1}
                                <div id="thanks">
                                    <div style="text-align: center">
                                        <i style="font-size: 60px;padding:100px" class="fa fa-spinner fa-spin"></i>
                                    </div>

                                </div>
                            {else}
                                {include file="theme_1/blk.forbidden.theme_1.EcomB2B.tpl" data=$block key=$key   }
                            {/if}
                    {elseif $block.type=='login'}

                        {if !$logged_in}



                            {assign "with_login" 1}
                            {include file="theme_1/blk.{$block.type}.theme_1.EcomB2B.tpl" data=$block key=$key  }

                        {else}
                            {include file="theme_1/blk.already_logged_in.theme_1.EcomB2B.tpl" data=$block key=$key   }
                        {/if}
                    {elseif $block.type=='register'}

                        {if !$logged_in}
                            {assign "with_register" 1}
                            {include file="theme_1/blk.{$block.type}.theme_1.EcomB2B.tpl" data=$block key=$key  }

                        {else}
                            {include file="theme_1/blk.already_logged_in.theme_1.EcomB2B.tpl" data=$block key=$key   }
                        {/if}
                    {else}
                        {if $block.type=='search'   }{assign "with_search" 1}{/if}
                        {if $block.type=='iframe'   }{assign "with_iframe" 1}{/if}
                        {if $block.type=='product'   }{assign "with_gallery" 1}{/if}
                        {if $block.type=='reset_password' }{assign "with_reset_password" 1}{/if}
                        {if $block.type=='unsubscribe'}{assign "with_unsubscribe" 1}{/if}

                        {if $block.type=='category_products' or   $block.type=='products'  or   $block.type=='product' }


                            {if $store->get('Store Type')=='Dropshipping'}
                                {assign "with_products_portfolio" 1}
                            {else}
                                {assign "with_product_order_input" 1}
                            {/if}


                            {/if}
                        {if $block.type=='category_products'  }


                            {if $store->get('Store Type')=='Dropshipping'}
                                {assign "with_products_portfolio" 1}
                            {else}
                                {assign "with_category_products" 1}
                            {/if}

                        {/if}



                        {include file="theme_1/blk.{$block.type}.theme_1.EcomB2B.tpl" data=$block key=$key  }

                    {/if}

                {/if}
            {/foreach}
            {/if}

        </div>



        {include file="theme_1/footer.theme_1.EcomB2B.tpl"}


    </div>

</div>

<script>

    var pinned=false;
    var menu_open = false;
    var mouse_over_menu=false;
    var mouse_over_menu_link=false;

    (function () {
        function getScript(url, success) {
            var script = document.createElement('script');
            script.src = url;
            var head = document.getElementsByTagName('head')[0], done = false;
            script.onload = script.onreadystatechange = function () {
                if (!done && (!this.readyState || this.readyState == 'loaded' || this.readyState == 'complete')) {
                    done = true;
                    success();
                    script.onload = script.onreadystatechange = null;
                    head.removeChild(script);
                }
            };
            head.appendChild(script);
        }

        getScript(
                    {if $logged_in}
                        "/assets/desktop.in.min.js"
                    {else}
                        "/assets/desktop.out.min.js"
                    {/if}
                    , function () {


            {if $website->get('Website Text Font')!=''  and !$logged_in}

            WebFontConfig = {
                google: { families: [ '{$website->get('Website Text Font')}:400,700' ] }
            };

            var wf = document.createElement('script');
            wf.src = ('https:' == document.location.protocol ? 'https' : 'http') +
                '://ajax.googleapis.com/ajax/libs/webfont/1.5.18/webfont.js';
            wf.type = 'text/javascript';
            wf.async = 'true';
            var s = document.getElementsByTagName('script')[0];
            s.parentNode.insertBefore(wf, s);

            {/if}


            $("#bottom_header a.dropdown").hoverIntent(menu_in, menu_out);


            $( "#bottom_header a.dropdown" ).on( 'mouseenter', menu_in_fast ).on( 'mouseleave', menu_out_fast );
            $( "._menu_block" ).on( 'mouseenter', menu_block_in ).on( 'mouseleave', menu_block_out );




            $('#_menu_blocks').width($('#top_header').width())




            $('#header_search_icon').on("click", function () {

                window.location.href = "search.sys?q=" + encodeURIComponent($('#header_search_input').val());


            });




            $("#header_search_input").on('keyup', function (e) {
                if (e.keyCode == 13) {
                    window.location.href = "search.sys?q=" + encodeURIComponent($(this).val());
                }
            });





            {if $with_search==1}


            var _args=document.location.href.split("?")[1];


            //console.log(_args)

            if(_args!=undefined){
                args=_args.split("=");
               if(args[1]!=undefined && args[0]=='q'){
                   $('#search_input').val( decodeURI(args[1]))

               }

            }
            if($('#search_input').val()!=''){
                search($('#search_input').val())
            }

            $('#search_icon').on("click", function () {
                search($('#search_input').val());
            });

            $(document).on('keyup', '#search_input', function (e) {
                if (e.keyCode == 13) {
                    search($(this).val())
                }
            });

            {/if}
            {if $with_reset_password==1}
            getScript("/assets/desktop.logged_in.min.js", function () {
                getScript("/assets/desktop.forms.min.js", function () {

                    $("form").on('submit', function (e) {

                        e.preventDefault();
                        e.returnValue = false;

                    });


                    $("#password_reset_form").validate({

                        submitHandler: function(form)
                        {


                            var button=$('#change_password_button');

                            if(button.hasClass('wait')){
                                return;
                            }

                            button.addClass('wait')
                            button.find('i').removeClass('fa-save').addClass('fa-spinner fa-spin')



                            var ajaxData = new FormData();

                            ajaxData.append("tipo", 'update_password')
                            ajaxData.append("pwd", sha256_digest($('#password').val()))


                            $.ajax({
                                url: "/ar_web_profile.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false,
                                complete: function () {
                                }, success: function (data) {



                                    if (data.state == '200') {
                                        $('#password_reset_form').addClass('submited')

                                    } else if (data.state == '400') {
                                        swal("{t}Error{/t}!", data.msg, "error")
                                    }

                                    button.removeClass('wait')
                                    button.find('i').addClass('fa-save').removeClass('fa-spinner fa-spin')

                                }, error: function () {
                                    button.removeClass('wait')
                                    button.find('i').addClass('fa-save').removeClass('fa-spinner fa-spin')
                                }
                            });


                        },

                        // Rules for form validation
                        rules:
                            {


                                password:
                                    {
                                        required: true,
                                        minlength: 8
                                    },
                                password_confirm:
                                    {
                                        required: true,
                                        minlength: 8,
                                        equalTo: "#password"
                                    }

                            },

                        // Messages for form validation
                        messages:
                            {

                                password:
                                    {
                                        required: '{if empty($labels._validation_required)}{t}Required field{/t}{else}{$labels._validation_required|escape}{/if}',
                                        minlength: '{if empty($labels._validation_minlength_password)}{t}Enter at least 8 characters{/t}{else}{$labels._validation_minlength_password|escape}{/if}',


                                    },
                                password_confirm:
                                    {
                                        required: '{if empty($labels._validation_required)}{t}Required field{/t}{else}{$labels._validation_required|escape}{/if}',
                                        equalTo: '{if empty($labels._validation_same_password)}{t}Enter the same password as above{/t}{else}{$labels._validation_same_password|escape}{/if}',

                                        minlength: '{if empty($labels._validation_minlength_password)}{t}Enter at least 8 characters{/t}{else}{$labels._validation_minlength_password|escape}{/if}',
                                    }
                            },

                        // Do not change code below
                        errorPlacement: function(error, element)
                        {
                            error.insertAfter(element.parent());
                        }
                    });





                })
            })

            {/if}
            {if $with_basket==1}
            getScript("/assets/desktop.logged_in.min.js", function () {
                getScript("/assets/desktop.forms.min.js", function () {
                    getScript("/assets/desktop.basket.min.js", function () {
                        $.getJSON("ar_web_basket.php?tipo=get_basket_html&device_prefix=", function (data) {

                        $('#basket').html(data.html)

                        $('.modal-opener').on('click', function()
                        {
                            if( !$('#sky-form-modal-overlay').length )
                            {
                                $('body').append('<div id="sky-form-modal-overlay" class="sky-form-modal-overlay"></div>');
                            }

                            $('#sky-form-modal-overlay').on('click', function()
                            {
                                $('#sky-form-modal-overlay').fadeOut();
                                $('.sky-form-modal').fadeOut();
                            });

                            form = $($(this).attr('href'));
                            $('#sky-form-modal-overlay').fadeIn();
                            form.css('top', '50%').css('left', '50%').css('margin-top', -form.outerHeight()/2).css('margin-left', -form.outerWidth()/2).fadeIn();

                            return false;
                        });

                        $('.modal-closer').on('click', function()
                        {
                            $('#sky-form-modal-overlay').fadeOut();
                            $('.sky-form-modal').fadeOut();

                            return false;
                        });

                      })
                    })
                })
            })
            {/if}
            {if $with_client_basket==1}
                            getScript("/assets/desktop.logged_in.min.js", function () {
                                getScript("/assets/desktop.forms.min.js", function () {
                                    getScript("/assets/desktop.client_basket.min.js", function () {
                                        $.getJSON("ar_web_client_basket.php?tipo=get_client_basket_html&client_key="+getUrlParameter('client_id')+"&device_prefix=", function (data) {
                                            $('#client_basket').html(data.html);
                                            $('.breadcrumbs .client_nav').html(data.client_nav.label)
                                            $('.breadcrumbs .client_nav').attr('title',data.client_nav.title)
                                            $('.breadcrumbs .order_nav').html(data.order_nav.label)
                                            $('.breadcrumbs .order_nav').attr('title',data.order_nav.title)
                                        })
                                    })
                                })
                            });
            {/if}
            {if $with_thanks==1}
                            getScript("/assets/desktop.logged_in.min.js", function () {



                var order_key=getUrlParameter('order_key');
                var timestamp=getUrlParameter('t');
                var timestamp_server=getUrlParameter('ts');

                if(timestamp==undefined){
                    timestamp='';
                }
                if(timestamp_server==undefined){
                    timestamp_server='';
                }

                if(order_key){
                    $.getJSON("ar_web_thanks.php?tipo=get_thanks_html&order_key="+order_key+"&device_prefix=&timestamp="+timestamp+"&timestamp_server="+timestamp_server, function (data) {
                        $('#thanks').html(data.html)
                    })
                }else{
                    $('#thanks').html('')
                }



            })

            {/if}
            {if $with_checkout==1}
            getScript("/assets/desktop.logged_in.min.js", function () {
                getScript("/assets/desktop.forms.min.js", function () {
                    getScript("/assets/desktop.checkout.min.js", function () {

                        let checkout_request="ar_web_checkout.php?tipo=get_checkout_html&device_prefix";
                        if(getUrlParameter('order_key')!=undefined){
                            checkout_request+='&client_order_key='+getUrlParameter('order_key')
                        }

                        $.getJSON(checkout_request, function (data) {

                            $('#bottom_header  .control_panel').html('<a id="go_back_basket hide"  href="'+data.basket_url+'" class="button"><i class="far fa-arrow-alt-left  " title="{t}Go back to basket{/t}" aria-hidden="true"></i>\n' + '<span>{if empty($labels._go_back_to_basket)}{t}Go back to basket{/t}{else}{$labels._go_back_to_basket}{/if}</span></a>')

                            $('#checkout').html(data.html)

                            $("form").on('submit',function(e) {
                                e.preventDefault();
                                e.returnValue = false;
                            });


                            function jQueryTabs3() {
                                $(".tabs3").each(function () {
                                    $(".tabs-panel3").not(":first").hide(), $("li", this).removeClass("active"), $("li:first-child", this).addClass("active"), $(".tabs-panel:first-child").show(), $("li", this).on( 'click',function (t){
                                        var i=$("a",this).attr("href");
                                        $(this).siblings().removeClass("active"),$(this).addClass("active"),$(i).siblings().hide(),$(i).fadeIn(400),t.preventDefault()}), $(window).width() < 100 && $(".tabs-panel3").show()
                                })
                            }
                            jQueryTabs3();
                            $(".tabs3 li a").each(function(){
                                var t=$(this).attr("href"),i=$(this).html();$(t+" .tab-title3").prepend("<p><strong>"+i+"</strong></p>")})
                            $(window).resize(function (){
                                    jQueryTabs3()

                                })



                            })
                    })
                })
            })


            {/if}
            {if $with_profile==1}
            getScript("/assets/desktop.forms.min.js", function () {
                getScript("/assets/desktop.profile.min.js", function () {
                $.getJSON("ar_web_profile.php?tipo=get_profile_html&device_prefix=", function (data) {
                    $('#profile').html(data.html)
                })
                })
            })

            {/if}
            {if $with_client==1}
                            getScript("/assets/desktop.forms.min.js", function () {
                      $.getJSON("ar_web_client.php?tipo=get_client_html&id="+getUrlParameter('id')+"&device_prefix=", function (data) {
                          $('#client').html(data.html)
                          $('.breadcrumbs .client_nav').html(data.client_nav.label)
                          $('.breadcrumbs .client_nav').attr('title',data.client_nav.title)


                          $(document).on('click', '#new_order', function (e) {
                              window.location = '/client_basket.sys?client_id='+getUrlParameter('id');
                          });


                          getScript("/assets/datatables.min.js", function () {

                              const request_data ={ "tipo":'client_orders','client_id':getUrlParameter('id')}

                              $.ajax({
                                  url: '/ar_web_tables.php', type: 'GET', dataType: 'json', data: request_data, success: function (data) {

                                      if (data.state == 200) {
                                          state = data.app_state;
                                          $('#table_container').html(data.html)





                                      }



                                  }
                              });

                          })


                      })
                   })
             {/if}


            {if $with_register==1}
            $('#register_header_button').addClass('hide')
            getScript("/assets/desktop.forms.min.js", function () {

                $("#country_select").change(function () {

                    var selected = $("#country_select option:selected")
                    // console.log(selected.val())

                    var request = "ar_web_addressing.php?tipo=address_format&country_code=" + selected.val() + '&website_key={$website->id}'

                    console.log(request)
                    $.getJSON(request, function (data) {
                        console.log(data)
                        $.each(data.hidden_fields, function (index, value) {
                            $('#' + value).addClass('hide')
                            $('#' + value).find('input').addClass('ignore')

                        });

                        $.each(data.used_fields, function (index, value) {
                            $('#' + value).removeClass('hide')
                            $('#' + value).find('input').removeClass('ignore')

                        });

                        $.each(data.labels, function (index, value) {
                            $('#' + index).find('input').attr('placeholder', value)
                            $('#' + index).find('b').html(value)
                            $('#' + index).find('label.label').html(value)

                        });

                        $.each(data.no_required_fields, function (index, value) {


                            // console.log(value)

                            $('#' + value + ' input').rules("remove");


                        });

                        $.each(data.required_fields, function (index, value) {
                            console.log($('#' + value))
                            //console.log($('#'+value+' input').rules())

                            $('#' + value + ' input').rules("add", {
                                required: true});

                        });


                    });


                });


                $("form").on('submit', function (e) {

                    e.preventDefault();
                    e.returnValue = false;

                });


                $("#registration_form").validate({

                    submitHandler: function (form) {


                        if ($('#register_button').hasClass('wait')) {
                            return;
                        }

                        $('#register_button').addClass('wait');
                        $('#register_button i').removeClass('fa-arrow-right').addClass('fa-spinner fa-spin');

                        let register_data = { }

                        $("#registration_form input:not(.ignore)").each(function (i, obj) {
                            if (!$(obj).attr('name') == '') {
                                register_data[$(obj).attr('name')] = $(obj).val()
                            }

                        });

                        $("#registration_form textarea:not(.ignore)").each(function (i, obj) {
                            if (!$(obj).attr('name') == '') {
                                register_data[$(obj).attr('name')] = $(obj).val()
                            }

                        });

                        $("#registration_form select:not(.ignore)").each(function (i, obj) {
                            if (!$(obj).attr('name') == '') {


                                register_data[$(obj).attr('name')] = $(obj).val()
                            }

                        });

                        {if $website->settings('captcha_client')}
                            register_data['captcha']= grecaptcha.getResponse();
                        {/if}


                        register_data['new-password'] = sha256_digest(register_data['new-password']);

                        var ajaxData = new FormData();

                        ajaxData.append("tipo", 'register')
                        ajaxData.append("store_key", '{$store->id}')
                        ajaxData.append("data", JSON.stringify(register_data))





                        $.ajax({
                            url: "/ar_web_register.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false, complete: function () {
                            }, success: function (data) {


                                if (data.state == '200') {

                                    ga('auTracker.send', 'event', 'Register', 'register');

                                    window.location.replace("welcome.sys");


                                } else if (data.state == '400') {
                                    swal("{t}Error{/t}!", data.msg, "error")
                                    $('#register_button').removeClass('wait')
                                    $('#register_button i').addClass('fa-arrow-right').removeClass('fa-spinner fa-spin')
                                }


                            }, error: function () {


                                $('#register_button').removeClass('wait')
                                $('#register_button i').addClass('fa-arrow-right').removeClass('fa-spinner fa-spin')


                            }
                        });


                    },

                    // Rules for form validation
                    rules: {

                        email: {
                            required: true, email: true, remote: {
                                url: "ar_web_validate.php", data: {
                                    tipo: 'validate_email_registered', website_key: '{$website->id}'
                                }
                            }

                        }, 'new-password': {
                            required: true, minlength: 8


                        }, password_confirm: {
                            required: true, minlength: 8, equalTo: "#register_password"
                        }, name: {
                            required: true,

                        }, tel: {
                            required: true,

                        }, terms: {
                            required: true,
                        },

                {foreach from=$required_fields item=required_field }
                {$required_field}:
                {
                    required: true
                }
            ,
                {/foreach}

                {foreach from=$no_required_fields item=no_required_field }
                {$no_required_field}:
                {
                    required: false
                }
            ,
                {/foreach}

            },


                messages:
                {

                    email:
                    {

                        required: '{if empty($labels._validation_required)}{t}Required field{/t}{else}{$labels._validation_required|escape}{/if}', email
                    :
                        '{if empty($labels._validation_email_invalid)}{t}Invalid email{/t}{else}{$labels._validation_email_invalid|escape}{/if}', remote
                    :
                        '{if empty($labels._validation_handle_registered)}{t}Email address is already in registered{/t}{else}{$labels._validation_handle_registered|escape}{/if}',


                    }
                ,
                    'new-password':
                    {
                        required: '{if empty($labels._validation_required)}{t}Required field{/t}{else}{$labels._validation_required|escape}{/if}', minlength
                    :
                        '{if empty($labels._validation_minlength_password)}{t}Enter at least 8 characters{/t}{else}{$labels._validation_minlength_password|escape}{/if}',


                    }
                ,
                    password_confirm:
                    {
                        required: '{if empty($labels._validation_required)}{t}Required field{/t}{else}{$labels._validation_required|escape}{/if}', equalTo
                    :
                        '{if empty($labels._validation_same_password)}{t}Enter the same password as above{/t}{else}{$labels._validation_same_password|escape}{/if}',

                            minlength
                    :
                        '{if empty($labels._validation_minlength_password)}{t}Enter at least 8 characters{/t}{else}{$labels._validation_minlength_password|escape}{/if}',
                    }
                ,
                    name:
                    {
                        required: '{if empty($labels._validation_required)}{t}Required field{/t}{else}{$labels._validation_required|escape}{/if}',
                    }
                ,
                    tel:
                    {
                        required: '{if empty($labels._validation_required)}{t}Required field{/t}{else}{$labels._validation_required|escape}{/if}',
                    }
                ,
                    terms:
                    {
                        required: '{if empty($labels._validation_accept_terms)}{t}Please accept our terms and conditions to proceed{/t}{else}{$labels._validation_accept_terms|escape}{/if}',


                    }
                ,
                    administrativeArea:
                    {
                        required: '{if empty($labels._validation_required)}{t}Required field{/t}{else}{$labels._validation_required|escape}{/if}',
                    }
                ,
                    locality:
                    {
                        required: '{if empty($labels._validation_required)}{t}Required field{/t}{else}{$labels._validation_required|escape}{/if}',
                    }
                ,
                    dependentLocality:
                    {
                        required: '{if empty($labels._validation_required)}{t}Required field{/t}{else}{$labels._validation_required|escape}{/if}',
                    }
                ,
                    postalCode:
                    {
                        required: '{if empty($labels._validation_required)}{t}Required field{/t}{else}{$labels._validation_required|escape}{/if}',
                    }
                ,
                    addressLine1:
                    {
                        required: '{if empty($labels._validation_required)}{t}Required field{/t}{else}{$labels._validation_required|escape}{/if}',
                    }
                ,
                    addressLine2:
                    {
                        required: '{if empty($labels._validation_required)}{t}Required field{/t}{else}{$labels._validation_required|escape}{/if}',
                    }
                ,
                    sortingCode:
                    {
                        required: '{if empty($labels._validation_required)}{t}Required field{/t}{else}{$labels._validation_required|escape}{/if}',
                    }


                },


                errorPlacement: function (error, element) {
                    error.insertAfter(element.parent());
                }
            })

            })

            {/if}

             {if $with_portfolio==1}
                            getScript("/assets/datatables.min.js", function () {

                                const request_data ={ "tipo":'portfolio'}
                                $.ajax({

                                    url: '/ar_web_tables.php', type: 'GET', dataType: 'json', data: request_data, success: function (data) {
                                        if (data.state == 200) {

                                            state = data.app_state;

                                            $('#table_container').html(data.html)
                                        }

                                    }
                                });



                        })
                            {/if}
             {if $with_clients==1}
             getScript("/assets/desktop.forms.min.js", function () {
                 getScript("/assets/datatables.min.js", function () {

                     const request_data ={ "tipo":'clients'}

                     $.ajax({
                         url: '/ar_web_tables.php', type: 'GET', dataType: 'json', data: request_data, success: function (data) {

                             if (data.state == 200) {
                                 state = data.app_state;
                                 $('#table_container').html(data.html)
                             }

                         }
                     });

                 })

                 $(document).on('click', '#new_customer', function (e) {
                    $( ".reg_form" ).removeClass( "hide" );
                     $( ".clients" ).addClass( "hide" );
                });

                 $("form").submit(function(e) {
                     e.preventDefault();
                     e.returnValue = false;
                 });

                 $("#new_client_form").validate(
                     {
                         submitHandler: function(form)
                         {

                             var button=$('#save_new_client_button');

                             if(button.hasClass('wait')){
                                 return;
                             }

                             button.addClass('wait')
                             button.find('i').removeClass('fa-arrow-right').addClass('fa-spinner fa-spin')

                             var register_data={ }

                             $("#new_client_form input:not(.ignore)").each(function(i, obj) {
                                 if(!$(obj).attr('name')==''){
                                     register_data[$(obj).attr('name')]=$(obj).val()
                                 }
                             });

                             $("#new_client_form select:not(.ignore)").each(function(i, obj) {
                                 if(!$(obj).attr('name')==''){
                                     register_data[$(obj).attr('name')]=$(obj).val()
                                 }
                             });



                             var ajaxData = new FormData();

                             ajaxData.append("tipo", 'new_customer_client')
                             ajaxData.append("data", JSON.stringify(register_data))


                             $.ajax({
                                 url: "/ar_web_clients.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false,
                                 complete: function () {
                                 }, success: function (data) {




                                     if (data.state == '200') {

                                         rows.fetch({
                                             reset: true
                                         });

                                         $( ".reg_form" ).addClass( "hide" );
                                         $( ".clients" ).removeClass( "hide" );

                                         for (var key in data.metadata.class_html) {
                                             $('.' + key).html(data.metadata.class_html[key])
                                         }

                                     } else if (data.state == '400') {
                                         swal("{t}Error{/t}!", data.msg, "error")
                                     }


                                     button.removeClass('wait')
                                     button.find('i').addClass('fa-arrow-right').removeClass('fa-spinner fa-spin')
                                 }, error: function () {
                                     button.removeClass('wait')
                                     button.find('i').addClass('fa-arrow-right').removeClass('fa-spinner fa-spin')
                                 }
                             });


                         },

                         rules:
                             {
                                {foreach from=$required_fields item=required_field }
                 {$required_field}: { required: true },
                 {/foreach}
                                {foreach from=$no_required_fields item=no_required_field }
                 {$no_required_field}:{   required: false},
                 {/foreach}

                            },
                        messages:
                            {


                     administrativeArea:
                     {
                         required: '{if empty($labels._validation_required)}{t}Required field{/t}{else}{$labels._validation_required|escape}{/if}',
                     },
                     locality:
                     {
                         required: '{if empty($labels._validation_required)}{t}Required field{/t}{else}{$labels._validation_required|escape}{/if}',
                     },
                     dependentLocality:
                     {
                         required: '{if empty($labels._validation_required)}{t}Required field{/t}{else}{$labels._validation_required|escape}{/if}',
                     },
                     postalCode:
                     {
                         required: '{if empty($labels._validation_required)}{t}Required field{/t}{else}{$labels._validation_required|escape}{/if}',
                     },
                     addressLine1:
                     {
                         required: '{if empty($labels._validation_required)}{t}Required field{/t}{else}{$labels._validation_required|escape}{/if}',
                     },
                     addressLine2:
                     {
                         required: '{if empty($labels._validation_required)}{t}Required field{/t}{else}{$labels._validation_required|escape}{/if}',
                     },
                     sortingCode:
                     {
                         required: '{if empty($labels._validation_required)}{t}Required field{/t}{else}{$labels._validation_required|escape}{/if}',
                     }




                 },
                        errorPlacement: function(error, element)
                            {
                     error.insertAfter(element.parent());
                 }
                        });






             })

                            {/if}
             {if $with_client_order_new==1}
                            getScript("/assets/desktop.forms.min.js", function () {
                                getScript("/assets/datatables.min.js", function () {

                                    const request_data ={ "tipo":'choose_client_for_order'}

                                    $.ajax({
                                        url: '/ar_web_tables.php', type: 'GET', dataType: 'json', data: request_data, success: function (data) {

                                            if (data.state == 200) {
                                                state = data.app_state;
                                                $('#table_container').html(data.html)
                                            }

                                        }
                                    });

                                })



                                $(document).on('click', '#order_for_new_customer', function (e) {
                                    $(this).closest('.sky-form').addClass( "hide" );
                                    $( ".reg_form" ).removeClass( "hide" );
                                });

                                $("form").submit(function(e) {
                                    e.preventDefault();
                                    e.returnValue = false;
                                });

                                $("#order_for_new_customer_form").validate(
                                    {
                                        submitHandler: function(form)
                                        {

                                            var button=$('#save_order_for_new_customer_button');

                                            if(button.hasClass('wait')){
                                                return;
                                            }

                                            button.addClass('wait')
                                            button.find('i').removeClass('fa-arrow-right').addClass('fa-spinner fa-spin')

                                            var register_data={ }

                                            $("#order_for_new_customer_form input:not(.ignore)").each(function(i, obj) {
                                                if(!$(obj).attr('name')==''){
                                                    register_data[$(obj).attr('name')]=$(obj).val()
                                                }
                                            });

                                            $("#order_for_new_customer_form select:not(.ignore)").each(function(i, obj) {
                                                if(!$(obj).attr('name')==''){
                                                    register_data[$(obj).attr('name')]=$(obj).val()
                                                }
                                            });



                                            var ajaxData = new FormData();

                                            ajaxData.append("tipo", 'order_for_new_customer')
                                            ajaxData.append("data", JSON.stringify(register_data))


                                            $.ajax({
                                                url: "/ar_web_clients.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false,
                                                complete: function () {
                                                }, success: function (data) {




                                                    if (data.state == '200') {
                                                        window.location = 'client_basket.sys?client_id='+data.client_id


                                                    } else if (data.state == '400') {
                                                        swal("{t}Error{/t}!", data.msg, "error")
                                                        button.removeClass('wait')
                                                        button.find('i').addClass('fa-arrow-right').removeClass('fa-spinner fa-spin')
                                                    }



                                                }, error: function () {
                                                    button.removeClass('wait')
                                                    button.find('i').addClass('fa-arrow-right').removeClass('fa-spinner fa-spin')
                                                }
                                            });


                                        },

                                        rules:
                                            {
                                {foreach from=$required_fields item=required_field }
                                {$required_field}: { required: true },
                                {/foreach}
                                {foreach from=$no_required_fields item=no_required_field }
                                {$no_required_field}:{   required: false},
                                {/foreach}

                            },
                                messages:
                                {


                                    administrativeArea:
                                    {
                                        required: '{if empty($labels._validation_required)}{t}Required field{/t}{else}{$labels._validation_required|escape}{/if}',
                                    },
                                    locality:
                                    {
                                        required: '{if empty($labels._validation_required)}{t}Required field{/t}{else}{$labels._validation_required|escape}{/if}',
                                    },
                                    dependentLocality:
                                    {
                                        required: '{if empty($labels._validation_required)}{t}Required field{/t}{else}{$labels._validation_required|escape}{/if}',
                                    },
                                    postalCode:
                                    {
                                        required: '{if empty($labels._validation_required)}{t}Required field{/t}{else}{$labels._validation_required|escape}{/if}',
                                    },
                                    addressLine1:
                                    {
                                        required: '{if empty($labels._validation_required)}{t}Required field{/t}{else}{$labels._validation_required|escape}{/if}',
                                    },
                                    addressLine2:
                                    {
                                        required: '{if empty($labels._validation_required)}{t}Required field{/t}{else}{$labels._validation_required|escape}{/if}',
                                    },
                                    sortingCode:
                                    {
                                        required: '{if empty($labels._validation_required)}{t}Required field{/t}{else}{$labels._validation_required|escape}{/if}',
                                    }




                                },
                                errorPlacement: function(error, element)
                                {
                                    error.insertAfter(element.parent());
                                }
                            });






                            })

                            {/if}
             {if $with_clients_orders==1}


                            $(document).on('click', '#client_order_new', function (e) {
                                window.location = '/client_order_new.sys';
                            });


                            getScript("/assets/datatables.min.js", function () {




                                const request_data ={ "tipo":'clients_orders'}

                                $.ajax({
                                    url: '/ar_web_tables.php', type: 'GET', dataType: 'json', data: request_data, success: function (data) {

                                        if (data.state == 200) {
                                            state = data.app_state;
                                            $('#table_container').html(data.html)
                                        }

                                    }
                                });

                            })
                            {/if}




            {if $with_login==1}
            $('.control_panel').addClass('hide')
            getScript("/assets/desktop.forms.min.js", function () {
                $('#open_recovery').on('click', function (e) {
                    open_recovery()
                });

                function open_recovery() {
                    $('#login_form_container').addClass('hide')
                    $('#recovery_form_container').removeClass('hide')
                    $('#recovery_email').val($('#handle').val())

                }

                $('#password_recovery_go_back').on('click', function (e) {

                    e.preventDefault();
                    $("#password_recovery_form").removeClass('submited')


                });


                $('#close_recovery').on('click', function (e) {

                    $('#login_form_container').removeClass('hide')
                    $('#recovery_form_container').addClass('hide')

                });


                $("#login_form").validate({

                    submitHandler: function (form) {


                        var button = $('#login_button');

                        if (button.hasClass('wait')) {
                            return;
                        }

                        button.addClass('wait')
                        button.find('i').removeClass('fa-arrow-right').addClass('fa-spinner fa-spin')


                        var ajaxData = new FormData();

                        ajaxData.append("tipo", 'login')
                        ajaxData.append("website_key", '{$website->id}')
                        ajaxData.append("handle", $('#handle').val())
                        ajaxData.append("pwd", sha256_digest($('#pwd').val()))
                        ajaxData.append("keep_logged", $('#keep_logged').is(':checked'))

                        $.ajax({
                            url: "/ar_web_login.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false, complete: function () {
                            }, success: function (data) {


                                console.log(data)
                                if (data.state == '200') {




                                    ga('auTracker.send', 'event', 'Login', 'login');


                                {if isset($redirect_after_login)}
                                    window.location.replace('{$redirect_after_login}');

                                    {else}


                                   if(document.referrer.indexOf(location.protocol + "//" + location.host) === 0){
                                        //console.log(document.referrer)


                                        if(document.referrer.match(/login\.sys/g)){

                                            window.location.replace("index.php");
                                        }else if(document.referrer.match(/register\.sys/g)){
                                            window.location.replace("index.php");
                                        }else{
                                            window.location.replace(document.referrer);
                                        }



                                    }else{
                                        window.location.replace("index.php");
                                    }
                                    {/if}



                                } else if (data.state == '400') {
                                    swal("{t}Error{/t}!", data.msg, "error")
                                    button.removeClass('wait')
                                    button.find('i').addClass('fa-arrow-right').removeClass('fa-spinner fa-spin')
                                }




                            }, error: function () {
                                button.removeClass('wait')
                                button.find('i').addClass('fa-arrow-right').removeClass('fa-spinner fa-spin f')

                            }
                        });


                    },

                    // Rules for form validation
                    rules: {

                        email: {
                            required: true, email: true,


                        }, password: {
                            required: true,


                        }


                    },

                    // Messages for form validation
                    messages: {

                        email: {
                            required: '{if empty($labels._validation_handle_missing)}{t}Please enter your registered email address{/t}{else}{$labels._validation_handle_missing|escape}{/if}',
                            email: '{if empty($labels._validation_email_invalid)}{t}Please enter a valid email address{/t}{else}{$labels._validation_email_invalid|escape}{/if}',
                        }, password: {
                            required: '{if empty($labels._validation_password_missing)}{t}Please enter your password{/t}{else}{$labels._validation_password_missing|escape}{/if}',

                        }


                    },

                    // Do not change code below
                    errorPlacement: function (error, element) {
                        error.insertAfter(element.parent());
                    }
                });

                $("#password_recovery_form").validate({

                    submitHandler: function (form) {


                        if ($('#recovery_button').hasClass('wait')) {
                            return;
                        }

                        $('#recovery_button').addClass('wait')
                        $('#recovery_button i').removeClass('fa-arrow-right').addClass('fa-spinner fa-spin')


                        var ajaxData = new FormData();

                        ajaxData.append("tipo", 'recover_password')
                        ajaxData.append("website_key", '{$website->id}')
                        ajaxData.append("webpage_key", '{$webpage->id}')

                        ajaxData.append("recovery_email", $('#recovery_email').val())


                        $.ajax({
                            url: "/ar_web_recover_password.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false, complete: function () {
                            }, success: function (data) {


                                $("#password_recovery_form").addClass('submited')

                                if (data.state == '200') {


                                    $('.password_recovery_msg').addClass('hide')
                                    $('#password_recovery_success_msg').removeClass('hide').prev('i').addClass('fa-check').removeClass('error fa-exclamation')
                                    $('#password_recovery_form').find('.message').removeClass('error')
                                    $('#password_recovery_go_back').addClass('hide')

                                } else if (data.state == '400') {

                                    console.log('#password_recovery_' + data.error_code + '_error_msg')

                                    $('.password_recovery_msg').addClass('hide').prev('i').removeClass('fa-check').addClass('error fa-exclamation')
                                    $('#password_recovery_' + data.error_code + '_error_msg').removeClass('hide')
                                    $('#password_recovery_form').find('.message').addClass('error')
                                    $('#password_recovery_go_back').removeClass('hide')
                                }

                                $('#recovery_button').removeClass('wait')
                                $('#recovery_button i').addClass('fa-arrow-right').removeClass('fa-spinner fa-spin')


                            }, error: function () {

                                $('#recovery_button').removeClass('wait')
                                $('#recovery_button i').addClass('fa-arrow-right').removeClass('fa-spinner fa-spin')

                            }
                        });


                    },

                    // Rules for form validation
                    rules: {

                        email: {
                            required: true, email: true,


                        }


                    },

                    // Messages for form validation
                    messages: {

                        email: {
                            required: '{if empty($labels._validation_handle_missing)}{t}Please enter your registered email address{/t}{else}{$labels._validation_handle_missing}{/if}',
                            email: '{if empty($labels._validation_email_invalid)}{t}Please enter a valid email address{/t}{else}{$labels._validation_email_invalid}{/if}',
                        }


                    },

                    // Do not change code below
                    errorPlacement: function (error, element) {
                        error.insertAfter(element.parent());
                    }
                });

            })
            {/if}
            {if $with_gallery==1}


            getScript("/assets/image_gallery.min.js", function () {
                var $pswp = $('.pswp')[0];

                var items = [];

                $('.images figure').each(function () {

                    var link = $(this).find('a')
                    items.push({
                        src: link.attr('href'), w: link.data('w'), h: link.data('h')
                    });

                })

                $('.images figure a').on( 'click',function (event) {
                    event.preventDefault();
                    var options = {
                        index: $(this).index()
                    }
                    var lightBox = new PhotoSwipe($pswp, PhotoSwipeUI_Default, items, options);
                    lightBox.init();
                });


            })
            {/if}
            {if $with_iframe==1}

            $(document).ready(function () {
                resize_banners();
            });

            $(window).resize(function () {
                resize_banners();

            });

            function resize_banners() {


                $('.iframe').each(function (i, obj) {
                    $(this).css({
                        height: $(this).width() * $(this).attr('h') / $(this).attr('w')
                    })
                });
            }
            {/if}

             {if $logged_in}
             {if $store->get('Store Type')=='Dropshipping'}
                    {if $with_products_portfolio==1}
                            $.getJSON("ar_web_portfolio.php?tipo=category_products&with_category_products={if $with_category_products==1}Yes{else}No{/if}&webpage_key={$webpage->id}", function (data) {
                                $.each(data.products_in_portfolio, function (index, value) {
                                    let portfolio_row=$('.portfolio_row_' + index);
                                    portfolio_row.find('.add_to_portfolio').addClass('hide')
                                    portfolio_row.find('.remove_from_portfolio').removeClass('hide')
                                });
                                $.each(data.stock, function (index, value) {
                                    if (value[0] != '') {
                                        $('.stock_level_' + index).removeClass('Excess Normal Low VeryLow OutofStock Error OnDemand').addClass(value[0]).attr('title', value[1])
                                        $('.product_stock_label_' + index).html(value[1])
                                    }
                                });
                            });

                    {/if}
                    getScript("/assets/desktop.logged_in.min.js", function () {
                        $('#logout i').removeClass('fa-spinner fa-spin').addClass('fa-sign-out')
                    })
             {else}
                    {if $with_product_order_input==1}



                $.getJSON("ar_web_customer_products.php?&tipo=category_products&with_category_products={if $with_category_products==1}Yes{else}No{/if}&webpage_key={$webpage->id}", function (data) {
                $('.order_row i').removeClass('hide')
                $('.order_row span').removeClass('hide')

                $.each(data.ordered_products, function (index, value) {
                    $('.order_row_' + index).find('input').val(value).data('ovalue', value)
                    $('.order_row_' + index).find('i').removeClass('fa-hand-pointer fa-flip-horizontal').addClass('fa-thumbs-up fa-flip-horizontal')
                    $('.order_row_' + index).find('.label span').html('{if empty($labels._ordering_ordered)}{t}Ordered{/t}{else}{$labels._ordering_ordered}{/if}')
                });

                $.each(data.favourite, function (index, value) {
                    $('.favourite_' + index).removeClass('far').addClass('marked fas').data('favourite_key', value)
                });

                $.each(data.out_of_stock_reminders, function (index, value) {
                    var reminder_icon = $('.out_of_stock_reminders_' + index)
                    reminder_icon.removeClass('far').addClass('fas').data('out_of_stock_reminder_key', value).attr('title', reminder_icon.data('label_remove_notification'))
                });

                $.each(data.stock, function (index, value) {
                    if (value[0] != '') {
                        $('.stock_level_' + index).removeClass('Excess Normal Low VeryLow OutofStock Error OnDemand').addClass(value[0]).attr('title', value[1])
                        $('.product_stock_label_' + index).html(value[1])
                    }
                });

                $('#header_order_totals').find('.ordered_products_number').html(data.items)
                $('#header_order_totals').find('.order_amount').html(data.total)
                $('#header_order_totals').find('i').attr('title', data.label)
                });
                {else}
                $.getJSON("ar_web_customer_products.php?tipo=total_basket", function (data) {
                    $('#header_order_totals').find('.ordered_products_number').html(data.items)
                    $('#header_order_totals').find('.order_amount').html(data.total)
                    $('#header_order_totals').find('i').attr('title', data.label)

                });
                {/if}
                     getScript("/assets/desktop.logged_in.min.js", function () {
                        $('#logout i').removeClass('fa-spinner fa-spin').addClass('fa-sign-out')
                        {if $with_favourites==1}


                    $.getJSON("ar_web_favourites.php?tipo=get_favourites_html&device_prefix=", function (data) {


                        $('#favourites').html(data.html)

                        $.getJSON("ar_web_customer_products.php?tipo=category_products&with_favourites_products=Yes&webpage_key={$webpage->id}", function (data) {



                            $('.order_row i').removeClass('hide')
                            $('.order_row span').removeClass('hide')

                            $.each(data.ordered_products, function (index, value) {
                                $('.order_row_' + index).find('input').val(value).data('ovalue', value)
                                $('.order_row_' + index).find('i').removeClass('fa-hand-pointer fa-flip-horizontal').addClass('fa-thumbs-up fa-flip-horizontal')
                                $('.order_row_' + index).find('.label span').html('{if empty($labels._ordering_ordered)}{t}Ordered{/t}{else}{$labels._ordering_ordered}{/if}')
                            });


                            $.each(data.favourite, function (index, value) {
                                $('.favourite_' + index).removeClass('far').addClass('marked fas').data('favourite_key', value)
                            });

                            $.each(data.out_of_stock_reminders, function (index, value) {

                                var reminder_icon=$('.out_of_stock_reminders_' + index)
                                reminder_icon.removeClass('far').addClass('fas').data('out_of_stock_reminder_key', value).attr('title', reminder_icon.data('label_remove_notification'))
                            });

                            $.each(data.stock, function (index, value) {
                                if(value[0]!=''){
                                    $('.stock_level_' + index).removeClass('Excess Normal Low VeryLow OutofStock Error OnDemand').addClass(value[0]).attr('title',value[1])

                                    $('.product_stock_label_'+ index).html(value[1])

                                }

                            });

                            $('#header_order_totals').find('.ordered_products_number').html(data.items)
                            $('#header_order_totals').find('.order_amount').html(data.total)
                            $('#header_order_totals').find('i').attr('title',data.label)
                            ga('auTracker.send', 'pageview');

                        });



                    })



                {/if}
                    })
              {/if}
              {/if}






        });
    })();


    {if $with_search!=1 and $with_favourites!=1 and $with_basket!=1 and $with_checkout!=1 and $with_thanks!=1}
    ga('auTracker.send', 'pageview');
    {/if}

    {if $with_portfolio==1}
    function fixedEncodeURIComponent(str) {
        return encodeURIComponent(str).replace(/[!'()]/g, escape).replace(/\*/g, "%2A");
    }

    {/if}

</script>

{if $with_gallery==1}
    <div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="pswp__bg"></div>
        <div class="pswp__scroll-wrap">

            <div class="pswp__container">
                <div class="pswp__item"></div>
                <div class="pswp__item"></div>
                <div class="pswp__item"></div>
            </div>

            <div class="pswp__ui pswp__ui--hidden">
                <div class="pswp__top-bar">
                    <div class="pswp__counter"></div>
                    <button class="pswp__button pswp__button--close" title="{t}Close{/t} (Esc)"></button>
                    <button class="pswp__button pswp__button--share" title="{t}Share{/t}"></button>
                    <button class="pswp__button pswp__button--fs" title="{t}Toggle fullscreen{/t}"></button>
                    <button class="pswp__button pswp__button--zoom" title="{t}Zoom in/out{/t}"></button>
                    <div class="pswp__preloader">
                        <div class="pswp__preloader__icn">
                            <div class="pswp__preloader__cut">
                                <div class="pswp__preloader__donut"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
                    <div class="pswp__share-tooltip"></div>
                </div>
                <button class="pswp__button pswp__button--arrow--left" title="{t}Previous{/t} (arrow left)"></button>
                <button class="pswp__button pswp__button--arrow--right" title="{t}Next{/t} (arrow right)"></button>
                <div class="pswp__caption">
                    <div class="pswp__caption__center"></div>
                </div>
            </div>
        </div>
    </div>
{/if}
{if !$is_devel and !empty($tawk_chat_code)}
    <script type="text/javascript">
        var Tawk_API=Tawk_API||{

        }, Tawk_LoadStart=new Date();
        (function(){
            var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
            s1.async=true;
            s1.src='https://embed.tawk.to/{$tawk_chat_code}/default';
            s1.charset='UTF-8';
            s1.setAttribute('crossorigin','*');
            s0.parentNode.insertBefore(s1,s0);
        })();
    </script>
{/if}
{/if}



{if $with_client==1 or $with_thanks==1 or $with_client_basket}
<script>
    var getUrlParameter = function getUrlParameter(sParam) {
    var sPageURL = window.location.search.substring(1),
    sURLVariables = sPageURL.split('&'),
    sParameterName,
    i;

    for (i = 0; i < sURLVariables.length; i++) {
    sParameterName = sURLVariables[i].split('=');

    if (sParameterName[0] === sParam) {
    return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
    }
    }
    };
</script>
{/if}

{if !empty($firebase)}
    <script src="https://www.gstatic.com/firebasejs/7.7.0/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/7.7.0/firebase-analytics.js"></script>
    <script>
        var firebaseConfig = {
            apiKey: "{$firebase.apiKey}",
            authDomain: "{$firebase.projectId}.firebaseapp.com",
            databaseURL: "https://{$firebase.projectId}.firebaseio.com",
            projectId: "{$firebase.projectId}",
            storageBucket: "{$firebase.projectId}.appspot.com",
            messagingSenderId: "{$firebase.messagingSenderId}",
            appId: "{$firebase.appId}",
            measurementId:  "{$firebase.measurementId}"
        };
        firebase.initializeApp(firebaseConfig);
        firebase.analytics();

    </script>
{/if}
</body></html>
﻿{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 12 March 2018 at 15:36:32 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}{include file="theme_1/_head.theme_1.EcomB2B.tablet.tpl"}
<body>{include file="analytics.tpl"}
{if $logged_in}
    <span id="ordering_settings" class="hide"  data-website_key="{$website->id}" ></span>
{/if}
<div id="page-transitions">
    {include file="theme_1/header.theme_1.EcomB2B.tablet.tpl"}
    <div id="page-content" class="page-content">
        <div id="page-content-scroll" class="header-clear"><!--Enables this element to be scrolled -->
            {if $navigation.show}
            <div class="menu-bar" style="margin:0px;height:50px;position: relative;top:-5px;border-bottom:1px solid #ccc">
                <em class="menu-bar-text-1   ">
                    {foreach from=$navigation.breadcrumbs item=$breadcrumb name=breadcrumbs}
                        <span class="breadcrumbs"><a href="{$breadcrumb.link}"style="color:#1f2f1f" title="{$breadcrumb.title}">{$breadcrumb.label}</a> {if !$smarty.foreach.breadcrumbs.last}<i class="fas padding_left_10 padding_right_10 fa-angle-double-right"></i>{/if}</span>
                    {/foreach}
                </em>
                <em class="menu-bar-text-2   " >
                    {if $navigation.prev}<a style="color:#1f2f1f" href="{$navigation.prev.link}" title="{$navigation.prev.title}"><i class="fas fa-arrow-left"></i></a>{/if} {if $navigation.next}<a style="color:#1f2f1f" href="{$navigation.next.link}" title="{$navigation.next.title}"><i style="margin-left:20px" class="fas fa-arrow-right next"></i></a>{/if}
                </em>
                <div class="menu-bar-title" style="position: relative;"></div>
            </div>
            {/if}
            {if isset($discounts) and count($discounts.deals)>0 }
                <div class="discounts" style="">
                    {foreach from=$discounts.deals item=deal_data }
                    <div class="discount_card" key="{$deal_data.key}" >
                        <div class="discount_icon" style="">{$deal_data.icon}</div>
                        <span  class="discount_name">{$deal_data.name}</span>
                        {if  $deal_data.until!=''}<small class="padding_left_10"><span id="_offer_valid_until" class="website_localized_label" >{if !empty($labels._offer_valid_until)}{$labels._offer_valid_until}{else}{t}Valid until{/t}{/if}</span>: {$deal_data.until_formatted}{/if}</small>

                        <br/>
                        <span   class="discount_term">{$deal_data.term}</span>
                        <span   class="discount_allowance">{$deal_data.allowance}</span>
                    </div>
                    {/foreach}<div style="clear:both"></div>
                </div>
            {/if}

            {assign "with_iframe" false}
            {assign "with_login" false}
            {assign "with_register" false}
            {assign "with_basket" false}
            {assign "with_checkout" false}
            {assign "with_profile" false}
            {assign "with_favourites" false}
            {assign "with_search" false}
            {assign "with_thanks" false}
            {assign "with_gallery" false}
            {assign "with_reset_password" false}
            {assign "with_product_order_input" false}
            {assign "with_product" false}
            {assign "with_unsubscribe" false}


            {foreach from=$content.blocks item=$block key=key}
                    {if $block.show}



                     {if $block.type=='basket'}
                            {if $logged_in}{assign "with_basket" 1}
                                <div id="basket">
                                    <div style="text-align: center">
                                        <i style="font-size: 60px;padding:100px" class="fa fa-spinner fa-spin"></i>
                                    </div>

                                </div>
                            {else}
                                {include file="theme_1/blk.forbidden.theme_1.EcomB2B.tablet.tpl" data=$block key=$key   }
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
                                {include file="theme_1/blk.forbidden.theme_1.EcomB2B.tablet.tpl" data=$block key=$key   }
                            {/if}
                        {elseif $block.type=='checkout'}
                            {if $logged_in}{assign "with_checkout" 1}
                                <div id="checkout">
                                    <div style="text-align: center">
                                        <i style="font-size: 60px;padding:100px" class="fa fa-spinner fa-spin"></i>
                                    </div>
                                </div>
                            {else}
                                {include file="theme_1/blk.forbidden.theme_1.EcomB2B.tablet.tpl" data=$block key=$key   }
                            {/if}
                        {elseif $block.type=='favourites'}
                            {if $logged_in}{assign "with_favourites" 1}
                                <div id="favourites">
                                    <div style="text-align: center">
                                        <i style="font-size: 60px;padding:100px" class="fa fa-spinner fa-spin"></i>
                                    </div>
                                </div>
                            {else}
                                {include file="theme_1/blk.forbidden.theme_1.EcomB2B.tablet.tpl" data=$block key=$key   }
                            {/if}

                        {elseif $block.type=='thanks'}
                            {if $logged_in}{assign "with_thanks" 1}
                                <div id="thanks">
                                    <div style="text-align: center">
                                        <i style="font-size: 60px;padding:100px" class="fa fa-spinner fa-spin"></i>
                                    </div>

                                </div>
                            {else}
                                {include file="theme_1/blk.forbidden.theme_1.EcomB2B.tablet.tpl" data=$block key=$key   }
                            {/if}
                        {elseif $block.type=='login'}

                            {if !$logged_in}



                                {assign "with_login" 1}
                                {include file="theme_1/blk.{$block.type}.theme_1.EcomB2B.tablet.tpl" data=$block key=$key  }

                            {else}
                                {include file="theme_1/blk.already_logged_in.theme_1.EcomB2B.tablet.tpl" data=$block key=$key   }
                            {/if}
                        {elseif $block.type=='register'}

                            {if !$logged_in}
                                {assign "with_register" 1}
                                {include file="theme_1/blk.{$block.type}.theme_1.EcomB2B.tablet.tpl" data=$block key=$key  }

                            {else}
                                {include file="theme_1/blk.already_logged_in.theme_1.EcomB2B.tablet.tpl" data=$block key=$key   }
                            {/if}
                        {else}
                            {if $block.type=='search'   }{assign "with_search" 1}{/if}
                         {if $block.type=='reset_password'   }{assign "with_reset_password" 1}{/if}


                         {if $block.type=='unsubscribe'}{assign "with_unsubscribe" 1}{/if}

                            {if $block.type=='iframe'   }{assign "with_iframe" 1}{/if}
                            {if $block.type=='product'   }{assign "with_gallery" 1}{/if}
                            {if $block.type=='category_products' or   $block.type=='products'  or   $block.type=='product' }{assign "with_product_order_input" 1}{/if}

                            {include file="theme_1/blk.{$block.type}.theme_1.EcomB2B.tablet.tpl" data=$block key=$key  }

                        {/if}

                    {/if}
                {/foreach}





            {include file="theme_1/footer.theme_1.EcomB2B.tablet.tpl"}
        </div>
    </div>

    <a href="#" class="hide back-to-top-badge"><i class="fas fa-arrow-circle-up"></i></a>


</div>

<script>


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



        getScript('/js/tablet.min.js?v=2', function () {







            $('#header_search_icon').on("click", function () {

                window.location.href = "search.sys?q=" + encodeURIComponent($('#header_search_input').val());


            });





            getScript('/js/tablet.custom.min.js?v=2', function () {



                {if $with_reset_password==1}

                    getScript('/js/mobile.forms.min.js', function () {

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


                {/if}



                {if $with_search==1}


                var _args=document.location.href.split("?")[1];

                if(_args!=undefined){
                    args=_args.split("=");
                    if(args[1]!=undefined && args[0]=='q'){
                        $('#search_input').val( args[1])

                    }

                }
                if($('#search_input').val()!=''){
                    search($('#search_input').val())
                }




                {/if}

                {if $with_basket==1}



                getScript('/js/mobile.logged_in.min.js?v=2', function () {
                    getScript('/js/mobile.forms.min.js', function () {
                        getScript('/js/mobile.basket.min.js?v3', function () {




                        $.getJSON("ar_web_basket.php?tipo=get_basket_html&device_prefix=tablet", function (data) {

                            $('#basket').html(data.html)


                            if(data.empty){
                                getScript('/js/tablet.custom.min.js?v=2', function () {

                                })
                            }else{

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

                            }



                        })
                        })
                    })
                })

                {/if}
                {if $with_thanks==1}
                getScript('/js/mobile.logged_in.min.js?v=2', function () {

                    var _args=document.location.href.split("?")[1];

                    if(_args!=undefined) {
                        args = _args.split("=");
                        if (args[1] != undefined && args[0] == 'order_key') {
                            $.getJSON("ar_web_thanks.php?tipo=get_thanks_html&order_key="+args[1]+"&device_prefix=tablet", function (data) {

                                $('#thanks').html(data.html)


                            })

                        }else{
                            $('#thanks').html('')
                        }
                    }else{
                        $('#thanks').html('')
                    }





                })

                {/if}
                {if $with_checkout==1}
                getScript('/js/mobile.logged_in.min.js?v=2', function () {
                    getScript('/js/mobile.forms.min.js', function () {
                        getScript('/js/mobile.checkout.min.js?v2', function () {
                            $.getJSON("ar_web_checkout.php?tipo=get_checkout_html&device_prefix=tablet", function (data) {

                                $('#checkout').html(data.html)


                                $("form").submit(function(e) {

                                    e.preventDefault();
                                    e.returnValue = false;

                                    // do things
                                });



                            })
                        })
                    })
                })

                {/if}
                {if $with_favourites==1}
                getScript('/js/mobile.logged_in.min.js?v=2', function () {



                    $.getJSON("ar_web_favourites.php?tipo=get_favourites_html&device_prefix=tablet", function (data) {


                        $('#favourites').html(data.html)

                        $.getJSON("ar_web_customer_products.php?tipo=category_products&webpage_key={$webpage->id}", function (data) {

                            console.log(data)


                            $.each(data.ordered_products, function (index, value) {
                                $('.order_qty_' + index).val(value)
                            });


                            $.each(data.favourite, function (index, value) {
                                $('.favourite_' + index).removeClass('far').addClass('marked fas').data('favourite_key', value)
                            });

                            $.each(data.out_of_stock_reminders, function (index, value) {

                                var reminder_icon=$('.out_of_stock_reminders_' + index)
                                reminder_icon.removeClass('far').addClass('fas').data('out_of_stock_reminder_key', value).attr('title', reminder_icon.data('label_remove_notification'))
                            });



                        });


                    })

                })

                {/if}
                {if $with_profile==1}
                getScript('/js/mobile.forms.min.js', function () {
                    getScript('/js/mobile.profile.min.js', function () {
                    $.getJSON("ar_web_profile.php?tipo=get_profile_html&device_prefix=tablet", function (data) {


                        $('#profile').html(data.html)



                    })

                    })

                })
                {/if}

                {if $with_register==1}
                getScript('/js/mobile.forms.min.js', function () {


                $( "#country_select" ).change(function() {

                    var selected=$( "#country_select option:selected" )
                    // console.log(selected.val())

                    var request= "ar_web_addressing.php?tipo=address_format&country_code="+selected.val()+'&website_key={$website->id}'

                    console.log(request)
                    $.getJSON(request, function( data ) {
                        console.log(data)
                        $.each(data.hidden_fields, function(index, value) {
                            $('#'+value).addClass('hide')
                            $('#'+value).find('input').addClass('ignore')

                        });

                        $.each(data.used_fields, function(index, value) {
                            $('#'+value).removeClass('hide')
                            $('#'+value).find('input').removeClass('ignore')

                        });

                        $.each(data.labels, function(index, value) {
                            $('#'+index).find('input').attr('placeholder',value)
                            $('#'+index).find('b').html(value)

                        });

                        $.each(data.no_required_fields, function(index, value) {


                            // console.log(value)

                            $('#'+value+' input').rules( "remove" );




                        });

                        $.each(data.required_fields, function(index, value) {
                            console.log($('#'+value))
                            //console.log($('#'+value+' input').rules())

                            $('#'+value+' input').rules( "add", { required: true});

                        });


                    });


                });


                $("form").on('submit', function (e) {

                    e.preventDefault();
                    e.returnValue = false;

                });


                $("#registration_form").validate(
                    {

                        submitHandler: function(form)
                        {


                            if($('#register_button').hasClass('wait')){
                                return;
                            }

                            $('#register_button').addClass('wait')
                            $('#register_button i').removeClass('fa-arrow-right').addClass('fa-spinner fa-spin')

                            var register_data={ }

                            $("#registration_form input:not(.ignore)").each(function(i, obj) {
                                if(!$(obj).attr('name')==''){
                                    register_data[$(obj).attr('name')]=$(obj).val()
                                }

                            });

                            $("#registration_form select:not(.ignore)").each(function(i, obj) {
                                if(!$(obj).attr('name')==''){


                                    register_data[$(obj).attr('name')]=$(obj).val()
                                }

                            });



                            register_data['password']=sha256_digest(register_data['password']);

                            var ajaxData = new FormData();

                            ajaxData.append("tipo", 'register')
                            ajaxData.append("store_key", '{$store->id}')
                            ajaxData.append("data", JSON.stringify(register_data))


                            $.ajax({
                                url: "/ar_web_register.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false,
                                complete: function () {
                                }, success: function (data) {




                                    if (data.state == '200') {




                                        window.location.replace("welcome.sys");


                                    } else if (data.state == '400') {
                                        swal("{t}Error{/t}!", data.msg, "error")
                                    }

                                    $('#register_button').removeClass('wait')
                                    $('#register_button i').addClass('fa-arrow-right').removeClass('fa-spinner fa-spin')


                                }, error: function () {


                                    $('#register_button').removeClass('wait')
                                    $('#register_button i').addClass('fa-arrow-right').removeClass('fa-spinner fa-spin')


                                }
                            });


                        },

                        // Rules for form validation
                        rules:
                            {

                                email:
                                    {
                                        required: true,
                                        email: true,
                                        remote: {
                                            url: "ar_web_validate.php",
                                            data: {
                                                tipo:'validate_email_registered',
                                                website_key:'{$website->id}'
                                            }
                                        }

                                    },
                                password:
                                    {
                                        required: true,
                                        minlength: 8


                                    },
                                password_confirm:
                                    {
                                        required: true,
                                        minlength: 8,
                                        equalTo: "#register_password"
                                    },
                                contact_name:
                                    {
                                        required: true,

                                    },
                                mobile:
                                    {
                                        required: true,

                                    },
                                terms:
                                    {
                                        required: true,
                                    },

                {foreach from=$required_fields item=required_field }
                {$required_field}: { required: true },
                {/foreach}

                {foreach from=$no_required_fields item=no_required_field }
                {$no_required_field}:{   required: false},
                {/foreach}

            },

                // Messages for form validation
                messages:
                {

                    email:
                    {

                        required: '{if empty($labels._validation_required)}{t}Required field{/t}{else}{$labels._validation_required|escape}{/if}',
                            email: '{if empty($labels._validation_email_invalid)}{t}Invalid email{/t}{else}{$labels._validation_email_invalid|escape}{/if}',
                        remote: '{if empty($labels._validation_handle_registered)}{t}Email address is already in registered{/t}{else}{$labels._validation_handle_registered|escape}{/if}',


                    },
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
                    },
                    contact_name:
                    {
                        required: '{if empty($labels._validation_required)}{t}Required field{/t}{else}{$labels._validation_required|escape}{/if}',
                    },
                    mobile:
                    {
                        required: '{if empty($labels._validation_required)}{t}Required field{/t}{else}{$labels._validation_required|escape}{/if}',
                    },
                    terms:
                    {
                        required: '{if empty($labels._validation_accept_terms)}{t}Please accept our terms and conditions to proceed{/t}{else}{$labels._validation_accept_terms|escape}{/if}',


                    },
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

                // Do not change code below
                errorPlacement: function(error, element)
                {
                    error.insertAfter(element.parent());
                }
            });


});

    {/if}
                {if $with_login==1}

                getScript('/js/mobile.forms.min.js', function () {


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


                                    if (data.state == '200') {

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


                getScript('/js/image_gallery.min.js', function () {
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

                        console.log($(this))
                        console.log($(this).width() )

                        console.log( $(this).data('h'))
                        $(this).css({
                            height: $(this).width() * $(this).data('h') / $(this).data('w')
                        })
                    });
                }


                {/if}

                {if $logged_in}

                {if $with_product_order_input==1}

                $.getJSON( "ar_web_customer_products.php?tipo=category_products&webpage_key={$webpage->id}", function( data ) {






                    $.each(data.ordered_products, function( index, value ) {
                        $('.order_qty_'+index).val(value)
                    });


                    $.each(data.favourite, function( index, value ) {
                        $('.favourite_'+index).removeClass('far').addClass('marked fas').data('favourite_key',value)
                    });

                    $.each(data.out_of_stock_reminders, function (index, value) {

                        var reminder_icon=$('.out_of_stock_reminders_' + index)
                        reminder_icon.removeClass('far').addClass('fas').data('out_of_stock_reminder_key', value).attr('title', reminder_icon.data('label_remove_notification'))
                    });

                    $('#header_order_totals').find('.ordered_products_number').html(data.items)
                    $('#header_order_totals').find('.order_amount').html(data.total)
                    $('#header_order_totals').find('i').attr('title',data.label)



                });


                {else}
                $.getJSON("ar_web_customer_products.php?tipo=total_basket", function (data) {


                    $('#header_order_totals').find('.ordered_products_number').html(data.items)
                    $('#header_order_totals').find('.order_amount').html(data.total)
                    $('#header_order_totals').find('i').attr('title',data.label)




                });
                {/if}

                getScript('/js/mobile.logged_in.min.js?v=2', function () {
                    $('#logout i').removeClass('fa-spinner fa-spin').addClass('fa-sign-out')

                })

                {/if}


            })


        })









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


</body>
</html>

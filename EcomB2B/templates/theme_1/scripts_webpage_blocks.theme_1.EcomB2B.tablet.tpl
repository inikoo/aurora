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



        getScript("/assets/mobile.min.js", function () {

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
            $('#header_search_icon').on("click", function () {
                window.location.href = "search.sys?q=" + encodeURIComponent($('#header_search_input').val());
            });
            getScript("assets/mobile_custom.min.js", function () {

                {if $with_search==1}


                var _args=document.location.href.split("?")[1];

                if(_args!=undefined){
                    args=_args.split("=");
                    if(args[1]!=undefined && args[0]=='q'){
                        $('#search_input').val( decodeURI(args[1]))

                    }

                }
                if($('#search_input').val()!=''){
                    search($('#search_input').val())
                }

                {/if}
                {if $with_basket==1}



                getScript("/assets/mobile.logged_in.min.js", function () {
                    getScript("/assets/mobile.forms.min.js", function () {
                        getScript("/assets/mobile.basket.min.js", function () {
                            $.getJSON("ar_web_basket.php?tipo=get_basket_html&device_prefix=tablet", function (data) {

                                $('#basket').html(data.html)


                                if(data.empty){
                                    getScript("assets/mobile_custom.min.js", function () {

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
                getScript("/assets/mobile.logged_in.min.js", function () {





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
                        $.getJSON("ar_web_thanks.php?tipo=get_thanks_html&order_key="+order_key+"&device_prefix=tablet&timestamp="+timestamp+"&timestamp_server="+timestamp_server, function (data) {
                            $('#thanks').html(data.html)
                        })
                    }else{
                        $('#thanks').html('')
                    }






                })

                {/if}
                {if $with_client_basket==1}
                getScript("/assets/mobile.logged_in.min.js", function () {
                    getScript("/assets/mobile.forms.min.js", function () {
                        getScript("/assets/desktop.client_basket.min.js", function () {
                            $.getJSON("ar_web_client_basket.php?tipo=get_client_basket_html&client_key="+getUrlParameter('client_id')+"&device_prefix=tablet", function (data) {
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
                {if $with_checkout==1}
                getScript("/assets/mobile.logged_in.min.js", function () {
                    getScript("/assets/mobile.forms.min.js", function () {
                        getScript("/assets/mobile.checkout.min.js", function () {

                            let checkout_request="ar_web_checkout.php?tipo=get_checkout_html&device_prefix=tablet";
                            if(getUrlParameter('order_key')!=undefined){
                                checkout_request+='&client_order_key='+getUrlParameter('order_key')
                            }

                            $.getJSON(checkout_request, function (data) {

                                $('#checkout').html(data.html);


                                $("form").on( "submit",
                                    function(e) {

                                        e.preventDefault();
                                        e.returnValue = false;

                                    }
                                )


                            })
                        })
                    })
                });

                {/if}
                {if $with_favourites==1}


                getScript("/assets/mobile.logged_in.min.js", function () {



                    $.getJSON("ar_web_favourites.php?tipo=get_favourites_html&device_prefix=tablet", function (data) {


                        $('#favourites').html(data.html)

                            $.getJSON("ar_web_customer_products.php?with_favourites_products=Yes&tipo=category_products&webpage_key={$webpage->id}", function (data) {

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


                                $.each(data.stock, function (index, value) {
                                    if (value[0] != '') {
                                        $('.image_stock_hint_' + index).removeClass('Excess Normal Low VeryLow OutofStock Error OnDemand').addClass(value[0])
                                        $('.stock_level_' + index).removeClass('Excess Normal Low VeryLow OutofStock Error OnDemand').addClass(value[0]).attr('title', value[1])

                                    }
                                });


                        });


                    })

                })

                {/if}
                {if $with_profile==1}
                    getScript("/assets/mobile.forms.min.js", function () {
                    getScript("/assets/mobile.profile.min.js", function () {
                    $.getJSON("ar_web_profile.php?tipo=get_profile_html&device_prefix=tablet", function (data) {


                        $('#profile').html(data.html)



                    })

                    })

                })
                {/if}
                {if $with_client==1}
                getScript("/assets/mobile.forms.min.js", function () {
                    $.getJSON("ar_web_client.php?tipo=get_client_html&device_prefix=tablet", function (data) {
                        $('#client').html(data.html);
                        $('.breadcrumbs .client_nav').html(data.client_nav.label);
                        $('.breadcrumbs .client_nav').attr('title',data.client_nav.title);


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
                getScript("/assets/mobile.forms.min.js", function () {


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
                            $('#'+index).find('label.label').html(value)

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

                            {if $website->settings('captcha_client')}
                            register_data['captcha']= grecaptcha.getResponse();
                            {/if}

                            register_data['new-password'] = sha256_digest(register_data['new-password']);

                            var ajaxData = new FormData();

                            ajaxData.append("tipo", 'register')
                            ajaxData.append("store_key", '{$store->id}')
                            ajaxData.append("data", JSON.stringify(register_data))


                            $.ajax({
                                url: "/ar_web_register.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false,
                                complete: function () {
                                }, success: function (data) {




                                    if (data.state == '200') {

                                        ga('auTracker.send', 'event', 'Register', 'register');


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
                                'new-password':
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
                                name:
                                    {
                                        required: true,

                                    },
                                tel:
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
                    'new-password':
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
                    name:
                    {
                        required: '{if empty($labels._validation_required)}{t}Required field{/t}{else}{$labels._validation_required|escape}{/if}',
                    },
                    tel:
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
                {if $with_reset_password==1}

                getScript("/assets/mobile.forms.min.js", function () {

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

                        errorPlacement: function(error, element)
                                        {
                                            error.insertAfter(element.parent());
                                        }
                    });
                })

                {/if}
                {if $with_portfolio==1}
                    getScript("/assets/datatables.min.js", function () {

                    const request_data ={ "tipo":'portfolio',"device_prefix" :'tablet' };
                    $.ajax({

                        url: '/ar_web_tables.php', type: 'GET', dataType: 'json', data: request_data, success: function (data) {
                                if (data.state == 200) {
                                    state = data.app_state;
                                    $('.images_zip').removeClass('hide').attr('href',data.images_zip_url);
                                    $('.data_feed').removeClass('hide').attr('href',data.data_feed_url);
                                    $('#table_container').html(data.html);
                                }

                            }
                        });
                    })
                {/if}
                {if $with_clients==1}
                    getScript("/assets/mobile.forms.min.js", function () {
                    getScript("/assets/datatables.min.js", function () {

                        const request_data ={ "tipo":'clients',"device_prefix" :'tablet' }

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

                    $("form").on('submit',function(e) {
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

                getScript("/assets/mobile.forms.min.js", function () {


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

                        $(this).css({ height: $(this).width() * $(this).data('h') / $(this).data('w')})
                        $(this).find('iframe').css({ height:$(this).height(),width:$(this).width()})
                        $(this).find('iframe').attr( 'src', $(this).data('src'));
                    });
                }


                {/if}
                {if $logged_in}
                    {if $store->get('Store Type')=='Dropshipping' }
                        {if $with_products_portfolio==1}
                        getScript("/assets/dropshipping.logged_in.min.js", function () {
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
                });
                {/if}
                        getScript("/assets/mobile.logged_in.min.js", function () {

                            $('#logout i').removeClass('fa-spinner fa-spin').addClass('fa-sign-out')
                        })
                {else}
                    {if $with_product_order_input==1}

                $.getJSON("ar_web_customer_products.php?with_category_products={if $with_category_products==1}Yes{else}No{/if}&tipo=category_products&webpage_key={$webpage->id}", function (data) {


                    $.each(data.ordered_products, function (index, value) {
                        $('.order_qty_' + index).val(value)
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
                    $('#header_order_totals').find('i').attr('title',data.label)
                });
                {/if}
                    getScript("/assets/mobile.logged_in.min.js", function () {
                    $('#logout i').removeClass('fa-spinner fa-spin').addClass('fa-sign-out')

                })

                {/if}
                {/if}

            })
        })


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
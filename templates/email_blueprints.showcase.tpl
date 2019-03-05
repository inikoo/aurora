{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 15 August 2016 at 15:58:52 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3
-->
*}

<style>

    #blueprints_repo{
        border-bottom:1px solid #ccc;padding:20px 40px
    }

    #blueprints_repo .blueprint_option{
        width:200px;cursor:pointer;float:left;margin-left:20px;color:#999
    }

    #blueprints_repo .blueprint_option:hover{
       color:#777
    }

    #blueprints_repo > .blueprint_option:first{
        margin-left:0px
    }

    #blueprints_repo .blueprint_option img{
        border:1px solid #ccc
    }

    #blueprints_repo .blueprint_option:hover > img{
        border:1px solid #aaa
    }

</style>



{if isset($control_blueprint_template)}
    {include file=$control_blueprint_template}

{/if}



{if isset($blueprints_redirect)}
<div style="padding:15px 20px;border-bottom:1px solid #ccc;position: relative" class="control_panel">







    <span id="change_template" onclick="change_view(state.request + '&tab={$blueprints_redirect}')" class="button  " style="border:1px solid #ccc;padding:5px 10px;margin-left:40px"">
            <i class="fa fa-arrow-alt-square-left padding_right_5" aria-hidden="true"></i> {t}Go back to workshop{/t}
    </span>





</div>
{/if}


<div id="blueprints_repo">

    {if $role=='Registration'}

    <div blueprint='empty' class="blueprint_option">
        <img src="/conf/etemplates/empty.png"  />
        <div style="text-align: center">{t}Empty{/t}</div>
    </div>
        <div blueprint='welcome_minimalistic' class="blueprint_option">
            <img src="/conf/etemplates/welcome_minimalistic.png"  />
            <div style="text-align: center">{t}Minimalistic{/t}</div>
        </div>
    <div blueprint='welcome_default' class="blueprint_option">
        <img src="/conf/etemplates/welcome_default.png"   />
        <div style="text-align: center">{t}Light{/t}</div>
    </div>
        <div blueprint='welcome_cool' class="blueprint_option">
            <img src="/conf/etemplates/welcome_cool.png"   />
            <div style="text-align: center">{t}Fresh{/t}</div>
        </div>

    <div blueprint='welcome_simple' class="blueprint_option">
        <img src="/conf/etemplates/welcome_simple.png"  />
        <div style="text-align: center">{t}Standard{/t}</div>
    </div>

    {elseif $role=='Password Reminder'}

        <div blueprint='empty' class="blueprint_option">
            <img src="/conf/etemplates/empty.png"  />
            <div style="text-align: center">{t}Empty{/t}</div>
        </div>

        <div blueprint='reset_password' class="blueprint_option">
            <img src="/conf/etemplates/reset_password.png"  />
            <div style="text-align: center">{t}Reset password{/t}</div>
        </div>
        <div blueprint='reset_password_cool' class="blueprint_option">
            <img src="/conf/etemplates/reset_password_cool.png"  />
            <div style="text-align: center">{t}Reset password{/t}</div>
        </div>
    {elseif $role=='OOS Notification'}
        <div blueprint='empty' class="blueprint_option">
            <img src="/conf/etemplates/empty.png"  />
            <div style="text-align: center">{t}Empty{/t}</div>
        </div>
        <div blueprint='welcome_minimalistic' class="blueprint_option">
            <img src="/conf/etemplates/welcome_minimalistic.png"  />
            <div style="text-align: center">{t}Minimalistic{/t}</div>
        </div>
        <div blueprint='back_in_stock_notification_default' class="blueprint_option">
            <img src="/conf/etemplates/back_in_stock_notification_default.png"  />
            <div style="text-align: center">{t}Light{/t}</div>
        </div>

        <div blueprint='back_in_stock_notification_simple' class="blueprint_option">
            <img src="/conf/etemplates/back_in_stock_notification_simple.png"   />
            <div style="text-align: center">{t}Standard{/t}</div>
        </div>
    {elseif $role=='GR Reminder'}
        <div blueprint='empty' class="blueprint_option">
            <img src="/conf/etemplates/empty.png"  />
            <div style="text-align: center">{t}Empty{/t}</div>
        </div>
        <div blueprint='welcome_minimalistic' class="blueprint_option">
            <img src="/conf/etemplates/welcome_minimalistic.png"  />
            <div style="text-align: center">{t}Minimalistic{/t}</div>
        </div>


    {else}
        <div blueprint='empty' class="blueprint_option">
            <img src="/conf/etemplates/empty.png"  />
            <div style="text-align: center">{t}Empty{/t}</div>
        </div>
    {/if}



<div style="clear:both"></div>

</div>

<script>

    $('.blueprint_option').on( 'click',function() {

        label=$(this).find('div')

        label.data('label',label.html()).html('<i class="fa fa-spinner fa-spin  fa-fw"></i> {t}Loading{/t}')


        var ajaxData = new FormData();

        ajaxData.append("tipo", 'select_blueprint')
        ajaxData.append("role", '{$role}')
        ajaxData.append("scope", '{$scope}')
        ajaxData.append("scope_key", '{$scope_key}')

        ajaxData.append("blueprint", $(this).attr('blueprint'))

        console.log('ar_edit_email_template.php?tipo=select_blueprint&role={$role}&scope={$scope}&scope_key={$scope_key}&blueprint='+$(this).attr('blueprint'))

        $.ajax({
            url: "/ar_edit_email_template.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false,
            complete: function () {
            }, success: function (data) {


                if (data.state == '200') {

                   change_view(state.request  + '{$email_template_redirect}')

                    for (var key in data.update_metadata.class_html) {
                        $('.' + key).html(data.update_metadata.class_html[key])
                    }



                    for (var key in data.update_metadata.hide) {
                        $('.' + data.update_metadata.hide[key]).addClass('hide')
                    }

                    for (var key in data.update_metadata.show) {

                        $('.' + data.update_metadata.show[key]).removeClass('hide')
                    }



                    $('.email_template_not_set').addClass('hide')
                } else if (data.state == '400') {
                    label.html(label.data('label'))
                    swal(data.msg);
                }



            }, error: function () {

            }
        });

    });

    $('#create_text_only_email_template').on( 'click',function() {



        var ajaxData = new FormData();

        ajaxData.append("tipo", 'create_text_only_email_template')
        ajaxData.append("role", '{$role}')
        ajaxData.append("scope", '{$scope}')
        ajaxData.append("scope_key", '{$scope_key}')



        $.ajax({
            url: "/ar_edit_email_template.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false,
            complete: function () {
            }, success: function (data) {


                if (data.state == '200') {

                    change_view(state.request + '{$email_template_redirect}')
                } else if (data.state == '400') {
                    label.html(label.data('label'))
                    swal(data.msg);
                }



            }, error: function () {

            }
        });

    });

</script>
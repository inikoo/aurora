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


<div id="blueprints_repo">

    {if $role=='Welcome'}

    <div blueprint='empty' class="blueprint_option">
        <img src="/conf/etemplates/empty.png"  />
        <div style="text-align: center">{t}Empty{/t}</div>
    </div>
    <div blueprint='welcome_default' class="blueprint_option">
        <img src="/conf/etemplates/welcome_default.png"   />
        <div style="text-align: center">{t}Default{/t}</div>
    </div>
    <div blueprint='welcome_minimalistic' class="blueprint_option">
        <img src="/conf/etemplates/welcome_minimalistic.png"  />
        <div style="text-align: center">{t}Minimalistic{/t}</div>
    </div>

    <div blueprint='welcome_simple' class="blueprint_option">
        <img src="/conf/etemplates/welcome_simple.png"  />
        <div style="text-align: center">{t}Simple{/t}</div>
    </div>

    {/if}

<div style="clear:both"></div>

</div>

<script>

    $('.blueprint_option').click(function() {

        label=$(this).find('div')

        label.data('label',label.html()).html('<i class="fa fa-spinner fa-spin  fa-fw"></i> {t}Loading{/t}')


        var ajaxData = new FormData();

        ajaxData.append("tipo", 'select_blueprint')
        ajaxData.append("role", '{$role}')
        ajaxData.append("scope", '{$scope}')
        ajaxData.append("scope_key", '{$scope_key}')

        ajaxData.append("blueprint", $(this).attr('blueprint'))


        $.ajax({
            url: "/ar_edit_email_template.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false,
            complete: function () {
            }, success: function (data) {


                if (data.state == '200') {

                   change_view(state.request + '&tab=email_template')
                } else if (data.state == '400') {
                    label.html(label.data('label'))
                    swal(data.msg);
                }



            }, error: function () {

            }
        });

    });

</script>
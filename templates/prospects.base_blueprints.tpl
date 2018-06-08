{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 4 June 2018 at 05:37:46 GMT+8, Kuala Lumpur, Malaysia
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


        <div blueprint='empty' class="blueprint_option">
            <img src="/conf/etemplates/empty.png"  />
            <div style="text-align: center">{t}Empty{/t}</div>
        </div>




<div style="clear:both"></div>

</div>

<script>

    $('.blueprint_option').click(function() {

        label=$(this).find('div')

        label.data('label',label.html()).html('<i class="fa fa-spinner fa-spin  fa-fw"></i> {t}Loading{/t}')


        var ajaxData = new FormData();

        ajaxData.append("tipo", 'set_email_template_base')

        ajaxData.append("key", '{$email_template->id}')

        ajaxData.append("blueprint", $(this).attr('blueprint'))

        console.log('ar_edit_email_template.php?tipo=set_email_template_base&key={$email_template->id}&blueprint='+$(this).attr('blueprint'))

        $.ajax({
            url: "/ar_edit_email_template.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false,
            complete: function () {
            }, success: function (data) {


                if (data.state == '200') {

                   change_view(state.request)

                } else if (data.state == '400') {
                    label.html(label.data('label'))
                    swal(data.msg);
                }



            }, error: function () {

            }
        });

    });

    function select_base_template_from_table(element ,key,redirect){


        icon=$(element).find('i')


        if(icon.hasClass('fa-spin')){
            return
        }

        icon.addClass('fa-spinner fa-spin')


        var ajaxData = new FormData();

        ajaxData.append("tipo", 'select_base_template')

        ajaxData.append("template_email_key", '{$email_template->id}')

        ajaxData.append("base_template_key", key)



        $.ajax({
            url: "/ar_edit_email_template.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false,
            complete: function () {
            }, success: function (data) {

                if (data.state == '200') {

                    change_view(state.request + redirect)
                } else if (data.state == '400') {
                    icon.removeClass('fa-spinner fa-spin')

                    swal(data.msg);
                }



            }, error: function () {

            }
        });
    }



</script>
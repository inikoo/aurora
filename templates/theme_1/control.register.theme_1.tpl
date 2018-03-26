{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 4 July 2017 at 12:20:58 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}


{if $webpage->get('Website Registration Type')=='Open'}
<div id="redirect_options" class="hide options_dialog" style="">

    <i class="fa fa-window-close button" onclick="$(this).closest('div').addClass('hide')" aria-hidden="true" style="margin-bottom: 5px"></i>


    <table class="options">
        <tr>
            <td data-type="welcome" data-label="welcome.sys">{t}Welcome page{/t}</td>
        </tr>
        <tr>
            <td data-type="home" data-label='<i class="fa fa-home" aria-hidden="true"></i> {t}Home{/t}'>{t}Home page{/t}</td>
        </tr>
        <tr>
            <td data-type="referring_page" data-label="{t}Referring page{/t}">{t}Referring page{/t}</td>
        </tr>
    </table>

</div>

<span id="registration_form" class="button" style="border:1px solid #ccc;padding:5px 10px">
        <i class="fa fa-registered" aria-hidden="true"></i> {t}Registration form{/t}
</span>

<span class="success very_discreet " style="margin-left:20px;margin-right:20px;font-style: italic">@{t}Success{/t}:</span>

<span id="welcome_email" class="button  {if !($content.send_email==1 and $metadata.emails.welcome.published_key>0 and $metadata.emails.welcome.key>0)}very_discreet{/if}">
    <span onclick="change_view(state.request + '&subtab=webpage.email_template')"><i class="far envelope discreet" aria-hidden="true"></i> {t}Welcome email{/t}</span> <i id="send_email"
                                                                                                                                                                          class="fa fa-check {if $content.send_email==1 and $metadata.emails.welcome.published_key>0 and $metadata.emails.welcome.key>0}success{/if}"
                                                                                                                                                                          aria-hidden="true"></i>
    </span>


<span id="redirect_to" style="margin-left:20px;" class="button ">
    <i class="fa fa-share" aria-hidden="true"></i>  <span id="redirect" type="{$content.redirect}">{if $content.redirect=='welcome'}welcome.sys{elseif $content.redirect=='home'}
            <i class="fa fa-home" aria-hidden="true"></i>
                 {t}Home{/t}{else}{t}Referring page{/t}{/if}</span>
    </span>


<script>


    $('#redirect_to').on("click", function () {
        if ($('#redirect_options').hasClass('hide')) {

            $('#redirect_options').removeClass('hide').offset({
                top: $(this).offset().top - 40, left: $(this).offset().left + $(this).width() + 5
            })
        } else {
            $('#redirect_options').addClass('hide')
        }
    });


    $('#send_email').on("click", function () {

        {if $metadata.emails.welcome.published_key>0 and $metadata.emails.welcome.key>0}

        if ($(this).hasClass('success')) {
            $(this).removeClass('success').closest('#welcome_email').addClass('very_discreet')
        } else {
            $(this).addClass('success').closest('#welcome_email').removeClass('very_discreet')
        }
        $('#save_button').addClass('save button changed valid')

        {else}
        change_view(state.request + '&tab=email_template')
        {/if}

    });


    $('#redirect_options td').on('click', function (e) {

        $('#redirect').html($(this).data('label')).attr('type', $(this).data('type'))
        $('#save_button').addClass('save button changed valid')
        $('#redirect_options').addClass('hide')


    })


    function change_webpage_element_visibility(element) {


        if ($(element).hasClass('button')) {


            if ($(element).find('i.fa-check').hasClass('success')) {
                $(element).find('i.fa-check').removeClass('success').addClass('very_discreet')
                $('#preview')[0].contentWindow.change_webpage_element_visibility($(element).attr('id'), 'hide')
            } else {
                $(element).find('i.fa-check').addClass('success').removeClass('very_discreet')
                $('#preview')[0].contentWindow.change_webpage_element_visibility($(element).attr('id'), 'show')

            }


        }
    }

</script>
{else}

    <span id="registration_form" class="button" style="border:1px solid #ccc;padding:5px 10px">
        <i class="fa fa-registered" aria-hidden="true"></i> {t}Registration form{/t} ({t}Closed{/t})
</span>

{/if}
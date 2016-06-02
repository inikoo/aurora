{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 2 June 2016 at 12:07:56 CEST, Mijas Costa, Spain
 Copyright (c) 2016, Inikoo

 Version 3
-->
*}

<div style="padding:20px;border-bottom:1px solid #ccc">
<span><i class="fa fa-toggle-on" aria-hidden="true" ></i> {t}Logged in{/t}</span>
<span class="padding_left_20"><i class="fa fa-toggle-on button" onClick="toggle_marginals(this)" aria-hidden="true" ></i> {t}Marginals{/t}</span>

</div>




<iframe id="preview" style="width:100%;height:900px" frameBorder="0" src="/ecom/papp.php?website_key={$page->get('Webpage Website Key')}&request={$request}"></iframe>

<script>

function toggle_marginals(element) {

    if ($(element).hasClass('fa-toggle-on')) {
        $(element).removeClass('fa-toggle-on').addClass('fa-toggle-off')

        $('#preview').contents().find('#header').addClass('hide')
        $('#preview').contents().find('#footer').addClass('hide')

    } else {
        $(element).removeClass('fa-toggle-off').addClass('fa-toggle-on')
        $('#preview').contents().find('#header').removeClass('hide')
        $('#preview').contents().find('#footer').removeClass('hide')

    }

}


</script>
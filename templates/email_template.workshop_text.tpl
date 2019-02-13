{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 12 February 2019 at 12:40:56 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2019, Inikoo

 Version 3
-->
*}

<span class="hide" id="email_template_data" data-email_template_key="{$email_template->id}"></span>

{if isset($control_template)}
    {include file=$control_template}

{/if}



<div id="email_template_text_container" style="height:1000px;position:relative">

    <textarea id="email_template_text" style="width:1155px;min-height:600px;resize: vertical;padding:5px 20px;;margin:25px 20px 20px 20px">{$email_template->get('Email Template Text')}</textarea>

</div>

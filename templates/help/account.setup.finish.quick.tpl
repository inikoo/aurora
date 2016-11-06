<!-- 
About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 25 March 2016 at 19:34:13 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2016, Inikoo

 Version 3.0
-->

{if $account->get('Account Employees')==0}
    <p>{t}Well done, log in using <b>root</b> as username and assign some employees to the system {/t}.</p>
{else}
    <p>{t}Well done{/t}</p>
{/if}
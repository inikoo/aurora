{include file='header.tpl'}
<div id="bd" style="padding:0 20px">
<span class="nav2 onright"><a   href="companies.php">{t}Cancel{/t}</a></span>
<span class="nav2"><a href="contacts.php">{$home}</a></span>
<div id="yui-main" >
    
   {if $tipo=='company'}
    {include file='new_company_splinter.tpl'}
{else}
    {include file='new_contact_splinter.tpl'}

{/if}

      

    </div>
</div>
</div>
{include file='footer.tpl'}



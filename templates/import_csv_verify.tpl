{include file='header.tpl'}
<input type="hidden" id="scope" value="{$scope}">
<div id="bd">
<div id="no_details_title" style="clear:left;">
    <h1>{t}Import Contacts From CSV File{/t}</h1>
</div>
<div class="left3Quarters" style="text-align:right">
    <input type="hidden" name="form" value="form" />
    <div class="framedsection">
        <div id="call_table"></div>
    </div>
    <span class="button" id="insert_data" style="margin-right:20px">{t}Insert data{/t}</span>	
</div>
</div>
{include file='footer.tpl'}

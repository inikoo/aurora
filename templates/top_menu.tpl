{foreach from=$_content.sections item=section_link }
                    <div {if isset($section_link.id) and $section_link.id }id="{$section_link.id}"{/if}
                         class="section {if isset($section_link.selected) and $section_link.selected}selected{/if}" {if isset($section_link.reference) and $section_link.reference!=''}onclick="change_view('{$section_link.reference}')"{/if}
                         title="{if isset($section_link.title)}{$section_link.title}{elseif isset($section_link.label) }{$section_link.label}{else}*{/if}">
                        {if !empty($section_link.icon)}<i class="far fa-{$section_link.icon} "></i>{/if}
                        {if !empty($section_link.html_icon)}{$section_link.html_icon}{/if}
                        <span class="section_label"> {if isset($section_link.label) }{$section_link.label}{else}*{/if}</span>
                    </div>
                {/foreach}



{if isset($_scope)}{assign "scope" $_scope}{else}{assign "scope" "object_sticky_note"}{/if}
{if isset($title)}{assign "title" $title}{else}{assign "title" ""}{/if}
{if isset($color)}{assign "color" $color}{else}{assign "color" ""}{/if}


<div  class="sticky_note_container {$color} {$scope} {if $value==''}hide{/if}"  data-scope="{$scope}"  data-object="{$object}" data-key="{$key}"  data-field="{$field}"    >
    <span style="position: absolute;top:1px;left:4px;font-size: x-small">{$title}</span>
    <i style="top:30px;float: right" onclick="save_sticky_note('{$scope}')" class="fal save fa-cloud button super_discreet fa-fw" aria-hidden="true"></i>

    <i style="top:30px;right:40px" class="fal fa-trash-alt delete_sticky_note button fa-fw" aria-hidden="true"></i>
    <div class="sticky_note" contenteditable="true" >{$value|strip_tags|escape}</div>
</div>

<script>
    $('.sticky_note_container.{$scope}').on('keyup paste copy cut', '[contenteditable]', function () {
        $(this).closest('.sticky_note_container').find('.save').removeClass('fal fa-check super_discreet').addClass('fa fa-cloud')

    })
</script>


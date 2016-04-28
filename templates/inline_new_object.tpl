<div id="inline_form" style="float:right" class="hide">
<span id="invalid_msg" class="hide">{t}Invalid value{/t}</span> 

<span id="fields" object='{$data.object}' key='' parent='{$data.parent}' parent_key='{$data.parent_key}' >
<span id="inline_new_object" class=" hide"   object='{$data.object}' key='' parent='{$data.parent}' parent_key='{$data.parent_key}' field='{$data.field_id}' >
<span id="{$data.field_id}_container" class="value " _required="true" field_type='{$data.field_edit}' field='{$data.field_id}'  server_validation=''> 
{$data.field_label} 

<input id="{$data.field_id}" class="{$data.field_edit}_input_field " value="" placeholder="{if isset($data.placeholder)}{$data.placeholder}{/if}">
{if $data.field_edit=='time'}
<input type="hidden" id="{$data.field_id}_date"  value="{$data.date}">
{/if}
<i id="{$data.object}_save"  class=" fa fa-cloud fa-fw save"></i> </span> 
</span> 
</span>
</div>
<script>

$('#{$trigger}').on("click", function() {

    toggle_inline_new_object_form('{$trigger}')

});



$("#{$data.field_id}").keypress(function(e) {

    if (!$('#{$data.object}_save').hasClass('fa-cloud')) {
        e.preventDefault(e);
        return;
    }
});


$("#{$data.field_id}").on("input propertychange", function(evt) {

    if (!$('#{$data.object}_save').hasClass('fa-cloud')) {
        evt.preventDefault(ent);
        return;
    }
    var delay = 50;
    if (window.event && event.type == "propertychange" && event.propertyName != "value") return;
    delayed_on_change_field($(this), delay)

});

$("#{$data.object}_save").on("click", function(evt) {
    save_inline_new_object('{$trigger}')
});

$("#inline_new_object_msg").on("click", function(evt) {
   $("#inline_new_object_msg").html('').removeClass('success error')
   
});


</script>
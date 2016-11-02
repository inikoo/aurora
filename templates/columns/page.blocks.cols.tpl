{*/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 1 June 2016 at 11:41:48 CEST, Mijas Costa, Spain
 Copyright (c) 2016, Inikoo

 Version 3
*/*}

var columns = [
{
name: "id",
label: "",
editable: false,
renderable: false,
cell: "string"
}, {
name: "template",
label: "{t}Template{/t}",
editable: false,
cell: Backgrid.Cell.extend({
events: {
"click": function() {
change_view( '{$data['object']}/{$data['key']}/version/' + this.model.get("id"))

}
},
className: "link",

render: function () {
this.constructor.__super__.render.apply(this, arguments);


this.$el.empty();
var rawValue = this.model.get(this.column.get("name"));
var formattedValue = this.formatter.fromRaw(rawValue, this.model);
this.$el.append(formattedValue);
this.delegateEvents();


if(this.model.get('id')==''){
this.$el.removeClass('link');
}
return this;
}


})
}


]
function change_table_view(view,save_state){}

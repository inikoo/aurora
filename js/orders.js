/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 26 August 2015 23:18:02 GMT+8 Singapore

 Copyright (c) 2015, Inikoo

 Version 3.0
*/
 
 
 function get_navigation(view, parent, parent_key) {
     var request = "ar_views.php?tipo=navigation&view=" + view + '&parent=' + parent + '&parent_key=' + parent_key;
     $.getJSON(request, function(data) {
         $('#navigation').html(data.resp)
     });
 }

 $(function() {

     get_navigation($('#block_view').val(), 'store', $('#store_key').val())
     $(document).on('click', '#section_links .section', {}, function(e) {

         $("#blocks .block").addClass("hide");
         $("#block_" + this.id).removeClass("hide");

         get_navigation(this.id, 'store', $('#store_key').val())

     })

 });

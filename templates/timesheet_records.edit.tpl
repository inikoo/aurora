{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 12 January 2018 at 16:29:40 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3
-->
*}


<div id="set_timesheet_record_note" class="hide" style="position:absolute;border:1px solid #ccc;background-color: white;padding:15px 10px 5px 10px;z-index: 100">

   <table >
       <tr style="height: 15px">
           <td class="aright" style="padding-bottom: 0px"> <i style="position:relative;top:-7px;margin-right:10px" class="fa fa-window-close button" onClick="close_timesheet_record_notes()" aria-hidden="true"></i></td>
       </tr>
       <tr>
           <td><textarea style="width: 200px"></textarea></td>
       </tr>
       <tr class="aright">
           <td><i  onClick="save_timesheet_record_notes()" class="fa  fa-cloud fa-fw button  save    " aria-hidden="true"></i></td>
       </tr>
   </table>



</div>

<script>

    $("#set_timesheet_record_note").on("input propertychange", function (evt) {

        $('#set_timesheet_record_note').find('i.save').addClass('changed valid')
    })

    function open_timesheet_record_notes(element) {

        var element=$(element)
        var offset = element.offset()

        $('#set_timesheet_record_note').removeClass('hide').attr('key',element.attr('key')).offset({
        top: offset.top -7.5,
        left: offset.left +element.width()- $('#set_timesheet_record_note').width()-20}).data('element',element).find('textarea').val(element.find('.note').html())
    }

    function close_timesheet_record_notes(element) {
        $('#set_timesheet_record_note').addClass('hide')

    }

    function save_timesheet_record_notes(){

     if($('#set_timesheet_record_note').find('i.save').hasClass('valid')) {
         $('#set_timesheet_record_note').find('i.save').addClass('fa-spinner fa-spin').removeClass('valid changed')
         var request = '/ar_edit.php?tipo=edit_field&object=timesheet_record&key=' + $('#set_timesheet_record_note').attr('key') + '&field=Timesheet_Record_Note&value=' + $('#set_timesheet_record_note').find('textarea').val()
         console.log(request)


         $.getJSON(request, function (r) {

             $('#set_timesheet_record_note').find('i.save').removeClass('fa-spinner fa-spin')

             console.log(r)
             element = $('#set_timesheet_record_note').data('element');
             element.find('.note').html(r.value)
             if(r.value==''){
                 element.find('i.fa-sticky-note').removeClass('hide')
             }else{
                 element.find('i.fa-sticky-note').addClass('hide')

             }
             close_timesheet_record_notes();


         });

     }

    }

</script>
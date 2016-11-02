/*Author: Raul Perusquia <raul@inikoo.com>
 Created: 12 July 2016 at 13:09:51 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo
 Version 3.0*/

function post_table_rendered(otable) {


    var object_data = JSON.parse(atob($('#object_showcase div.order').data("object")))
    if (object_data.automatic_placement_locations == 'Yes') {

        $('#table  tbody   tr .part_locations').each(
            function (i, obj) {
                var part_location = $(obj).find('div.part_location')
                if (part_location.length == 1) {

                    set_placement_location(part_location)
                }
            }
        )

    }
}

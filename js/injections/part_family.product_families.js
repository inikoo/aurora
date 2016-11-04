/*Author: Raul Perusquia <raul@inikoo.com>
 Created: 21 June 2016 at 11:03:00 BST, Sheffield, UK
 Copyright (c) 2016, Inikoo
 Version 3.0*/

function open_new_product_family(store_key) {
    change_view(state.request + '&tab=part_family.product_family.new', {
        store_key: store_key
    })
}

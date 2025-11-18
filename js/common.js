/*Author: Raul Perusquia <raul@inikoo.com>
 Created: 23 February 2019 at 13:40:22 GMT+8, Kuala Lumpur Malaysia
 Copyright (c) 2015, Inikoo
 Version 3.0*/

String.prototype.capitalize = function() {
    return this.charAt(0).toUpperCase() + this.slice(1);
}
<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sun, 01 Aug 2021 02:35:19 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 3.0
 */

namespace Aurora\Interfaces\TaxCategory;

use Aurora\Utilities\Address;
use Aurora\Utilities\TaxNumber;

interface TaxCategoryProvider
{
    public function getTaxCategory(Address $invoice_address, Address $delivery_address, TaxNumber $taxNumber);
}




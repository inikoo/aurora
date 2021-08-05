<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sun, 01 Aug 2021 00:57:17 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 3.0
 */

namespace Aurora\Traits;

use Aurora\Utilities\TaxNumber;

trait ObjectTaxNumberTrait {

    function getTaxNumber($prefix): TaxNumber {

        return new TaxNumber(
            $this->data[$prefix.' Tax Number'], $this->data[$prefix.' Tax Number Valid']=='Yes'
        );

    }


}
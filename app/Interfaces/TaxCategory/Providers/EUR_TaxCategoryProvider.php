<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sun, 01 Aug 2021 02:32:31 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 3.0
 */

namespace Aurora\Interfaces\TaxCategory\Providers;

use Aurora\Interfaces\TaxCategory\TaxCategoryProvider;
use Aurora\Models\Utils\TaxCategory;
use PDO;

class EUR_TaxCategoryProvider implements TaxCategoryProvider
{
    private string $base_country;
    private PDO $db;

    function __construct(PDO $db, $base_country)
    {
        $this->db           = $db;
        $this->base_country = $base_country;
    }

    public function getTaxCategory($invoice_address, $delivery_address, $taxNumber): TaxCategory
    {
        $tax_category = new TaxCategory($this->db);
        if ($invoice_address->getCountryCode() == $this->base_country or $delivery_address->getCountryCode() == $this->base_country) {
            return $tax_category->withCountryType($this->base_country, 'Standard');
        }

        if ($delivery_address->isEuropeanUnion()) {
            if ($taxNumber->isValid()) {
                return $tax_category->withCountryType($this->base_country, 'EU_VTC');
            } else {
                $countryCode = $delivery_address->getCountryCode();

                if ($countryCode == 'MC') {
                    return $tax_category->withCountryType('FR', 'Standard');
                }
                if ($countryCode == 'PT') {
                    if (preg_match('/^(90|91|92|93|94)/', $delivery_address->getPostalCode())) {
                        return $tax_category->withCountryType('PT', 'Standard-RAM');
                    }
                    if (preg_match('/^9/', $delivery_address->getPostalCode())) {
                        return $tax_category->withCountryType('PT', 'Standard-RAA');
                    }
                }
                if ($countryCode == 'ES') {
                    if (preg_match('/^(35|38|51|52)/', $delivery_address->getPostalCode())) {
                        return $tax_category->withCountryType($this->base_country, 'Outside');
                    }
                }


                return $tax_category->withCountryType($delivery_address->getCountryCode(), 'Standard');
            }
        }


        return $tax_category->withCountryType($this->base_country, 'Outside');
    }
}


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
    private PDO $db;
    private string $base_country;

    //private string $tax_category_code_prefix;

    function __construct(PDO $db, $base_country, bool $is_RE = false)
    {
        $this->db           = $db;
        $this->base_country = $base_country;
        $this->is_RE        = $is_RE;
    }

    public function getTaxCategory($invoice_address, $delivery_address, $taxNumber): TaxCategory
    {
        $tax_category = new TaxCategory($this->db);
        $deliveryCountryCode = $delivery_address->getCountryCode();




        if ($invoice_address->getCountryCode() == $this->base_country or $deliveryCountryCode == $this->base_country) {
            if ($this->base_country == 'ES') {
                if ($delivery_address->getCountryCode() == 'ES' and preg_match('/^(35|38|51|52)/', $delivery_address->getPostalCode())) {
                    return $tax_category->loadWithKey(1);
                }

                if ($this->is_RE) {
                    return $tax_category->loadWithCodeCountry('ES-SR+RE', 'ES');
                } else {
                    return $tax_category->loadWithCodeCountry('ES-SR', 'ES');
                }
            } else {
                return $tax_category->loadWithTypeCountry('Standard', $this->base_country);
            }
        }

        if ($delivery_address->isEuropeanUnion() and $invoice_address->isEuropeanUnion() and $taxNumber->isValid()) {
            return $tax_category->loadWithKey(2);
        }

        if ($deliveryCountryCode == 'MC') {
            return $tax_category->loadWithTypeCountry('Standard', 'FR');
        }
        if ($deliveryCountryCode == 'PT') {
            if (preg_match('/^(90|91|92|93|94)/', $delivery_address->getPostalCode())) {
                return $tax_category->loadWithCodeCountry('PT-SR-RAM', 'PT');
            }
            if (preg_match('/^9/', $delivery_address->getPostalCode())) {
                return $tax_category->loadWithCodeCountry('PT-SR-RAA', 'PT');
            }
        }
        if ($deliveryCountryCode == 'ES') {
            if (preg_match('/^(35|38|51|52)/', $delivery_address->getPostalCode())) {
                return $tax_category->loadWithKey(1);
            }
        }

        if ($delivery_address->isEuropeanUnion()) {
            return $tax_category->loadWithTypeCountry('Standard', $deliveryCountryCode);
        }


        return $tax_category->loadWithKey(1);
    }
}


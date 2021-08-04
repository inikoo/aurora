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
    private string $tax_category_code_prefix;

    function __construct(PDO $db, $base_country,$tax_category_code_prefix='')
    {
        $this->db           = $db;
        $this->base_country = $base_country;
        $this->tax_category_code_prefix=$tax_category_code_prefix;
    }

    public function getTaxCategory($invoice_address, $delivery_address, $taxNumber): TaxCategory
    {
        $tax_category = new TaxCategory($this->db);

        if ($invoice_address->getCountryCode() == $this->base_country or $delivery_address->getCountryCode() == $this->base_country) {
            return $tax_category->loadWithCodeCountry($this->tax_category_code_prefix.'S1',$this->base_country);
        }

        if ($delivery_address->isEuropeanUnion()) {
            if ($taxNumber->isValid()) {
                return $tax_category->loadWithCodeCountry($this->tax_category_code_prefix.'EU',$this->base_country);
            } else {
                $countryCode = $delivery_address->getCountryCode();

                if ($countryCode == 'MC') {
                    return $tax_category->loadWithTypeCountry('Standard','FR');
                }
                if ($countryCode == 'PT') {
                    if (preg_match('/^(90|91|92|93|94)/', $delivery_address->getPostalCode())) {
                        return $tax_category->loadWithTypeCountry('Standard-RAM','PT' );
                    }
                    if (preg_match('/^9/', $delivery_address->getPostalCode())) {
                        return $tax_category->loadWithTypeCountry('Standard-RAA','PT');
                    }
                }
                if ($countryCode == 'ES') {
                    if (preg_match('/^(35|38|51|52)/', $delivery_address->getPostalCode())) {
                        return $tax_category->loadWithCodeCountry($this->tax_category_code_prefix.'OUT',$this->base_country);
                    }
                }


                return $tax_category->loadWithTypeCountry('Standard',$delivery_address->getCountryCode());
            }
        }


        return $tax_category->loadWithCodeCountry($this->tax_category_code_prefix.'OUT',$this->base_country);
    }
}


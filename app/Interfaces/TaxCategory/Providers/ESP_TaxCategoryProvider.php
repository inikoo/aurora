<?php /** @noinspection DuplicatedCode */

/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 02 Aug 2021 04:58:35 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 3.0
 */

namespace Aurora\Interfaces\TaxCategory\Providers;

use Aurora\Interfaces\TaxCategory\TaxCategoryProvider;
use Aurora\Models\Utils\TaxCategory;
use PDO;

class ESP_TaxCategoryProvider implements TaxCategoryProvider
{
    private string $base_country;
    private PDO $db;

    private bool $is_RE;
    /**
     * @var string[]
     */
    private array $taxable_countries;

    function __construct(PDO $db,bool $is_RE=false)
    {
        $this->db           = $db;
        $this->base_country = 'ES';
        $this->taxable_countries = ['ES', 'XX'];
        $this->is_RE = $is_RE;
    }

    public function getTaxCategory($invoice_address, $delivery_address, $taxNumber): TaxCategory
    {
        $tax_category = new TaxCategory($this->db);

        if ( $delivery_address->getCountryCode() == 'ES'  and preg_match('/^(35|38|51|52)/', $delivery_address->getPostalCode())  ) {
            return $tax_category->withCountryType($this->base_country, 'Exempt');
        }

        if (in_array($invoice_address->getCountryCode(), $this->taxable_countries) or in_array($delivery_address->getCountryCode(), $this->taxable_countries)) {
            if($this->is_RE){
                return $tax_category->withCountryType($this->base_country, 'IVA+RE');
            }else{
                return $tax_category->withCountryType($this->base_country, 'IVA');
            }
        }

        if ($delivery_address->isEuropeanUnion()) {
            if ($taxNumber->isValid()) {
                return $tax_category->withCountryType($this->base_country, 'EU_VTC');
            } else {
                return $tax_category->withCountryType($this->base_country, 'IVA');
            }
        }


        return $tax_category->withCountryType($this->base_country, 'Outside');
    }
}


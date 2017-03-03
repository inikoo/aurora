<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 3 March 2017 at 16:44:29 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3

*/

function get_default_footer_data($website,$template) {

    $time_series = array(
        'Account' => array(
            array(
                'Timeseries Type'      => 'AccountSales',
                'Timeseries Frequency' => 'Daily',
                'Timeseries Scope'     => 'Sales',
                'Timeseries Parent'    => 'Account',
            ),
            array(
                'Timeseries Type'      => 'AccountSales',
                'Timeseries Frequency' => 'Weekly',
                'Timeseries Scope'     => 'Sales',
                'Timeseries Parent'    => 'Account',
            ),
            array(
                'Timeseries Type'      => 'AccountSales',
                'Timeseries Frequency' => 'Monthly',
                'Timeseries Scope'     => 'Sales',
                'Timeseries Parent'    => 'Account',
            ),
            array(
                'Timeseries Type'      => 'AccountSales',
                'Timeseries Frequency' => 'Quarterly',
                'Timeseries Scope'     => 'Sales',
                'Timeseries Parent'    => 'Account',
            ),
            array(
                'Timeseries Type'      => 'AccountSales',
                'Timeseries Frequency' => 'Yearly',
                'Timeseries Scope'     => 'Sales',
                'Timeseries Parent'    => 'Account',
            )




        ),
        'Store' => array(
            array(
                'Timeseries Type'      => 'StoreSales',
                'Timeseries Frequency' => 'Daily',
                'Timeseries Scope'     => 'Sales',
                'Timeseries Parent'    => 'Store',
            ),
            array(
                'Timeseries Type'      => 'StoreSales',
                'Timeseries Frequency' => 'Weekly',
                'Timeseries Scope'     => 'Sales',
                'Timeseries Parent'    => 'Store',
            ),
            array(
                'Timeseries Type'      => 'StoreSales',
                'Timeseries Frequency' => 'Monthly',
                'Timeseries Scope'     => 'Sales',
                'Timeseries Parent'    => 'Store',
            ),
            array(
                'Timeseries Type'      => 'StoreSales',
                'Timeseries Frequency' => 'Quarterly',
                'Timeseries Scope'     => 'Sales',
                'Timeseries Parent'    => 'Store',
            ),
            array(
                'Timeseries Type'      => 'StoreSales',
                'Timeseries Frequency' => 'Yearly',
                'Timeseries Scope'     => 'Sales',
                'Timeseries Parent'    => 'Store',
            )




        ),

        'Supplier'        => array(
            array(
                'Timeseries Type'      => 'SupplierSales',
                'Timeseries Frequency' => 'Daily',
                'Timeseries Scope'     => 'Sales',
                'Timeseries Parent'    => 'Supplier'
            ),
            array(
                'Timeseries Type'      => 'SupplierSales',
                'Timeseries Frequency' => 'Weekly',
                'Timeseries Scope'     => 'Sales',
                'Timeseries Parent'    => 'Supplier'
            ),
            array(
                'Timeseries Type'      => 'SupplierSales',
                'Timeseries Frequency' => 'Monthly',
                'Timeseries Scope'     => 'Sales',
                'Timeseries Parent'    => 'Supplier'
            ),
            array(
                'Timeseries Type'      => 'SupplierSales',
                'Timeseries Frequency' => 'Quarterly',
                'Timeseries Scope'     => 'Sales',
                'Timeseries Parent'    => 'Supplier'
            ),
            array(
                'Timeseries Type'      => 'SupplierSales',
                'Timeseries Frequency' => 'Yearly',
                'Timeseries Scope'     => 'Sales',
                'Timeseries Parent'    => 'Supplier'
            ),






        ),
        'ProductCategory' => array(


            array(
                'Timeseries Type'      => 'ProductCategorySales',
                'Timeseries Frequency' => 'Daily',
                'Timeseries Scope'     => 'Sales',
                'Timeseries Parent'    => 'Category'
            ),

            array(
                'Timeseries Type'      => 'ProductCategorySales',
                'Timeseries Frequency' => 'Weekly',
                'Timeseries Scope'     => 'Sales',
                'Timeseries Parent'    => 'Category'
            ),
            array(
                'Timeseries Type'      => 'ProductCategorySales',
                'Timeseries Frequency' => 'Monthly',
                'Timeseries Scope'     => 'Sales',
                'Timeseries Parent'    => 'Category'
            ),
            array(
                'Timeseries Type'      => 'ProductCategorySales',
                'Timeseries Frequency' => 'Quarterly',
                'Timeseries Scope'     => 'Sales',
                'Timeseries Parent'    => 'Category'
            ),
            array(
                'Timeseries Type'      => 'ProductCategorySales',
                'Timeseries Frequency' => 'Yearly',
                'Timeseries Scope'     => 'Sales',
                'Timeseries Parent'    => 'Category'
            )


        ),
        'PartCategory'    => array(


            array(
                'Timeseries Type'      => 'PartCategorySales',
                'Timeseries Frequency' => 'Daily',
                'Timeseries Scope'     => 'Sales',
                'Timeseries Parent'    => 'Category'
            ),

            array(
                'Timeseries Type'      => 'PartCategorySales',
                'Timeseries Frequency' => 'Weekly',
                'Timeseries Scope'     => 'Sales',
                'Timeseries Parent'    => 'Category'
            ),
            array(
                'Timeseries Type'      => 'PartCategorySales',
                'Timeseries Frequency' => 'Monthly',
                'Timeseries Scope'     => 'Sales',
                'Timeseries Parent'    => 'Category'
            ),
            array(
                'Timeseries Type'      => 'PartCategorySales',
                'Timeseries Frequency' => 'Quarterly',
                'Timeseries Scope'     => 'Sales',
                'Timeseries Parent'    => 'Category'
            ),
            array(
                'Timeseries Type'      => 'PartCategorySales',
                'Timeseries Frequency' => 'Yearly',
                'Timeseries Scope'     => 'Sales',
                'Timeseries Parent'    => 'Category'
            )


        )

    );

    return $time_series;

}

?>
